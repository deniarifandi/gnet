<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');

Route::get('/register', [dashboardController::class, 'registerbro']);

Route::get('/openTambahPelanggan', [dashboardController::class, 'open_tambah_pelanggan'])->name('otp');
Route::get('/funcTambahPelanggan', [dashboardController::class, 'func_tambah_pelanggan']);

Route::get('/getCustomer/{tahun}/{page}/{search}', [dashboardController::class, 'getCustomer']);

Route::post('/postbayar', [dashboardController::class, 'postBayar']);
Route::post('/posttidakbayar', [dashboardController::class, 'postTidakBayar']);

Route::get('/pengisi_pelanggan', [dashboardController::class, 'pengisi_pelanggan']);

Route::get('/print_invoice', [dashboardController::class, 'print_invoice']);


Route::get('/fpdf', function () {

   $pdf = App::make('dompdf.wrapper');
	$pdf->loadHTML('<h1>Test</h1>');
	return $pdf->stream();

});