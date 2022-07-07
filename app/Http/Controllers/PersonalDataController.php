<?php

namespace App\Http\Controllers;

use App\Models\PersonalData;
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
        $personals = PersonalData::all();

        $data = [];
        foreach ($personals as $value) {
            $data[$value['code']] = $value['name'];
        }
        return response()->json($data);
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
        //
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
        //
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
        $data=[];
        foreach ($input as $key=>$value) {
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
}
