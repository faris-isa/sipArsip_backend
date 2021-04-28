<?php

namespace App\Http\Controllers;

use App\Models\OfferPurchase;
use App\Models\OfferDetail;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offpurs = OfferPurchase::select("offer_purchases.id","offer_purchases.status", "offers.nama_pembeli", "offer_purchases.offer_id")
        ->join('offers', 'offers.id', '=', 'offer_purchases.offer_id')
        // ->join('purchases', 'purchases.id', '=', 'offer_purchases.purchase_id')
        ->get();

        return $offpurs->toJson();
        // return $offpurs;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offpurs = OfferPurchase::select("offers.nama_pembeli", "products.model_produk", "products.id")
        ->join('offers', 'offers.id', '=', 'offer_purchases.offer_id')
        ->join('offer_details', 'offer_details.offer_id', '=', 'offers.id')
        ->join('products', 'products.id', '=', 'offer_details.product_id')
        ->where('offer_purchases.id', $id)
        ->get();

        return $offpurs->toJson();
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
}
