<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\DB;
use Validator;

class BookmarkController extends Controller
{
    use ApiResponses;

    public function addbookmark(Request $request) {
        $validator = Validator::make(request()->all(), [
            'destination_id' => 'required|numeric|max:255',
            'user_id' => 'required|numeric|max:255',
        ]);
        
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors(), 'bookmark failed to add');
        }

        $user = auth('api')->user();

        DB::table('bookmarks')->insert([
            'destination_id' => $request['destination_id'],
            'user_id' => $user->username,
        ]);

        return $this->requestSuccess('bookmark successfully added');
    }
}
