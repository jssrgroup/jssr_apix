<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Http\Resources\DocumentTypeResource;
use App\Models\Customer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docTypes = DocumentType::all();
        return response()->json([
            'message' => 'Document Type List' . trans('validation.required'),
            'data' => DocumentTypeResource::collection($docTypes)
        ], 200);
    }

    public function getDocTypeByDep($depId)
    {
        $docTypes = DocumentType::where('dep_id', $depId)->get();;
        return response()->json([
            'message' => 'Document Type List',
            'data' => DocumentTypeResource::collection($docTypes)
        ], 200);
    }

    public function getDocTypeByDepNotDoc($depId)
    {
        $docTypes = DocumentType::where('dep_id', $depId)->where('parent', null)->get();
        return response()->json([
            'message' => 'Document Type List',
            'data' => DocumentTypeResource::collection($docTypes)
        ], 200);
    }

    public function getDocTypeByDepAndDoc($depId, $docId)
    {
        $docTypes = DocumentType::where('dep_id', $depId)->where('parent', $docId)->get();;
        return response()->json([
            'message' => 'Document Type List',
            'data' => DocumentTypeResource::collection($docTypes)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentTypeRequest $request)
    {
        // $validated = $request->validated();
        // $validated = $request->safe()->only(['dep_id']);
        // $validated = $request->safe()->except(['dep_id', 'desc']);
        $validated = $request->safe()->all();
        // return response()->json([
        //     "success" => true,
        //     "message" => "Created successfully." . trans('validation.required'),
        //     "locale" => App::currentLocale(),
        //     "data" => $validated
        // ]);
        // exit;
        $docType = $validated;

        $docType = DocumentType::create($validated);

        return response()->json([
            "success" => true,
            "message" => "Document Type created successfully.",
            "data" => $docType
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentType $documentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentType $documentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDocumentTypeRequest  $request
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentTypeRequest $request, $id)
    {
        $validated = $request->safe()->all();

        // $docType = $validated;
        $input = array_filter($validated, function ($value) {
            return $value !== null;
        });

        $docType = DocumentType::find($id);
        // // $docType = DocumentType::create($validated);
        $docType->update($input);


        return response()->json([
            "success" => true,
            "message" => "Document Type updated successfully.",
            "data" => $docType
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DocumentType::destroy($id);

        return response()->json([
            "success" => true,
            "message" => "Document Type delete successfully.",
        ]);
    }
}
