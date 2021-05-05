<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductType;


class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = ProductType::orderBy('id','asc')->get();
        return response()->json($type);
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
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "code_name" => "required",
            "satuan" => "required",
            "code_satuan" => "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $type = new ProductType;
        $type->name = $request->name;
        $type->code_name = $request->code_name;
        $type->satuan_hitung = $request->satuan;
        $type->code_satuan_hitung = $request->code_satuan;
        $type->save();

        if(!is_null($type)) {
            return response()->json(["status" => 201, "message" => "Tipe Produk berhasil didaftarkan !", "data" => $type]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
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
        $type = ProductType::findOrFail($id);
        return response()->json($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $typeData = ProductType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "code_name" => "required",
            "satuan" => "required",
            "code_satuan" => "required",
        ]);

        $typeData->name = $request->name;
        $typeData->code_name = $request->code_name;
        $typeData->satuan_hitung = $request->satuan;
        $typeData->code_satuan_hitung = $request->code_satuan;
        $typeData->save();

        if(!is_null($typeData)) {
            return response()->json(["status" => 201, "message" => "Tipe Produk berhasil diubah !", "data" => $typeData]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
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
        $type = ProductType::findOrFail($id);
        $type->delete();
        return response()->json(["message" => "Berhasil"]);
    }
}
