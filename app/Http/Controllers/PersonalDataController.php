<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
use App;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersonalDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $personals = PersonalData::all();
        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();

        // $data = [];
        foreach ($personals as $pdata) {
            // $pdata['value'] = '';
            $pdata->value = '';
        }
        return response()->json([
            "success" => true,
            "message" => "Personaldata have data.",
            "role" => 0,
            "data" => $personals
        ]);
        // return $personals;
    }

    public function getEditbyId($id)
    {
        // $personals = PersonalData::all();
        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();
        $customer = Customer::find($id);

        $data = json_decode(jdecrypt($customer['data']), true);
        $dataaccept = json_decode(jdecrypt($customer['dataaccept']), true);
        // $data = [];
        // foreach ($customer['data']['personal_data'] as $cus) {
        foreach ($personals as $pdata) {
            // $key = $pdata['code'];
            $key = $pdata->code;
            // $pdata['value'] = (isset($data[$key])) ? $data[$key] : '';
            // $pdata['necessary'] = isset($dataaccept[$key]) ? ($dataaccept[$key] ? 1 : 0) : 0;
            $pdata->value = (isset($data[$key])) ? $data[$key] : '';
            $pdata->necessary = isset($dataaccept[$key]) ? ($dataaccept[$key] ? 1 : 0) : 0;
            // $pdata['value'] = $key;

            // foreach ($dataaccept as $datax) {
            //     $pdata['necessary'] = (isset($datax[$key])) ? true : false;
            //     // $pdata['necessary'] = 999;
            // }
        }
        // }
        return response()->json([
            "success" => true,
            "message" => "Personaldata have data.",
            "role" => $customer['role'],
            "data" => $personals
        ]);
        // return $personals;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $requestData['code'] = strtolower($requestData['code']);
        $validator = Validator::make($requestData, [
            'code' => 'required|min:3|max:16|unique:personal_data|regex:/^[a-z]+[a-z0-9\s]+$/',
            // 'name' => 'in:DEFAULT,SOCIAL',
            'name' => 'required',
            'necessary' => 'boolean',
            'order_by' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // return response()->json($validator->validated());


        $personalData = PersonalData::create($validator->validated());
        return response()->json([
            "success" => true,
            "message" => "Personal data created successfully.",
            "data" => $personalData
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personals = PersonalData::all();
        $personal = $personals->find($id);
        // $personals = DB::table('personal_data')
        //     ->orderBy('order_by', 'asc')
        //     ->get();
        // $customer = Customer::find($id);

        return response()->json([
            "success" => true,
            "message" => "Personaldata have data.",
            "data" => $personal
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = PersonalData::destroy($id);

        return response()->json([
            "success" => true,
            "message" => "Delete data successfully.",
            "data" => $data
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderBy()
    {
        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();

        $data = [];
        foreach ($personals as $value) {
            $data[$value->code] = $value->order_by;
        }
        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateOrderBy(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            '*' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = [];
        foreach ($input as $key => $value) {
            DB::table('personal_data')
                ->where('code', $key)
                ->update(array('order_by' => $value));
        }
        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();

        $data = [];
        foreach ($personals as $value) {
            $data[$value->code] = $value->order_by;
        }
        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function encrypt(Request $request)
    {
        return response()->json(jencrypt($request['text']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function decrypt(Request $request)
    {
        $decrypt = json_decode(jdecrypt($request['token'], true));
        return response()->json($decrypt);
    }

    /**
     * getAcceptConsent
     *
     * @return Json
     */
    public function getAcceptConsent()
    {
        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();

        $data = [];
        foreach ($personals as $value) {
            $data[$value->code] = ($value->necessary) ? true : false;
        }
        return response()->json($data);
    }

    public function updateAcceptConsent(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            '*' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = [];
        foreach ($input as $key => $value) {
            DB::table('personal_data')
                ->where('code', $key)
                ->update(array('necessary' => $value));
        }
        $personals = DB::table('personal_data')
            ->orderBy('order_by', 'asc')
            ->get();

        $data = [];
        foreach ($personals as $value) {
            $data[$value->code] = ($value->necessary) ? true : false;
        }
        return response()->json($data);
    }
}
