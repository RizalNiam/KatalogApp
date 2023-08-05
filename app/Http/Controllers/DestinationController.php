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
            'name' => 'string|max:255',
            'description' => 'string|max:2048',
            'facility' => 'string|max:255',
            'budget' => 'string|max:255',
            'photo' => 'image|file|max:10240',
            'category' => 'string|max:255',
        ]);
        
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors(), 'destination failed to add');
        }

        $path = $request->file('photo')->store('public', 'public');
        $link = "https://magang.crocodic.net/ki/RizalAfifun/KatalogApp/storage/app/public/";
        $link .= $path;

        DB::table('destinations')->insert([
            'name' => $request['name'],
            'description' => $request['description'],
            'facility' => $request['facility'],
            'budget' => $request['budget'],
            'photo' => $link,
            'category' => $request['category'],
        ]);

        return $this->requestSuccess('destination successfully added');
    }

    function get_children_destinations() {
        $user = auth("api")->user();

        $rawData = DB::table('destinations')
        ->select('id', 'name', 'photo', 'category', 'budget', 'created_at', 'updated_at')
        ->where('category', '=', 'children')
        ->get(); 
        
        return $this->requestSuccessData('Success!', $rawData);
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

    public function get_img_slider()
    {
        $user = auth("api")->user();

        $limit = $_GET['limit'];

        $rawData = DB::table('destinations')
        ->select('id', 'name', 'photo')
        ->take($limit)
        ->get(); 
        
        return $this->requestSuccessData('Success!', $rawData);
    }
}
