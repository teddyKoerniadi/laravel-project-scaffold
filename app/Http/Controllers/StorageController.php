<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class StorageController extends Controller
{
    public function upload(Request $request) {
      $req = $request->all();
  
      $validation = Validator::make($req, [
        'folder' => 'required|string',
        'file' => 'required|mimes:jpeg,jpg,png,pdf|max:20480'
      ]);
  
      if($validation->fails()) {
        return response()->json([
          'success' => false,
          'message' => $validation->errors(),
          'status' => 422
        ], 422);
      };
  
      $path = 'public/'.$req['folder'];
      $name = rand(10000,99999)."-".str_replace(" ", "_", $request->file->getClientOriginalName());

      $upload = Storage::putFileAs($path, $req['file'], $name);
      $url = Storage::url($upload);
  
      return response()->json([ 'success' => true, 'url' => config('app.url').$url ]);
    }
  
}
