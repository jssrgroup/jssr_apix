<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index()
    {
        $departments = Document::all();
        return response()->json([
            'message' => 'Department List',
            'data' => DocumentResource::collection($departments)
        ], 200);
    }

    public function getAllByDep($depId)
    {
        $departments = Document::where('ref_dep_id', $depId)->get();
        return response()->json([
            'message' => 'Department List',
            'data' => DocumentResource::collection($departments)
        ], 200);
    }
    
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
        $key = env("AWS_KEY", "images");
        $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($key . '/document/' . $validator->validated()['name'], now()->addMinutes(1));


        return response()->json([
            "success" => true,
            "message" => "You have image.",
            "url" => $temporarySignedUrl
        ]);
    }

    public function imageUploadPost(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            // 'attachment' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'refId' => 'required|string',
            'depId' => 'required|string',
            'imageName' => 'required|string',
            'fileName' => 'required',
            'expireDate' => 'required|string',
            'docType' => 'required|string',
            'userId' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $imageName = time() . '.' . $request->image->extension();

        $key = env("AWS_KEY", "images");
        $path = Storage::disk('s3')->put($key . '/document', $request->fileName);
        $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(1));
        // $url = Storage::disk('s3')->url($path);


        /* Store $imageName name in DATABASE from HERE */
        Document::create([
            'ref_id' => $request->refId,
            'ref_dep_id' => $request->depId,
            'image_name' => $request->imageName,
            'file_name' => explode('/', $path)[2],
            'expire_date_at' => $request->expireDate,
            'ref_doc_type_id' => $request->docType,
            'ref_user_id' => $request->userId,
            // 'expire_date_at' => Carbon::createFromFormat('Y-m-d', $request->expireDate)->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            "success" => true,
            "message" => "You have successfully upload image.",
            // "data" => $validator->validated()['filename'],
            // "url" => $url,
            // "path" => $path,
            "data" => $url,
        ]);
    }

    public function deleteFile(Request $request, $id)
    {
        $requestData = $request->all();
        $requestData['id'] = $id;
        $validator = Validator::make($requestData, [
            'id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Document::find($id);

        if ($validator->fails()) {
            return response()->json("ไม่พบข้อมูล", 400);
        }

        $bucket = env("AWS_BUCKET", "apix.jssr.co.th");
        $key = env("AWS_KEY", "images");
        $region = env("AWS_DEFAULT_REGION", "ap-southeast-1");
        $keyname = $key . '/document/' . $customer['file_name'];

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => $region
        ]);

        try {
            $result = $s3->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $keyname
            ]);
            $message = "Delete Object";
            $customer->delete();
        } catch (S3Exception $e) {
            $message = $e->getAwsErrorMessage();
        }

        return response()->json([
            "success" => true,
            "message" => $message,
            "bucket" => $bucket,
            "key" => $key,
            "keyname" => $keyname,
            "result" => $result,
        ]);
    }
}
