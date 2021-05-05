<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductManufacture;

class ProductManufactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manufacture = ProductManufacture::orderBy('code_name','asc')->get();
        return response()->json($manufacture);
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
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $manufacture = new ProductManufacture;
        $manufacture->name = $request->name;
        $manufacture->code_name = $request->code_name;
        $manufacture->save();

        if(!is_null($manufacture)) {
            return response()->json(["status" => 201, "message" => "Manufacture Produk berhasil didaftarkan !", "data" => $manufacture]);
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
        $manufacture = ProductManufacture::findOrFail($id);
        return response()->json($manufacture);
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
    public function update(Request $request, $id){
        $manufactureData = ProductManufacture::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "code_name" => "required",
        ]);

        $manufactureData->name = $request->name;
        $manufactureData->code_name = $request->code_name;
        $manufactureData->save();

        if(!is_null($manufactureData)) {
            return response()->json(["status" => 201, "message" => "Manufacture Produk berhasil diubah !", "data" => $manufactureData]);
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
        $manufacture = ProductManufacture::findOrFail($id);
        $manufacture->delete();
        return response()->json(["message" => "Berhasil"]);
    }
}
