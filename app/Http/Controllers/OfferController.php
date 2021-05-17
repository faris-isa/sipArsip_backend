<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\OfferPurchase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Shared\Converter;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::with('purchases')
        ->with('products')
        ->get();

        return response()->json($offers);

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
        $validator = Validator::make($request->all(), [
            "nama_pembeli" => "required",
            "harga_total" => "required",
            "detail_produk" => "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $offer = new Offer;
        $offer->nama_pembeli = $request->nama_pembeli;
        $offer->harga_total = $request->harga_total;
        $offer->status = "not decided";
        $offer->save();

        $detail = $request->detail_produk;
        $array = json_decode($detail);
        $number = count($array);

        for ($x = 0; $x < $number; $x++){
            $offer->products()->attach($offer->id,
            ['product_id' => $array[$x]->id,
            'qty' => $array[$x]->qty,
            'harga' => $array[$x]->harga_satuan]);
        };

        $offer->purchases()->attach($offer->id,[
            'purchase_id' => null,
            'status' => 'penawaran',
            'created_at' => date("Y-m-d H:i:s"),
            'purchased_at' => null,
            'done_at' => null
            ]);

        if(!is_null($offer->id)) {
            return response()->json(["status" => 201, "message" => "Offer berhasil didaftarkan !", "penawaran" => $offer]);
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
        // $offer = Offer::findOrFail('offers', 'offers.id', '=', 'offer_details.offer_id')
        // ->join('products', 'products.id', '=', 'offer_details.product_id')
        // ->where('offer_id', $id)
        // ->get();
        $offer = Offer::find($id);

        $offer->detail = $offer->products()->get();
        $offer->purchase = $offer->purchases()->get();


        return response()->json($offer);
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
        $offer = Offer::findOrFail($id);
        $offer->nama_pembeli = $request->nama_pembeli;
        $offer->total_biaya = $request->total_biaya;
        $offer->status = $request->status;
        $offer->save();
        return response()->json($offer);
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

    public function status(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "status" => "required",
            "status_offpur" => "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $offer = Offer::find($id);
        $offer->status = $request->status;
        $offer->save();

        $status = $request->status_offpur;

        if ($request->status_offpur == "pembelian"){
            $purchase = new Purchase;
            $purchase->status = 'belum';
            $purchase->save();

            $offer->updatePurchase = DB::table('offer_purchase')
            ->where('offer_id', $offer->id)
            ->update(
                [
                    'purchase_id'=>$purchase->id,
                    'status' => $request->status_offpur,
                    'purchased_at'=> date("Y-m-d H:i:s"),
                ]
            );
        } else if ($request->status_offpur == "selesai"){

            $offer->updatePurchase = DB::table('offer_purchase')
            ->where('offer_id', $offer->id)
            ->update(
                [
                    'status' => $request->status_offpur,
                    'done_at'=> date("Y-m-d H:i:s"),
                ]
            );
        }

        if(!is_null($offer)) {
            return response()->json(["status" => 201, "message" => "Penawaran berhasil diupdate !", "data" => $offer]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal diubah, periksa kembali !"]);
        }
    }

    public function exportWord($id)
    {
        $offer = Offer::find($id);
        $detail = $offer->products()->get();

        $template = new TemplateProcessor('./public/word-template/penawaran.docx');


        $allId = [];

        for ($i=0; $i< count($detail); $i++){
            $getId = $detail[$i]->id;
            array_push($allId, $getId);

            $subtotal = $detail[$i]->pivot->qty * $detail[$i]->pivot->harga;

            //setValues table 1
            $valuesInvoice[] = [
                'itemId' => $i+1,
                'model_produk' => $detail[$i]->model_produk,
                'qty'=>$detail[$i]->pivot->qty,
                "harga_satuan" => $detail[$i]->harga_satuan,
                "harga" => $subtotal,
            ];
        };


        //getSubProduct
        $productData = [];
        for ($i = 0; $i < count($allId); $i++) {
            // $productdet = Product::find($allId[$i])->with('detail')->get();
            $productdet = Product::where('products.id', $allId[$i])
            ->join('product_details', 'product_details.product_id', '=', 'products.id')
            ->join('product_manufactures', 'product_details.product_manufacture_id', '=', 'product_manufactures.id')
            ->first();
            array_push($productData, $productdet);
        };

        // $valuesProduct = [];
        //set values for tables 2
        for ($i=0; $i< count($productData); $i++){
            $valuesProduct[] = [
                'productId' => $i+1,
                'model_produk' => $productData[$i]->model_produk,
                'manufacture' => $productData[$i]->name,
                'deskripsi_produk'=>$productData[$i]->spesifikasi,
            ];
        };

        $year = date('y');
        $yearBig = date('Y');
        $month = date('m');
        $day = date('d');
        $setDate = $year.$month.$day;

        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($i= 1;$i<=$dayInMonth;$i++){
            $tanggalan[] = $i;
        };

        $terakhir = end($tanggalan);

        $firstDate = $yearBig.'-'.$month.'-'.$tanggalan[0];
        $lastDate = $yearBig.'-'.$month.'-'.$terakhir;

        $much = DB::table('offers')
        ->where('created_at', '>=', $firstDate)
        ->where('created_at', '<=', $lastDate)
        ->count();

        $template->setValue('nama_pembeli', $offer->nama_pembeli);
        $template->setValue('howmonth', $much+1);
        $template->setValue('numbering', $setDate);
        $template->setValue('harga_total', $offer->harga_total);
        $template->cloneRowAndSetValues('itemId', $valuesInvoice);
        $template->cloneRowAndSetValues('productId', $valuesProduct);
        for ($i=0; $i< count($productData); $i++){
            $p = $i + 1;
            $template->setImageValue('foto_produk#'.$p, array('path' => $productData[$i]->foto_produk));
        // $templateProcessor->setImageValue('', $productData[$i]->foto_produk');
        }

        $fileName = $offer->nama_pembeli;
        $template->saveAs($fileName.'.docx');
        return response()->download($fileName.'.docx')->deleteFileAfterSend(true);
    }

    public function exportGraph(){
        $yearBig = date('Y');
        $year = date('y');
        $month = date('m');
        $day = date('d');
        $bulanAngka = date('n');
        $setDate = $year.$month.$day;
        $namaBulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $namaBulanJadi = $namaBulan[$bulanAngka];
        $minus = $yearBig-2;

        for ($i= $minus;$i<=$yearBig;$i++){
            $categories[] = $i;
        };
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i= 1;$i<=$dayInMonth;$i++){
            $tanggalan[] = $i;
        };


        $series=array();
        $seriesProductMuch=array();
        $series2=array();
        $series3=array();

        //getPeriodic
        for ($i= $minus;$i<=$yearBig;$i++){
            //query getPurchase
            $query = DB::table('purchases')
            ->where('status', 'terbeli')
            ->whereYear('updated_at', $i)
            ->count();
            array_push($series, $query);
            //query getProduct
            $query1 = DB::table('product_purchase')
            ->whereYear('tanggal_beli', $i)
            ->count();
            array_push($seriesProductMuch, $query1);
        }
        //getYearMonth
        for ($i = 1; $i<=count($namaBulan); $i++){
            // $tanggalReal = $tahun.'-'.$bulan.'-'.$i;
            $query = DB::table('purchases')
            ->where('status', 'terbeli')
            ->whereYear('updated_at', $yearBig)
            ->whereMonth('updated_at', $i)
            ->count();
            array_push($series2, $query);
        }

        //get Monthly
        for ($i = 1; $i<=$dayInMonth; $i++){
            // $tanggalReal = $tahun.'-'.$bulan.'-'.$i;
            $query = DB::table('purchases')
            ->where('status', 'terbeli')
            ->whereDate('updated_at', $yearBig.'-'.$month.'-'.$i)
            // ->whereDate('updated_at', $tahun.'-'.$bulan.'-'.$i)
            ->count();
            array_push($series3, $query);
        }

        // $series=[7,4,8];
        // $series2=[0,0,0,0,0,0,0,0,0,0,0,0];
        // $series3=[0,0,0,0,0,0,0,0,0,0,
        //         0,0,0,0,0,0,0,0,0,0,
        //         0,0,0,0,0,0,0,0,0,0];
        $stylePeriodic = array(
            'title'                 => 'Banyak Pembelian',
            'width'                 => Converter::cmToEmu(15),
            'height'                => Converter::cmToEmu(9),
            'showAxisLabels'        => true,
            'categoryAxisTitle'     => 'Tahun',
        );
        $styleYear = array(
            'title'                 => 'Banyak Pembelian',
            'width'                 => Converter::cmToEmu(15),
            'height'                => Converter::cmToEmu(9),
            'showAxisLabels'        => true,
            'categoryAxisTitle'     => 'Tanggal',
        );
        $styleMonth = array(
            'title'                 => 'Banyak Pembelian',
            'width'                 => Converter::cmToEmu(15),
            'height'                => Converter::cmToEmu(9),
            'showAxisLabels'        => true,
            'categoryAxisTitle'     => 'Tanggal',
        );

        // New Word document
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $text1 = $section->addText(
            'Jumlah Penjualan Periode '.$categories[0].' - '.$categories[2],
            array('size' => 20), array('alignment' => 'center')
        );
        $chart1 = $section->addChart('column', $categories, $series, $stylePeriodic);
        $section->addTextBreak(4);
        $text2 = $section->addText(
            'Jumlah Barang Terjual Periode '.$categories[0].' - '.$categories[2],
            array('size' => 20), array('alignment' => 'center')
        );
        $chart2 = $section->addChart('column', $categories, $seriesProductMuch, $stylePeriodic);
        $section->addPageBreak();
        $text3 = $section->addText(
            'Jumlah Penjualan Tahun '.$categories[2],
            array('size' => 20), array('alignment' => 'center')
        );
        $chart3 = $section->addChart('column', $namaBulan, $series2, $styleYear);
        $section->addTextBreak(4);
        $text4 = $section->addText(
            'Jumlah Penjualan Bulan '.$namaBulanJadi.' '.$categories[2],
            array('size' => 20), array('alignment' => 'center')
        );
        $chart4 = $section->addChart('column', $tanggalan, $series3, $styleMonth);
        $phpWord->save($setDate.'.docx');

        return response()->download($setDate.'.docx')->deleteFileAfterSend(true);
    }
}
