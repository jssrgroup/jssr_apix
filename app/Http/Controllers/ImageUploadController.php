<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUpload($name)
    {
        $requestData = [];
        $requestData['name'] = $name;
        $validator = Validator::make($requestData, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $key = env("AWS_KEY", "images/");
        $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($key.$validator->validated()['name'], now()->addMinutes(1));

        
        return response()->json([
            "success" => true,
            "message" => "You have image.",
            // "key" => $key,
            "url" => $temporarySignedUrl
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUploadPost(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $imageName = time() . '.' . $request->image->extension();

        $key = env("AWS_KEY", "images/");
        $path = Storage::disk('s3')->put($key, $request->image);
        $path = Storage::disk('s3')->url($path);

        /* Store $imageName name in DATABASE from HERE */

        return response()->json([
            "success" => true,
            "message" => "You have successfully upload image.",
            "data" => $path
        ]);
    }
}
