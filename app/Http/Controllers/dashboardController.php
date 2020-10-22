<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App;
use DateTime;

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

     	$user = DB::table('customers')
     	->select('nama', 'telepon', 'alamat')
     	->where('customer_id','=',$id_pelanggan)
     	->get();


     	$alamat = $user[0]->alamat;

     	$bulan = $_GET['bulan'];
     	$tahun = $_GET['tahun'];


     	$dateObj   = DateTime::createFromFormat('!m', $bulan);
		$monthName = $dateObj->format('F'); // March

     	$timestamp = date("d/M/Y");

    	$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('

						

			<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
    <style>


	</style>
   
</head>

<body>
    
	<h1 style="text-align:center; color:grey; font-size: 50px; margin-bottom: 0px"> G-NET </h1>
	<h5 style="text-align:center; margin-top: 10px; margin-bottom: 0px">Dusun Krajan 2, RT 002RW 012, Krajan I, Grenden, Puger, Kabupaten Jember, Jawa Timur 68164</h5>
	<hr style="height: 2px; background-color: red">
	<h2 style="text-align:center; "> INVOICE </h2>

	<table style="width: 100%;">
	
	  <tr style="border: 0px">
	    <td style="width: 20%; border: 0px">ID Pelanggan</td>
	    <td style="border: 0px"> : '.$id_pelanggan.'</td>
	    
	  </tr>
	  <tr>
	    <td style="border: 0px">Nama Pelanggan</td>
	    <td style="border: 0px"> : '.$nama_pelangggan.'</td>
	  
	  </tr>
	  <tr>
	    <td style="border: 0px">Alamat</td>
	    <td style="border: 0px">: '.$alamat.'</td>
	  </tr>
	  <tr>
	    <td style="border: 0px">Jatuh Tempo</td>
	    <td style="border: 0px">: 20 '.$monthName.'</td>
	  </tr>
	</table>
	<br>
	

	<style type="text/css">
		td, th {
			  border: 1px solid #dddddd;
			  text-align: left;
			  padding: 8px;
			}
	</style>

	<h2 style="text-align: center">Pembayaran Terakhir	</h2>
	<table style="width: 100%;">
		<tr>
			<td style="background-color: #e2e2e2">Pembayaran Terakhir</td>
			<td style="background-color: #e2e2e2">Bulan</td>
			<td style="background-color: #e2e2e2">Nominal</td>
		</tr>
		<tr>
			<td>12 Januari, 2020</td>
			<td>Januari 2002</td>
			<td>100.000</td>
		</tr>
	</table>

	<h2 style="text-align: center">Tagihan Bulan Ini </h2>
	<table style="width: 100%;">
		<tr>
			<td style="background-color: #e2e2e2">Tagihan Bulan Ini</td>
			<td style="background-color: #e2e2e2">Bulan</td>
			<td style="background-color: #e2e2e2">Nominal</td>
		</tr>
		<tr>
			<td>12 Januari, 2020</td>
			<td>Januari 2002</td>
			<td>100.000</td>
		</tr>
		<tr>
			<td style="border: 0px"></td>
			<td>Total</td>
			<td>100.000</td>
		</tr>
		<tr>
			<td style="border: 0px"></td>
			<td>Terbilang</td>
			<td>Seratus Ribu Rupiah</td>
		</tr>
	</table>
	<br>
	<br>
	<br>
	<br>
	<table style="width: 100%">
		<tr>
			<td style="border: 0px"></td>
			<td style="border: 0px"></td>
			<td style="width: 30%; border: 0px">Admin G-Net</td>
		</tr>
		<tr>
			<td style="border: 0px"></td>
		</tr>
		<tr>
			<td style="border: 0px"></td>
		</tr>
		<tr>
			<td style="border: 0px"></td>
		</tr>
		<tr>
			<td style="border: 0px"></td>
		</tr>
		<tr >
			<td style="border: 0px"></td>
			<td style="border: 0px"></td>
			<td style="width: 30%; border: 0px; vertical-align: bottom;">..................................</td>
		</tr>
	</table>


</body>
</html>






			');

		// return $pdf->stream();
		// $pdf->loadView('dashboard');
		return $pdf->stream();
    }

}
