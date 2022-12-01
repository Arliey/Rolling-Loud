<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Admin Validation
     *
     * @return \Illuminate\Http\Response
     */
    public function _construct()
    {
        $this->middleware('admin')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Admin::get();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Ticket',
            'data' => $admin
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = Validator::make($request->all(), [
            'konser_name' => 'required',
            'ticket_category' => 'required',
            'date' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);

        if ($store->fails()){
            return response()->json([
                'success' => false,
                'CODE' => 400,
                'message' => 'Gagal Menambahkan Data',
                'data' => $store->errors()
            ], 400);
        } 

        $validated = $store->validated();

        $admin = Admin::create($validated);

        return response()->json([
            "message" => "Add Data Ticket Success",
            "Code" => 200,
            "data" => $validated
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $table = Admin::find($id);
        
        if ($table) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Ticket',
                'data' => $table
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Ticket Tidak Ditemukan',
                'data' => ''
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $update = Validator::make($request->all(), [
            'konser_name' => 'nullable',
            'ticket_category' => 'nullable',
            'date' => 'nullable',
            'price' => 'nullable',
        ]);

        if ($update->fails()){
            return response()->json([
                'success' => false,
                'CODE' => 400,
                'message' => 'Gagal Mengubah Data',
                'data' => $update->errors()
            ], 400);
        }

        $validated = $update->getData();


        $admin = Admin::find($id);
        $admin->update($validated);

        return response()->json([
            "message" => "Update Data Ticket Success",
            "Code" => 200,
            "data" => $admin
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Admin::find($id);
        if($data){
            $data->delete();
            return["message" => "Delete Ticket Success"];
        }else{
            return["message" => "Data Not Found"];
        }
    }
}
