<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Purchase;
use App\Models\Offer;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $purchases = Purchase::get();

        // foreach (Purchase::all() as $purchases) {
        //     $purchases->tawaran = $purchases->offers();
        // }

        // $purchases = Purchase::join('offer_purchase', 'offer_purchase.purchase_id', '=', 'purchases.id')
        // ->join('offers', 'offers.id', '=', 'offer_purchase.offer_id')
        // ->get(['purchases.id','offers.nama_pembeli', 'purchases.status']);

        $purchases = Purchase::with('offers')
        ->with('products')
        ->get();

        return response()->json($purchases);
        // return $purchases->toJSon();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function store(Request $request)
    {
        $detail = $request->purchase_detail;
        $array = json_decode($detail);
        $number = count($array);

        $purchase = Purchase::findOrFail($request->purchase_id);

        for ($x = 0; $x < $number; $x++){

            $tanggal =  strtotime($array[$x]->tanggal);
            $jangka = $array[$x]->masa_garansi;
            $tanggal_baru = strtotime('+'.$jangka.'years', $tanggal);
            $tanggal_habis = date('Y-m-d', $tanggal_baru);

            $purchase->products()->attach($purchase->id,
            ['product_id' => $array[$x]->id,
            'serial_number' => $array[$x]->serial,
            'tanggal_beli' => $array[$x]->tanggal,
            'masa_garansi' => $jangka,
            'tanggal_selesai' => $tanggal_habis,
            'lokasi' => $array[$x]->lokasi]);
        };

        $purchase->updateOffers = DB::table('offer_purchase')->where('purchase_id', $purchase->id)->update(
            ['status' => 'selesai'],
        );
        $purchase->updateStatus = DB::table('purchases')->where('id', $purchase->id)->update(
            ['status' => 'terbeli',
            'updated_at' => date('Y-m-d')
            ]
        );

        if(!is_null($purchase)) {
            return response()->json(["status" => 201, "message" => "Akun berhasil diupdate !", "data" => $purchase]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal diubah, periksa kembali !"]);
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
        $purchase = Purchase::find($id);
        $purchase->tawaran = $purchase->offers()->get();
        $purchase->detail = $purchase->products()->get();



        $offer = Offer::find($purchase->tawaran[0]->pivot->offer_id);
        $purchase->not_products = $offer->products()->get();
        // return response()->json($purchase);
        return response()->json($purchase);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    public function getSerials(){
        $query = DB::table('product_purchase')->get();

        return response()->json($query);
    }
    public function getSerial($id){
        $query = DB::table('product_purchase')
        ->join('products', 'products.id', '=', 'product_purchase.product_id')
        ->join('purchases', 'purchases.id', '=', 'product_purchase.purchase_id')
        ->where('product_purchase.id', $id)
        ->select('product_purchase.*')
        ->get();

        return response()->json($query);
    }
    public function getPurchase(Request $request){

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $tanggal = $request->tanggal;
        $counter = array();
        $length = count($tanggal);

        for ($i = 1; $i<=$length; $i++){
            // $tanggalReal = $tahun.'-'.$bulan.'-'.$i;
            $query = DB::table('purchases')
            ->where('status', 'terbeli')
            ->whereDate('updated_at', $tahun.'-'.$bulan.'-'.$i)
            // ->whereDate('updated_at', $tahun.'-'.$bulan.'-'.$i)
            ->count();
            array_push($counter, $query);
        }

        // foreach ($date as $tanggal){
        // }

        return response()->json($counter);
    }
}
