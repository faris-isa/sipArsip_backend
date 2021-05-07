<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PurchaseLocation;

class PurchaseLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = PurchaseLocation::orderBy('name','asc')->get();
        return response()->json($locations);
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
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $location = new PurchaseLocation;
        $location->name = $request->name;
        $location->save();

        if(!is_null($location)) {
            return response()->json(["status" => 201, "message" => "Manufacture Produk berhasil didaftarkan !", "data" => $location]);
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
        $location = PurchaseLocation::findOrFail($id);
        return response()->json($location);
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
        $locationData = PurchaseLocation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "code_name" => "required",
        ]);

        $locationData->name = $request->name;
        $locationData->save();

        if(!is_null($locationData)) {
            return response()->json(["status" => 201, "message" => "Manufacture Produk berhasil diubah !", "data" => $locationData]);
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
        $location = PurchaseLocation::findOrFail($id);
        $location->delete();
        return response()->json(["message" => "Berhasil"]);
    }
}

