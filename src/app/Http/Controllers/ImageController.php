<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    //
    public function upload(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'image' => 'required | file | max:500',
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Error al subir la imagen', "error_list"=>$validator->errors()], 400);
        }
        $file = $request->file('image');
        $path = $request->input('path');
        $filename= time(). '-'. $file->getClientOriginalName();
        $file->storeAs($path, $filename);
        $url = $path . '/' . $filename;
        return response()->json(['response' => true, 'message' => "Imagen subida correctamente", "error_list"=>$validator->errors(), 'image' => $url]);
        
    }

    public function delete(Request $request)
    {
        //
        $image = $request->input('image');
        Storage::delete($image);
        return response()->json(['response' => true,'message' => 'Imagen borrada', 'image' => $image]);
    
    }
}
