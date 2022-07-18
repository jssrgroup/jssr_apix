<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::all();
        // return response()->json([
        //     "success" => true,
        //     "message" => "Product List",
        //     "data" => $customer
        // ]);
        return new CustomerResource($customer);
    }

    /**
     * store
     *
     * @param  Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|min:3|max:32',
                'password' => 'required',
                'role' => 'required',
            ],
            [
                'username.required' => 'Username is required',
                'password.required' => 'Password is required'
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
            if (array_key_exists($value->code, $request->all()) && $value->necessary)
                $data[$value->code] = $request[$value->code];
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
            "message" => "Product created successfully.",
            "data" => $customer
        ]);
    }


    /**
     * show
     *
     * @param  int $id
     * @return void
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
     * update
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return void
     */
    public function update(Request $request,  $id)
    {
        $requestData = $request->all();
        $requestData['id'] = $id;
        $validator = Validator::make($requestData, [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();

        $data = [];
        foreach ($personals as $value) {
            if (array_key_exists($value->code, $requestData))
                $data[$value->code] = $value->name;
        }
        $json = json_encode($data);
        $encrypt = jencrypt($json);

        $res = Customer::where('id', $requestData['id'])
            ->update(['data' => $encrypt]);

        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $res
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
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
}