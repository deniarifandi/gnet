<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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

}
