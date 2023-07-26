<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\ImageAws;
use App\Models\ImageCusAws;
use App\Models\PersonalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/customer",
     * summary="Get All Customer",
     * description="Get all customer data",
     * operationId="customerGetAll",
     * tags={"Customer"},
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
    public function index()
    {
        $customers = Customer::all();
        // return response()->json([
        //     "success" => true,
        //     "message" => "Product List",
        //     "data" => $customer
        // ]);
        // return new CustomerResource($customers);
        return $customers;
    }
    /**
     * @OA\Post(
     * path="/api/customer",
     * summary="Create Customer",
     * description="Create customer",
     * operationId="customerCreate",
     * tags={"Customer"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *            @OA\Schema(
     *               type="object",
     *               required={"username", "password"},
     *               @OA\Property(property="username", type="text"),
     *               @OA\Property(property="password", type="text"),
     *               @OA\Property(property="expireDate", type="text"),
     *               @OA\Property(property="cusId", type="text")
     *            ),
     *    ),
     *   @OA\Response(
     *      response=201,
     *       description="Successfully",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(Request $request)
    {
        // return response()->json([
        //     "success" => true,
        //     "message" => "Test.",
        //     "data" => json_decode($request->getContent(), true)
        // ]);
        // $requestData = $request->all();
        $req = json_decode($request->getContent(), true);
        $requestData = $req;
        $validator = Validator::make(
            $requestData,
            [
                'username' => 'required|min:3|max:32',
                'password' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'role' => 'required',
            ],
            [
                'username.required' => 'Username is required',
                'password.required' => 'Password is required',
                'email.required' => 'Email is required',
                'mobile.required' => 'Mobile is required',
                'role.required' => 'Role is required',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();

        $data = [];
        $dataaccept = [];
        foreach ($personals as $value) {
            $dataaccept[$value->code] = $value->necessary ? true : false;
            if (array_key_exists($value->code, $requestData) && $value->necessary)
                $data[$value->code] = $requestData[$value->code];
        }
        $json = json_encode($data);
        $jsonaccept = json_encode($dataaccept);
        $encrypt = jencrypt($json);
        $encryptaccept = jencrypt($jsonaccept);

        $input = $validator->validated();
        $input['data'] = $encrypt;
        $input['dataaccept'] = $encryptaccept;

        $customer = Customer::create($input);

        return response()->json([
            "success" => true,
            "message" => "Customer created successfully.",
            "data" => $customer
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/customer/{id}",
     * summary="Get Customer",
     * description="Get customer data",
     * operationId="customerGet",
     * tags={"Customer"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer id",
     *          required=true,
     *          in="path",
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
    public function show($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            return response()->json([
                "success" => true,
                "data" => $customer,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Customer not found.",
            ], 404);
        }
    }

    /**
     * @OA\Put(
     * path="/api/customer/{id}",
     * summary="Update Customer",
     * description="Update customer",
     * operationId="customerUpdate",
     * tags={"Customer"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *            @OA\Schema(
     *               type="object",
     *            ),
     *    ),
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
    public function update(Request $request,  $id)
    {
        // return response()->json([
        //     "success" => true,
        //     "message" => "Test.",
        //     "data" => json_encode($request->all()) //json_decode($request->getContent(), true)
        // ]);
        // $requestData = $request->all();
        $req = json_decode($request->getContent(), true);
        $requestData = $req;
        $requestData['id'] = $id;
        $validator = Validator::make(
            $requestData,
            [
                'id' => 'required',
                'username' => 'required|min:3|max:32',
                'password' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'role' => 'required',
            ],
            [
                'id.required' => 'Id is required',
                'username.required' => 'Username is required',
                'password.required' => 'Password is required',
                'email.required' => 'Email is required',
                'mobile.required' => 'Mobile is required',
                'role.required' => 'Role is required',
            ]
        );
        //   return response()->json([
        //             "success" => true,
        //             "message" => "Test.",
        //             "data" => $req
        //         ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $personals =  PersonalData::all();
        $customer = Customer::find($id);

        $data = json_decode(jdecrypt($customer['data']), true);
        $dataaccept = json_decode(jdecrypt($customer['dataaccept']), true);
        foreach ($personals as $pdata) {
            $key = $pdata['code'];
            $pdata['value'] = (isset($data[$key])) ? $data[$key] : '';
            $pdata['necessary'] = isset($dataaccept[$key]) ? ($dataaccept[$key] ? 1 : 0) : 0;
        }
        // return response()->json(array($data, $dataaccept, $personals));
        // $ndata = array();
        // foreach ($request->all() as $key => $value) {
        //     array_push($ndata, array('code' => $key, 'value' => $value));
        // }
        // return $ndata;

        // $personals = DB::table('personal_data')
        //     ->orderBy('order_by', 'asc')
        //     ->get();

        $data = [];
        // $dataaccept = [];
        // foreach ($personals as $value) {
        //     if (array_key_exists($value->code, $requestData))
        //         $data[$value->code] = $value->name;
        // }
        foreach ($personals as $value) {
            // $dataaccept[$value->code] = $value->necessary ? true : false;
            if (array_key_exists($value->code, $req) && $value->necessary)
                // if (array_key_exists($value->code, $request->all()))
                $data[$value->code] = $req[$value->code];
        }

        //   return response()->json([
        //     "success" => true,
        //     "message" => "Test.",
        //     "data" => $data
        // ]);

        // foreach ($request->all() as $key => $value) {
        //     array_push($ndata, array('code' => $key, 'value' => $value));
        // }
        $json = json_encode($data);
        $encrypt = jencrypt($json);

        Customer::where('id', $requestData['id'])
            ->update(['email' => $req['email'], 'mobile' => $req['mobile'], 'data' => $encrypt, 'role' => $req['role']]);

        return response()->json([
            "success" => true,
            "message" => "Customer updated successfully.",
            "data" => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            Customer::destroy($id);
        }

        return response()->json([
            "success" => isset($customer),
            "message" => "Customer deleted.",
            "data" => $customer
        ]);
    }

    public function acceptConsent(Request $request, $id)
    {
        $requestData = $request->all();
        $requestData['id'] = $id;
        $validator = Validator::make($requestData, [
            // 'code' => 'required|min:3|max:16|unique:personal_data|regex:/^[a-z]+[a-z0-9\s]+$/',
            // 'name' => 'in:DEFAULT,SOCIAL',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->select('code', 'necessary')
            ->get();

        $data = [];
        foreach ($personals as $value) {
            if (array_key_exists($value->code, $requestData))
                if ($value->necessary)
                    $data[$value->code] = true;
                else
                    $data[$value->code] = $requestData[$value->code];
        }
        $json = json_encode($data);
        $encrypt = jencrypt($json);

        Customer::where('id', $requestData['id'])
            ->update(['dataaccept' => $encrypt]);


        // $personalData = PersonalData::create($validator->validated());
        return response()->json([
            "success" => true,
            "message" => "Customer update accept consent successfully.",
            "data" => $data
        ]);
    }

    public function updateConsent(Request $request, $id)
    {
        $requestData = $request->all();
        $requestData['id'] = $id;
        $validator = Validator::make($requestData, [
            // 'code' => 'required|min:3|max:16|unique:personal_data|regex:/^[a-z]+[a-z0-9\s]+$/',
            // 'name' => 'in:DEFAULT,SOCIAL',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->select('code', 'necessary')
            ->get();

        $data = [];
        foreach ($personals as $value) {
            if (array_key_exists($value->code, $requestData))
                if ($value->necessary)
                    $data[$value->code] = true;
                else
                    $data[$value->code] = $requestData[$value->code];
        }
        $json = json_encode($data);
        $encrypt = jencrypt($json);

        Customer::where('id', $requestData['id'])
            ->update(['dataaccept' => $encrypt]);


        // $personalData = PersonalData::create($validator->validated());
        return response()->json([
            "success" => true,
            "message" => "Customer update accept consent successfully.",
            "data" => $data
        ]);
    }
    public function attachment($id)
    {
        // $requestData = $request->all();
        $validator = Validator::make(array('id' => $id), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $attachments = ImageAws::where('cus_id', $validator->validated()['id'])->get();
        return $attachments;
        // return $attachments->where('cus_id',$validator->validated()['id']);
    }
    public function attachmentCus($id)
    {
        // $requestData = $request->all();
        $validator = Validator::make(array('id' => $id), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $attachments = ImageCusAws::where('cus_id', $validator->validated()['id'])->get();
        return $attachments;
        // return $attachments->where('cus_id',$validator->validated()['id']);
    }
}
