<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Traits\ApiResponses;
use Validator;

class DestinationController extends Controller
{
    use ApiResponses;

    public function add_destination(Request $request) {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'facility' => 'required|string|max:255',
            'budget' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors(), 'tempat gagal ditambahkan, silahkan coba kembali');
        }
 
        $destination = Destination::create($request->all());

        return $this->requestSuccess();
    }
}
