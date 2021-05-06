<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductManufacture;
use App\Models\ProductType;
// use App\Models\NvrProduct;
// use App\Models\IpcamProduct;
// use App\Models\PoeswtProduct;


class ProductController extends Controller
{

    private $url = "http://127.0.0.1/backend/upload/product/";
    // public $url = "https://m3117063.api.isabot.site/upload/product/";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('id','asc')
        ->with('detail')
        ->with('type')
        ->get();
        return response()->json($products);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "model_produk" => "required",
            "product_type" => "required",
            "status" => "required",
            "harga_satuan" => "required",
            "product_manufacture" => "required",
            "spesifikasi" => "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $productDataArray = array(
            "model_produk" => $request->model_produk,
            "product_type_id" => $request->product_type,
            "status" => $request->status,
            "harga_satuan" => $request->harga_satuan,
        );


        $product  = Product::create($productDataArray);

        $tipe = ProductType::find($request->product_type);

        if(!is_null($product)) {
            $detail = new ProductDetail;
            $detail->product_id = $product->id;
            $detail->product_manufacture_id = $request->product_manufacture;
            $detail->spesifikasi = $request->spesifikasi;

            //save foto
            $foto = $request->file('foto_produk');
            $extension = $foto->getClientOriginalExtension();
            $nama_foto = rand().time().'.'.$extension ;
            $foto->move(base_path('/upload/product/'.strtolower($tipe->code_name)), $nama_foto);
            //buat load foto
            $detail->foto_produk = $this->url.strtolower($tipe->code_name).'/'.$nama_foto;
            $detail->save();

            return response()->json(["status" => 201, "message" => "Tipe Produk berhasil didaftarkan !", "data" => $product, "detail" => $detail]);
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
        $product = Product::find($id);
        $product->detail = ProductDetail::where('product_id', $id)->first();
        $product->manufacture = $product->detail->manufacture()->get();
        $product->type = $product->type()->get();

        return response()->json($product);
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
        $validator = Validator::make($request->all(), [
            "model_produk" => "required",
            "product_type" => "required",
            "status" => "required",
            "harga_satuan" => "required",
            "product_manufacture" => "required",
            "spesifikasi" => "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $productData = Product::findOrFail($id);
        $productData->model_produk = $request->model_produk;
        $productData->product_type_id = $request->product_type;
        $productData->status = $request->status;
        $productData->harga_satuan = $request->harga_satuan;
        $productData->save();

        $tipe = ProductType::find($request->product_type);

        if(!is_null($productData)) {
            $foto = $request->file('foto_produk');
            $detail = ProductDetail::where('product_id', $productData->id)->first();
            $upFoto;

            if(is_null($foto)){
                $upFoto = $detail->foto_produk;
                // return response()->json($upFoto);
            } else {
                $extension = $foto->getClientOriginalExtension();
                $nama_foto = rand().time().'.'.$extension ;
                $foto->move(base_path('/upload/product/'.strtolower($tipe->code_name).'/'), $nama_foto);
                //buat load foto
                $upFoto = $this->url.strtolower($tipe->code_name).'/'.$nama_foto;
            }

            $detail->product_manufacture_id = $request->product_manufacture;
            $detail->spesifikasi = $request->spesifikasi;
            $detail->foto_produk = $upFoto;

            //save
            $detail->save();

            return response()->json(["status" => 201, "message" => "Tipe Produk berhasil didaftarkan !", "data" => $productData, "detail" => $detail]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
        }

    }

    public function status(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->status = $request->status;
        $product->save();

        if(!is_null($product)) {
            return response()->json(["status" => 201, "message" => "Akun berhasil diupdate !", "data" => $product]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal diubah, periksa kembali !"]);
        }

    }
}
