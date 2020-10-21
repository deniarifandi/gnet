<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App;


class dashboardController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

    	$page = $request->input('page');
    	$search = $request->input('search');
    	$tahun = $request->input('tahun');

    	$customers_count = DB::table('customers')
    	->where('nama','like','%'.$search.'%')
    	->count();

    	$pageCount = floor($customers_count/5);

    	// echo floor($pageCount);
    	// $tahun = "2020";
    	
    	$getCustomer = json_decode($this->getCustomer($tahun,$page,$search),true);
    	return view('dashboard', ['customers' => $getCustomer, 'pagecount' => $pageCount]);

    }

    public function registerbro(){
    	return view('auth.register');
    }

    public function open_tambah_pelanggan(){

    	return view('openTambahPelanggan');

    }



    public function pengisi_pelanggan(){
    	echo 'isi pelanggan';

    	for ($i=0; $i < 250; $i++) { 
    		
    		$query = DB::table('customers')->insert(
		    	['nama' => 'nama'.$i, 'telepon' => $i]
			);

    	}
    }

    public function func_tambah_pelanggan(Request $request){
    	$nama = $request->input('nama');
    	$telepon = $request->input('telepon');

    	$query = DB::table('customers')->insert(
		    ['nama' => $nama, 'telepon' => $telepon]
		);

		if ($query) {
		     return redirect('dashboard?page=0&search=');
		}
    }

    public function postBayar(Request $request){
    	$customer_id = $request->input('customer_id');
    	$bulan = $request->input('bulan');
    	$tahun = $request->input('tahun');
    	$nominal = $request->input('nominal');

    	$query = DB::table('pembayarans')->insert(
		    [
		    	'customer_id' => $customer_id,
		    	'bulan' => $bulan,
		    	'tahun' => $tahun,
		    	'nominal' => $nominal
		    ]
		);

		if ($query) {
		     return "good";
		}else{
			return "fail";
		}
    }

    public function postTidakBayar(Request $request){
    	$customer_id = $request->input('customer_id');
    	$bulan = $request->input('bulan');
    	$tahun = $request->input('tahun');

    	$query = DB::table('pembayarans')
    	->where('customer_id', '=', $customer_id)
    	->where('bulan', '=', $bulan)
    	->where('tahun', '=', $tahun)
    	->delete();

		if ($query) {
		     return "good";
		}else{
			return "fail";
		}
    }

    public function getCustomer($tahun,$page,$search){
    	$stringResult = "";

    	$stringResult.= "[";

    	if ($search == null) {
    		$customers = DB::table('customers')
    				
			    	->offset(5*$page)
                	->limit(5)
                	->get();
    	}else{
    		$customers = DB::table('customers')
    				
    				->where('nama','like','%'.$search.'%')
			    	->offset(5*$page)
                	->limit(5)
                	->get();
    	}
    	
    	if (count($customers) != 0) {
    		for ($i=0; $i < count($customers)-1; $i++) { 
			//inner index
			$stringResult.= "{";
			$stringResult.= "\"customer_id\":\"".$customers[$i]->customer_id."\",";
			$stringResult.= "\"nama\":\"".$customers[$i]->nama."\",";
			$stringResult.= "\"tahun\":\"".$tahun."\",";
			$stringResult.= "\"telepon\":\"".$customers[$i]->telepon."\",";
			for ($x=1; $x <= 12-1; $x++) { 
				$pembayaran = DB::table('pembayarans')
						->where('customer_id','=',$customers[$i]->customer_id)
						->where('bulan','=', $x)
						->where('tahun','=',$tahun)
						->get();	

						if ($pembayaran == "[]") {
							// $stringResult.= "bulan".$x."";
							$stringResult.= "\"bulan".$x."\":\"Belum Bayar\",";
						}else{
							$stringResult.= "\"bulan".$x."\":\"".$pembayaran[0]->id_bayar."\",";
							// $stringResult.= "[".$pembayaran[0]->id_bayar."]";
						}	
			}
			//outer index
				$pembayaran = DB::table('pembayarans')
						->where('customer_id','=',$customers[$i]->customer_id)
						->where('bulan','=', 12)
						->where('tahun','=',$tahun)
						->get();	

						if ($pembayaran == "[]") {
							// $stringResult.= "bulan".$x."";
							$stringResult.= "\"bulan12"."\":\"Belum Bayar\"";
						}else{
							$stringResult.= "\"bulan12"."\":\"".$pembayaran[0]->id_bayar."\"";
							// $stringResult.= "[".$pembayaran[0]->id_bayar."]";
						}
			//outer index

			$stringResult.= "},";
			//inner index
			}
				//last index
		$stringResult.= "{";
			$stringResult.= "\"customer_id\":\"".$customers[$i]->customer_id."\",";
			$stringResult.= "\"nama\":\"".$customers[$i]->nama."\",";
			$stringResult.= "\"tahun\":\"".$tahun."\",";
			$stringResult.= "\"telepon\":\"".$customers[$i]->telepon."\",";
			for ($x=1; $x <= 12-1; $x++) { 
				$pembayaran = DB::table('pembayarans')
						->where('customer_id','=',$customers[$i]->customer_id)
						->where('bulan','=', $x)
						->get();	

						if ($pembayaran == "[]") {
							// $stringResult.= "bulan".$x."";
							$stringResult.= "\"bulan".$x."\":\"Belum Bayar\",";
						}else{
							$stringResult.= "\"bulan".$x."\":\"".$pembayaran[0]->id_bayar."\",";
							// $stringResult.= "[".$pembayaran[0]->id_bayar."]";
						}
				
			}
			//outer index
				$pembayaran = DB::table('pembayarans')
						->where('customer_id','=',$customers[$i]->customer_id)
						->where('bulan','=', 12)
						->get();	

						if ($pembayaran == "[]") {
							// $stringResult.= "bulan".$x."";
							$stringResult.= "\"bulan12"."\":\"Belum Bayar\"";
						}else{
							$stringResult.= "\"bulan12"."\":\"".$pembayaran[0]->id_bayar."\"";
							// $stringResult.= "[".$pembayaran[0]->id_bayar."]";
						}
			//outer index

			$stringResult.= "}";
				//last index
		$stringResult.= "]";
		// $stringResult.= "'";
    	}else{
    		$stringResult = "[]";
    	}

		return $stringResult;
    
    }



     public function print_invoice(){
		date_default_timezone_set("Asia/Bangkok");

     	$id_bayar = $_GET['bayar'];
     	$nama_pelangggan = $_GET['nama'];
     	$nomor_pelanggan = $_GET['nomor'];
     	$id_pelanggan = $_GET['id'];
     	$bulan = $_GET['bulan'];
     	$tahun = $_GET['tahun'];

     	$timestamp = date("d/M/Y");

    	$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('


			<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 10px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h1 style="margin-bottom: 0px; margin-left: 0px"> G-Net </h1>
                                <h5 style="margin-top: 15px; margin-left: 5px; margin-bottom: 0px">INVOICE</h5>
                            </td>
                            
                            <td style="padding-left: 10px">
                                Invoice #: '.$id_bayar.'<br>
                                Created: '.$timestamp.'<br>
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="padding-left: 10px; margin-top: 0px">
                                 Dusun Krajan 2, RT 002RW 012, <br>
                                 Krajan I, Grenden, Puger, <br>
                                 Kabupaten Jember, Jawa Timur 68164
                            </td>
                            
                            <td style="text-align:left">
                                id   : '.$id_pelanggan.'<br>
                                nama 	:'.$nama_pelangggan.'<br>
                                nomor 	:'.$nomor_pelanggan.'<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
        
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Price
                </td>
            </tr>
       
            <tr class="item last">
                <td>
                    Wi-Fi <br>
                    '.$bulan.', '.$tahun.'
                </td>
                
                <td>
                    Rp. 100.000,-
                </td>
            </tr>
            
            <tr class="total">
                <td></td>
                
                <td>
                   Total: Rp. 100.000,-
                </td>
            </tr>

            <tr style="text-align: right;">
                
                            <td>
                                
                            </td>
                            <td style="padding-top: 70px">
                                Admin G-Net
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                ----------------------
                            </td>
            </tr>

        </table>
    </div>
</body>
</html>


			');

		// return $pdf->stream();
		// $pdf->loadView('dashboard');
		return $pdf->stream();
    }

}
