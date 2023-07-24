<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\DB;
use Validator;

class DestinationController extends Controller
{
    use ApiResponses;

    public function add_destination(Request $request) {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'facility' => 'required|string|max:255',
            'budget' => 'required|string|max:255',
            'photo' => 'required|image|file|max:10240',
            'category_id' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors(), 'destination failed to add');
        }

        $path = $request->file('photo')->store('public', 'public');
        $link = "https://magang.crocodic.net/ki/RizalAfifun/KatalogApp/storage/app/public/";
        $link .= $path;

        DB::table('destinations')->insert([
            'name' => $request['name'],
            'city' => $request['city'],
            'description' => $request['description'],
            'facility' => $request['facility'],
            'budget' => $request['budget'],
            'photo' => $link,
            'category_id' => $request['category_id'],
        ]);

        return $this->requestSuccess('destination successfully added');
    }

    public function delete_image()
    {
        $user = auth('api')->user();

        $file = storage_path('/app/public/public') . $user->photo;

        if (file_exists($file)) {
            @unlink($file);
        }

        $user->delete;
    }
}
