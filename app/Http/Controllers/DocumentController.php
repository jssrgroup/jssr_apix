<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentReportResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\UserAdminResource;
use App\Models\Document;
use App\Models\Log;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index()
    {
        $departments = Document::all();
        return response()->json([
            'message' => 'Document List',
            'data' => DocumentResource::collection($departments)
        ], 200);
    }

    public function getAllByDep($depId)
    {
        $documents = Document::where('ref_dep_id', $depId)->get();
        return response()->json([
            'message' => 'Document List',
            'data' => DocumentResource::collection($documents)
        ], 200);
    }

    public function docDashboard($depId, $userId)
    {
        $query = "SELECT 'เอกสารทั้งหมด' `desc`, COUNT(*) count
        FROM documents
        WHERE ref_dep_id = ?
        UNION
        SELECT 'เอกสารของฉัน' `desc`, COUNT(*) count
        FROM documents
        WHERE ref_dep_id = ?
        AND ref_user_id = ?
        UNION
        SELECT 'เอกสารหมดอายุใน 7 วัน' `desc`, COUNT(*) count
        FROM documents
        WHERE ref_dep_id = ?
        AND DATEDIFF(expire_date_at, NOW())  BETWEEN 0 AND 6
        UNION
        SELECT 'เอกสารหมดอายุแล้ว' `desc`, COUNT(*) count
        FROM documents
        WHERE ref_dep_id = ?
        AND DATEDIFF(expire_date_at, NOW()) < 0";

        // $documents = DB::select(DB::raw($query), ['depId' => $depId, 'userId' => $userId]);
        $documents = DB::select(DB::raw($query), [$depId, $depId, $userId, $depId, $depId]);

        return response()->json([
            'message' => 'Document Dashboard Data',
            'data' => $documents,
            'depId' => $depId,
            'userId' => $userId,
        ], 200);
    }

    public function getAllExpired()
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date FROM (
            SELECT
            documents.id, ref_id, ref_doc_id doc_id, doc.desc doc_desc, type.id doc_type_id, type.desc type_desc, ref_user_id, ref_dep_id,departments.`desc` dep_desc, image_name, file_name,
            DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
            DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date
            FROM `documents`
            LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
            LEFT JOIN document_types type ON doc.parent = type.id
            LEFT JOIN departments ON documents.ref_dep_id = departments.id
            WHERE is_delete = 1
        ) t";

        $documents = DB::select(DB::raw($query));


        return response()->json([
            'message' => 'Document Remove List',
            'data' => $documents
        ], 200);
    }

    public function getAllExpiredByDep($depId)
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date FROM (
            SELECT
            documents.id, ref_id, ref_doc_id doc_id, doc.desc doc_desc, type.id doc_type_id, type.desc type_desc, ref_user_id, ref_dep_id,departments.`desc` dep_desc, image_name, file_name,
            DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
            DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date
            FROM `documents`
            LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
            LEFT JOIN document_types type ON doc.parent = type.id
            LEFT JOIN departments ON documents.ref_dep_id = departments.id
            WHERE is_delete = 1
        ) t
        WHERE ref_dep_id = ?";

        $documents = DB::select(DB::raw($query), [$depId]);


        return response()->json([
            'message' => 'Document Remove List',
            'data' => $documents
        ], 200);
    }

    public function getAllExpire()
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date,
        DATEDIFF(STR_TO_DATE(document_expire,'%d/%m/%Y'), NOW()) doc_remain_date,
        DATEDIFF(STR_TO_DATE(doctype_expire,'%d/%m/%Y'), NOW()) type_remain_date FROM (
        SELECT
        documents.id, ref_id, ref_doc_id doc_id, doc.parent doc_type_id, ref_user_id, ref_dep_id,departments.`desc` dep_desc, image_name, file_name,
        DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
        DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date,
        DATE_FORMAT(
        CASE
            WHEN doc.expire_type = 'DAY' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire DAY)
            WHEN doc.expire_type = 'MONTH' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire MONTH)
            WHEN doc.expire_type = 'YEAR' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire YEAR)
            ELSE NULL
          END
        ,'%d/%m/%Y') document_expire,
        CONCAT(doc.expire,' ',doc.expire_type) document_expire_type,
        DATE_FORMAT(
        CASE
            WHEN type.expire_type = 'DAY' THEN DATE_ADD(documents.created_at, INTERVAL type.expire DAY)
            WHEN type.expire_type = 'MONTH' THEN DATE_ADD(documents.created_at, INTERVAL type.expire MONTH)
            WHEN type.expire_type = 'YEAR' THEN DATE_ADD(documents.created_at, INTERVAL type.expire YEAR)
            ELSE NULL
          END
        ,'%d/%m/%Y') doctype_expire,
        CONCAT(type.expire,' ',type.expire_type) doctype_expire_type
        FROM `documents`
        LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
        LEFT JOIN document_types type ON doc.parent = type.id
        LEFT JOIN departments ON documents.ref_dep_id = departments.id
        ) t";

        $documents = DB::select(DB::raw($query));


        return response()->json([
            'message' => 'Document List',
            'data' => $documents
        ], 200);
    }

    public function getAllExpireByDepId($depId)
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date,
        DATEDIFF(STR_TO_DATE(document_expire,'%d/%m/%Y'), NOW()) doc_remain_date,
        DATEDIFF(STR_TO_DATE(doctype_expire,'%d/%m/%Y'), NOW()) type_remain_date FROM (
        SELECT
        documents.id, ref_id, ref_doc_id doc_id, doc.parent doc_type_id, ref_user_id, ref_dep_id, departments.`desc` dep_desc, image_name, file_name,
        DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
        DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date,
        DATE_FORMAT(
        CASE
            WHEN doc.expire_type = 'DAY' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire DAY)
            WHEN doc.expire_type = 'MONTH' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire MONTH)
            WHEN doc.expire_type = 'YEAR' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire YEAR)
            ELSE NULL
          END
        ,'%d/%m/%Y') document_expire,
        CONCAT(doc.expire,' ',doc.expire_type) document_expire_type,
        DATE_FORMAT(
        CASE
            WHEN type.expire_type = 'DAY' THEN DATE_ADD(documents.created_at, INTERVAL type.expire DAY)
            WHEN type.expire_type = 'MONTH' THEN DATE_ADD(documents.created_at, INTERVAL type.expire MONTH)
            WHEN type.expire_type = 'YEAR' THEN DATE_ADD(documents.created_at, INTERVAL type.expire YEAR)
            ELSE NULL
          END
        ,'%d/%m/%Y') doctype_expire,
        CONCAT(type.expire,' ',type.expire_type) doctype_expire_type
        FROM `documents`
        LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
        LEFT JOIN document_types type ON doc.parent = type.id
        LEFT JOIN departments ON documents.ref_dep_id = departments.id
        WHERE is_delete = 0
        ) t
        WHERE ref_dep_id = ?";

        $results = DB::select(DB::raw($query), [$depId]);

        // Convert the array to a collection
        // $resultsCollection = new Collection($results);

        // Filter the collection based on a condition
        // $documents = $resultsCollection->filter(function ($result) {
        //     return $result->doc_id = 10; // Example condition
        // });


        // Filter the collection based on a condition using the variable
        // $documents = $resultsCollection->filter(function ($result) use ($depId) {
        //     return $result->ref_dep_id == $depId;
        // });
        // return $documents;
        // exit;

        return response()->json([
            'message' => 'Document Dep List',
            'data' => DocumentReportResource::collection($results)
            // 'data' => $results
        ], 200);
    }

    public function getAllExpiringByDepId($depId)
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date
        FROM (
            SELECT
            documents.id, ref_id, ref_doc_id doc_id, doc.parent doc_type_id, ref_user_id, ref_dep_id, departments.`desc` dep_desc, image_name, file_name,
            DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
            DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date
            FROM `documents`
            LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
            LEFT JOIN document_types type ON doc.parent = type.id
            LEFT JOIN departments ON documents.ref_dep_id = departments.id
        -- WHERE is_delete = 0
        ) t
        WHERE ref_dep_id = ?
        AND DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) BETWEEN 0 AND 6";

        $results = DB::select(DB::raw($query), [$depId]);

        return response()->json([
            'message' => 'Document Dep List',
            // 'data' => DocumentReportResource::collection($results)
            'data' => $results
        ], 200);
    }

    public function getAllExpiredByDepId($depId)
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date
        FROM (
            SELECT
            documents.id, ref_id, ref_doc_id doc_id, doc.parent doc_type_id, ref_user_id, ref_dep_id, departments.`desc` dep_desc, image_name, file_name,
            DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
            DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date
            FROM `documents`
            LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
            LEFT JOIN document_types type ON doc.parent = type.id
            LEFT JOIN departments ON documents.ref_dep_id = departments.id
        -- WHERE is_delete = 0
        ) t
        WHERE ref_dep_id = ?
        AND DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) < 0";

        $results = DB::select(DB::raw($query), [$depId]);

        return response()->json([
            'message' => 'Document Dep List',
            // 'data' => DocumentReportResource::collection($results)
            'data' => $results
        ], 200);
    }

    public function getAllByDepAndUser($depId, $userId)
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date
        FROM (
            SELECT
            documents.id, ref_id, ref_doc_id doc_id, doc.parent doc_type_id, ref_user_id, ref_dep_id, departments.`desc` dep_desc, image_name, file_name,
            DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
            DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date
            FROM `documents`
            LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
            LEFT JOIN document_types type ON doc.parent = type.id
            LEFT JOIN departments ON documents.ref_dep_id = departments.id
        ) t
        WHERE ref_dep_id = ?
        AND ref_user_id = ?";

        $results = DB::select(DB::raw($query), [$depId, $userId]);

        return response()->json([
            'message' => 'Document Dep List',
            // 'data' => DocumentReportResource::collection($results)
            'data' => $results
        ], 200);
    }

    public function getAllByDepId($depId)
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date
        FROM (
            SELECT
            documents.id, ref_id, ref_doc_id doc_id, doc.parent doc_type_id, ref_user_id, ref_dep_id, departments.`desc` dep_desc, image_name, file_name,
            DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
            DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date
            FROM `documents`
            LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
            LEFT JOIN document_types type ON doc.parent = type.id
            LEFT JOIN departments ON documents.ref_dep_id = departments.id
        ) t
        WHERE ref_dep_id = ?";

        $results = DB::select(DB::raw($query), [$depId]);

        return response()->json([
            'message' => 'Document Dep List',
            // 'data' => DocumentReportResource::collection($results)
            'data' => $results
        ], 200);
    }

    public function getAllExpireById($id)
    {
        $query = "SELECT *, DATEDIFF(STR_TO_DATE(expire_doc_date,'%d/%m/%Y'), NOW()) remain_date,
        DATEDIFF(STR_TO_DATE(document_expire,'%d/%m/%Y'), NOW()) doc_remain_date,
        DATEDIFF(STR_TO_DATE(doctype_expire,'%d/%m/%Y'), NOW()) type_remain_date FROM (
        SELECT
        documents.id, ref_id, ref_doc_id doc_id, doc.parent doc_type_id, ref_user_id, ref_dep_id, departments.`desc` dep_desc, image_name, file_name,
        DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
        DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date,
        DATE_FORMAT(
        CASE
            WHEN doc.expire_type = 'DAY' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire DAY)
            WHEN doc.expire_type = 'MONTH' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire MONTH)
            WHEN doc.expire_type = 'YEAR' THEN DATE_ADD(documents.created_at, INTERVAL doc.expire YEAR)
            ELSE NULL
          END
        ,'%d/%m/%Y') document_expire,
        CONCAT(doc.expire,' ',doc.expire_type) document_expire_type,
        DATE_FORMAT(
        CASE
            WHEN type.expire_type = 'DAY' THEN DATE_ADD(documents.created_at, INTERVAL type.expire DAY)
            WHEN type.expire_type = 'MONTH' THEN DATE_ADD(documents.created_at, INTERVAL type.expire MONTH)
            WHEN type.expire_type = 'YEAR' THEN DATE_ADD(documents.created_at, INTERVAL type.expire YEAR)
            ELSE NULL
          END
        ,'%d/%m/%Y') doctype_expire,
        CONCAT(type.expire,' ',type.expire_type) doctype_expire_type
        FROM `documents`
        LEFT JOIN document_types doc ON documents.ref_doc_id = doc.id
        LEFT JOIN document_types type ON doc.parent = type.id
        LEFT JOIN departments ON documents.ref_dep_id = departments.id
        ) t
        WHERE id = ?";

        $results = DB::select(DB::raw($query), [$id]);

        // Convert the array to a collection
        // $resultsCollection = new Collection($results);

        // Filter the collection based on a condition
        // $documents = $resultsCollection->filter(function ($result) {
        //     return $result->id == 1; // Example condition
        // });

        // Filter the collection based on a condition using the variable
        // $documents = $resultsCollection->filter(function ($result) use ($id) {
        //     return $result->id == $id;
        // });

        return response()->json([
            'message' => 'Document Data',
            'data' => ($results) ? $results[0] : null
        ], 200);
    }

    public function imageUpload($name, Request $request)
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

        $user = new UserAdminResource(auth('useradmins')->user());

        $log = [
            "class" => __CLASS__,
            "method" => __METHOD__,
            "behavior" => "get attachment from filename",
            "user_id" => $user['INDX'],
            "doc_id" => $request->docId,
        ];

        Log::create($log);


        return response()->json([
            "success" => true,
            "message" => "You have image.",
            "url" => $temporarySignedUrl,
            // "user" => $log,
            // "req" => $request->all(),
        ]);
    }

    public function imageUploadPost(Request $request)
    {
        // return response()->json([
        //     "success" => true,
        //     "message" => "You have successfully upload image.",
        //     // "data" => $validator->validated()['filename'],
        //     // "url" => $url,
        //     // "path" => $path,
        //     "data" => $request->all(),
        // ]);
        // exit;
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
            'docId' => 'required|string',
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
            'ref_doc_id' => $request->docId,
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

    // For Cron job
    public function getAllExpireForDel()
    {
        $query = "SELECT * FROM (
            SELECT
            documents.id, ref_id, ref_doc_id doc_id, ref_user_id, ref_dep_id, image_name, file_name,
            DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
            DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date,
            DATEDIFF(expire_date_at, NOW()) remain_date
            FROM `documents`
	        WHERE is_delete = 0
        )t
        WHERE remain_date < 0";

        $documents = DB::select(DB::raw($query));

        return $documents;
    }

    // For Cron job
    public function deleteFlag($id)
    {
        $document = Document::find($id);

        $bucket = env("AWS_BUCKET", "apix.jssr.co.th");
        $key = env("AWS_KEY", "images");
        $region = env("AWS_DEFAULT_REGION", "ap-southeast-1");
        $keyname = $key . '/document/' . $document['file_name'];

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
            // $document->delete();
            $document->update([
                'is_delete' => 1,
                'deleted_by' => -99,
                'deleted_at' => now(),
            ]);
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
