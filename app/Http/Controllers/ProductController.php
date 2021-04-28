<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\NvrProduct;
use App\Models\IpcamProduct;
use App\Models\PoeswtProduct;


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
        $products = Product::orderBy('id','asc')->get();
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
            "type_products" => "required",
            "harga_satuan" => "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $type = $request->type_products;

        if ($type == "nvr"){
            $validator1 = Validator::make($request->all(), [
                "in_bandwidth" => "required",
                "out_bandwidth" => "required",
                "channel_dicoding" => "required",
                "channel_dicoding" => "required",
                "four_k_support" => "required",
                "sata_int" => "required",
                "network_int" => "required",
                "e_sata" => "required",
                "poe_ports" => "required",
                "hdmi_out" => "required",
                "deskripsi_produk" => "required",
                "foto_produk" => "required"
            ]);
            if($validator1->fails()) {
                return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
            }

            } else if ($type == "poeswitch"){
                $validator1 = Validator::make($request->all(), [
                    "deskripsi_produk" => "required",
                    "foto_produk" => "required"
                ]);
                if($validator1->fails()) {
                    return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
                }

                } else if ($type == "ipcam"){
            $validator1 = Validator::make($request->all(), [
                "max_resolution" => "required",
                "form_factor" => "required",
                "lens" => "required",
                "wdr" => "required",
                "protection" => "required",
                "deskripsi_produk" => "required",
                "foto_produk" => "required"
            ]);
            if($validator1->fails()) {
                return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
            }
        }

        // $url = "http://127.0.0.1/backend/upload/product/";

        $productDataArray = array(
            "model_produk" => $request->model_produk,
            "type_products" => $type,
            "status" => 'ongoing',
            "harga_satuan" => $request->harga_satuan,
        );

        $product  = Product::create($productDataArray);

        if ($type == "nvr"){
            $nvrproduct = new NvrProduct;
            $nvrproduct->product_id = $product->id;
            $nvrproduct->in_bandwidth = $request->in_bandwidth;
            $nvrproduct->out_bandwidth = $request->out_bandwidth;
            $nvrproduct->channel_dicoding = $request->channel_dicoding;
            $nvrproduct->four_k_support = $request->four_k_support;
            $nvrproduct->sata_int = $request->sata_int;
            $nvrproduct->network_int = $request->network_int;
            $nvrproduct->e_sata = $request->e_sata;
            $nvrproduct->poe_ports = $request->poe_ports;
            $nvrproduct->hdmi_out = $request->hdmi_out;
            $nvrproduct->deskripsi_produk = $request->deskripsi_produk;

            //save foto
            $foto = $request->file('foto_produk');
            $extension = $foto->getClientOriginalExtension();
            $nama_foto = rand().time().'.'.$extension ;
            $foto->move(base_path('/upload/product/nvr'), $nama_foto);
            //buat load foto
            $nvrproduct->foto_produk = $this->url."nvr/".$nama_foto;
            $nvrproduct->save();

            if(!is_null($nvrproduct)) {
                return response()->json(["status" => 201, "message" => "Produk berhasil didaftarkan !", "data" => $nvrproduct, "product" => $product]);
            } else {
                return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
            }


        } else if ($type == "poeswitch"){
            $poeswt = new PoeswtProduct;
            $poeswt->product_id = $product->id;
            $poeswt->deskripsi_produk = $request->deskripsi_produk;

            //save foto
            $foto = $request->file('foto_produk');
            $extension = $foto->getClientOriginalExtension();
            $nama_foto = rand().time().'.'.$extension ;
            $foto->move(base_path('/upload/product/poe'), $nama_foto);
            //buat load foto
            $poeswt->foto_produk = $this->url."poe/".$nama_foto;
            $poeswt->save();

            if(!is_null($poeswt)) {
                return response()->json(["status" => 201, "message" => "Produk berhasil didaftarkan !", "data" => $poeswt, "product" => $product]);
            } else {
                return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
            }

        } else if($type == "ipcam"){
            $ipcam = new IpcamProduct;
            $ipcam->product_id = $product->id;
            $ipcam->max_resolution = $request->max_resolution;
            $ipcam->form_factor = $request->form_factor;
            $ipcam->lens = $request->lens;
            $ipcam->wdr = $request->wdr;
            $ipcam->protection = $request->protection;
            $ipcam->deskripsi_produk = $request->deskripsi_produk;

            //save foto
            $foto = $request->file('foto_produk');
            $extension = $foto->getClientOriginalExtension();
            $nama_foto = rand().time().'.'.$extension ;
            $foto->move(base_path('/upload/product/ipcam'), $nama_foto);
            //buat load foto
            $ipcam->foto_produk = $this->url."ipcam/".$nama_foto;
            // $poeswt->foto_produk = 'http://127.0.0.1/backend/upload/product/ipcam/'.$nama_foto;
            $ipcam->save();

            if(!is_null($ipcam)) {
                return response()->json(["status" => 201, "message" => "Produk berhasil didaftarkan !", "data" => $ipcam, "product" => $product]);
            } else {
                return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
            }
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
        $product = Product::findOrFail($id);

        $val_type = $product->type_products;
        if ($val_type == "poeswitch"){
            $product = Product::where('products.id', $id)
                ->join('poeswt_products', 'products.id', '=', 'poeswt_products.product_id')
                ->first();
        } else if ($val_type == "ipcam"){
            $product = Product::where('products.id', $id)
                ->join('ipcam_products', 'products.id', '=', 'ipcam_products.product_id')
                ->first();
        } else if ($val_type == "nvr"){
            $product = Product::where('products.id', $id)
                ->join('nvr_products', 'products.id', '=', 'nvr_products.product_id')
                ->first();
        }


        return response()->json($product);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productData = Product::findOrFail($id);
        $type = $productData->type_products;

        $validator = Validator::make($request->all(), [
            "model_produk" => "required",
            "type_products" => "required",
            "harga_satuan" => "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        if ($type == "nvr"){
            $validator1 = Validator::make($request->all(), [
                "in_bandwidth" => "required",
                "out_bandwidth" => "required",
                "channel_dicoding" => "required",
                "channel_dicoding" => "required",
                "four_k_support" => "required",
                "sata_int" => "required",
                "network_int" => "required",
                "e_sata" => "required",
                "poe_ports" => "required",
                "hdmi_out" => "required",
                "deskripsi_produk" => "required",
                // "foto_produk" => "required"
            ]);
            if($validator1->fails()) {
                return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
            }

            } else if ($type == "poeswitch"){
                $validator1 = Validator::make($request->all(), [
                    "deskripsi_produk" => "required",
                    // "foto_produk" => "required"
                ]);

                if($validator1->fails()) {
                    return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
                }

                } else if ($type == "ipcam"){
            $validator1 = Validator::make($request->all(), [
                "max_resolution" => "required",
                "form_factor" => "required",
                "lens" => "required",
                "wdr" => "required",
                "protection" => "required",
                "deskripsi_produk" => "required",
                // "foto_produk" => "required"
            ]);
            if($validator1->fails()) {
                return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
            }
        }

        // $url = "http://127.0.0.1/backend/upload/product/";

        $productData->model_produk = $request->model_produk;
        $productData->harga_satuan = $request->harga_satuan;
        $productData->save();

        if ($type == "nvr"){

            $foto = $request->file('foto_produk');
            $upFoto;
            $nvrProduct = NvrProduct::where('product_id', $id)->first();

            if($foto == ""){
                $upFoto = $nvrProduct->foto_produk;
            } else {
                $extension = $foto->getClientOriginalExtension();
                $nama_foto = rand().time().'.'.$extension ;
                $foto->move(base_path('/upload/product/nvr'), $nama_foto);
                //buat load foto
                $upFoto = $this->url."nvr/".$nama_foto;
            }

            $nvrUpdate = DB::table('nvr_products')->where('product_id', $id)->update(
                [   'in_bandwidth' => $request->in_bandwidth,
                    'out_bandwidth' => $request->out_bandwidth,
                    'channel_dicoding' => $request->channel_dicoding,
                    'four_k_support' => $request->four_k_support,
                    'sata_int' => $request->sata_int,
                    'network_int' => $request->network_int,
                    'e_sata' => $request->e_sata,
                    'poe_ports' => $request->poe_ports,
                    'hdmi_out' => $request->hdmi_out,
                    'deskripsi_produk' => $request->deskripsi_produk,
                    'foto_produk' => $upFoto ]);

            if(!is_null($nvrUpdate)) {
                return response()->json(["status" => 201, "message" => "Produk berhasil didaftarkan !", "data" => $nvrUpdate, "product" => $productData]);
            } else {
                return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
            }

        } else if ($type == "poeswitch"){

            $foto = $request->file('foto_produk');
            $upFoto;
            $poeProduct = PoeswtProduct::where('product_id', $id)->first();

            if($foto == ""){
                $upFoto = $poeProduct->foto_produk;
            } else {
                $extension = $foto->getClientOriginalExtension();
                $nama_foto = rand().time().'.'.$extension ;
                $foto->move(base_path('/upload/product/poe'), $nama_foto);
                //buat load foto
                $upFoto = $this->url."poe/".$nama_foto;
            }

            $poeUpdate = DB::table('poeswt_products')->where('product_id', $id)->update(
                [   'deskripsi_produk' => $request->deskripsi_produk,
                    'foto_produk' => $upFoto ]);

            if(!is_null($poeUpdate)) {
                return response()->json(["status" => 201, "message" => "Produk berhasil didaftarkan !", "data" => $poeUpdate, "product" => $productData]);
            } else {
                return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
            }

        } else if($type == "ipcam"){

            $foto = $request->file('foto_produk');
            $upFoto;
            $ipcamProduct = IpcamProduct::where('product_id', $id)->first();

            if($foto == ""){
                $upFoto = $ipcamProduct->foto_produk;
            } else {
                $extension = $foto->getClientOriginalExtension();
                $nama_foto = rand().time().'.'.$extension ;
                $foto->move(base_path('/upload/product/ipcam'), $nama_foto);
                //buat load foto
                $upFoto = $this->url."ipcam/".$nama_foto;
            }

            $ipcamUpdate = DB::table('ipcam_products')->where('product_id', $id)->update(
                [
                    'max_resolution' => $request->max_resolution,
                    'form_factor' => $request->form_factor,
                    'lens' => $request->lens,
                    'wdr' => $request->wdr,
                    'protection' => $request->protection,
                    'deskripsi_produk' => $request->deskripsi_produk,
                    'foto_produk' => $upFoto ]);

            if(!is_null($ipcamUpdate)) {
                return response()->json(["status" => 201, "message" => "Produk berhasil didaftarkan !", "data" => $ipcamUpdate, "product" => $productData]);
            } else {
                return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
            }
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
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json($product);
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
