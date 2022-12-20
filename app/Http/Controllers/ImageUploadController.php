<?php

namespace App\Http\Controllers;

use App\Models\ImageAws;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageUploadController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/image-upload",
     * summary="Get File Upload By Id",
     * description="Get file upload by id.",
     * operationId="imageUploadGet",
     * tags={"AWS"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *   @OA\Response(
     *      response=200,
     *       description="Successfully",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $attachments = ImageAws::where('cus_id', $validator->validated()['id'])->get();
        return $attachments;
        // return $attachments->where('cus_id',$validator->validated()['id']);
    }
    /**
     * @OA\Get(
     * path="/api/image-upload-all",
     * summary="Get File Upload All",
     * description="Get file upload all.",
     * operationId="imageUploadAll",
     * tags={"AWS"},
     *   @OA\Response(
     *      response=200,
     *       description="Successfully",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function getAll(Request $request)
    {
        // $requestData = $request->all();
        // $validator = Validator::make($requestData, [
        //     'id' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }
        $attachments = ImageAws::all();
        return $attachments;
        // return $attachments->where('cus_id',$validator->validated()['id']);
    }
    /**
     * @OA\Post(
     * path="/api/image-upload",
     * summary="Upload File with cus id",
     * description="Upload file with customer id",
     * operationId="imageUpload",
     * tags={"AWS"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"attachment", "filename", "expireDate", "cusId"},
     *               @OA\Property(property="attachment", type="file"),
     *               @OA\Property(property="filename", type="text"),
     *               @OA\Property(property="expireDate", type="text"),
     *               @OA\Property(property="cusId", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Insert Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
        $key = env("AWS_KEY", "images");
        $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($key . '/' . $validator->validated()['name'], now()->addMinutes(1));


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
        // return response()->json([
        //     "success" => true,
        //     "message" => "[Test]You have successfully upload image.",
        //     // "data" => $validator->validated()['filename'],
        //     // "url" => $url,
        //     // "path" => $path,
        //     "data" => $request->all(),
        // ]);
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'attachment' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'filename' => 'required|string',
            'expireDate' => 'required|string',
            'cusId' => 'required|string',
        ]);
        if ($validator->fails()) {
            // return response()->json($validator->errors(), 422);
        }

        // $imageName = time() . '.' . $request->image->extension();

        $key = env("AWS_KEY", "images");
        $path = Storage::disk('s3')->put($key, $request->attachment);
        $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(1));
        // $url = Storage::disk('s3')->url($path);


        /* Store $imageName name in DATABASE from HERE */
        ImageAws::create([
            'image_name' => $request->filename,
            'cus_id' => $request->cusId,
            'file_name' => explode('/', $path)[1],
            'expire_date_at' => $request->expireDate,
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

        // $imageName = time() . '.' . $request->image->extension();
        $customer = ImageAws::find($id);

        $bucket = env("AWS_BUCKET", "apix.jssr.co.th");
        $key = env("AWS_KEY", "images");
        $region = env("AWS_DEFAULT_REGION", "ap-southeast-1");
        $key = env("AWS_KEY", "images");
        // $validator->validated()['filename'];
        $keyname = $key . '/' . $customer['file_name'];


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


        /* Store $imageName name in DATABASE from HERE */
        // ImageAws::create([
        //     'image_name' => $request->filename,
        //     'cus_id' => $request->cusId,
        //     'file_name' => explode('/', $path)[1],
        //     'expire_date_at' => $request->expireDate,
        //     // 'expire_date_at' => Carbon::createFromFormat('Y-m-d', $request->expireDate)->format('Y-m-d H:i:s'),
        // ]);

        return response()->json([
            "success" => true,
            "message" => $message,
            // // "data" => $validator->validated()['filename'],
            "bucket" => $bucket,
            "key" => $key,
            "keyname" => $keyname,
            "result" => $result,
            // "customer" => $customer['file_name'],
        ]);
    }
}
