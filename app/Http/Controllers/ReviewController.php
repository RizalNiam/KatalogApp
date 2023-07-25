<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\DB;
use Validator;

class ReviewController extends Controller
{
    use ApiResponses;

    public function addreview(Request $request) {
        $validator = Validator::make(request()->all(), [
            'destination_id' => 'required|numeric|max:255',
            'rating' => 'required|numeric|max:255',
            'description' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors(), 'review failed to add');
        }

        $user = auth('api')->user();

        DB::table('reviews')->insert([
            'destination_id' => $request['destination_id'],
            'rating' => $request['rating'],
            'description' => $request['description'],
            'user_id' => $user->id,
            'user_name' => $user->username,
        ]);

        return $this->requestSuccess('review successfully added');
    }
}
