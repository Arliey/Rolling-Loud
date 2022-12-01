<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\Admin;

class TransactionController extends Controller
{
    /**
     * Validation Admin
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
        $transaction = Transaction::get();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Transaksi',
            'data' => $transaction
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = Admin::where('id', $request->id_ticket)->first()->stock;
        $user = Auth()->user();
        $admin = Admin::find($request->id_ticket);

        $transaction = validator::make($request->all(), [
            'id_ticket' => 'required',
            'nama_pembeli' => 'required',
            'jumlah_ticket' => 'required',
        ]);

        if ($transaction->fails()){
            return response()->json([
                'success' => false,
                'CODE' => 400,
                'message' => 'Gagal Menambahkan Data',
                'data' => $transaction->errors()
            ], 400);
        } 
        
        if ($check < $request->jumlah_ticket){
            return response()->json([
                'success' => false,
                'CODE' => 400,
                'message' => 'Stock Tidak Mencukupi',
                'data' => $transaction->errors()
            ], 400);
        }

        $transaction = Transaction::create([
            'id_ticket' => $request->id_ticket,
            'nama_pembeli' => $request->nama_pembeli,
            'jumlah_ticket' => $request->jumlah_ticket,
            'total' => $request->jumlah_ticket * $admin->price,
            'id_user' => $user->id
        ]);

        $admin->stock = $admin->stock - $request->jumlah_ticket;
        $admin->save();


            if ($transaction) {
                return response()->json([
                    'success' => true,
                    'CODE' => 200,
                    'message' => 'Berhasil Menambahkan Data',
                    'data' => $transaction
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'CODE' => 400,
                    'message' => 'Gagal Menambahkan Data',
                    'data' => $transaction->errors()
                ], 400);
            }
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Transaksi',
            'data' => $transaction
        ], 200);
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
        $transaction = Transaction::where('id', $id)->update([
            'status_pembayaran' => $request->status_pembayaran,
            'tanggal_bayar' => now(),
        ]);

        if ($transaction){
            return response()->json([
                'success' => true,
                'CODE' => 200,
                'message' => 'Berhasil Mengubah Data',
                'data' => $transaction
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'CODE' => 400,
                'message' => 'Gagal Mengubah Data',
                'data' => $transaction
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menghapus Data',
            'data' => $transaction
        ], 200);
    }
    
}
