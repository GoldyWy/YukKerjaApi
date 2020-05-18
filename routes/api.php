<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clear-cache', function() {
   $exitCode = Artisan::call('cache:clear');
   // return what you want
});


Route::get('/pekerja', 'AuthController@index');
Route::post('/checkUser', 'AuthController@CheckUser');

//Auth
Route::post('/daftarPekerja', 'AuthController@DaftarPekerja');
Route::post('/masuk', 'AuthController@Masuk');
Route::post('/logout', 'AuthController@logout');

Route::post('/daftarPerusahaan', 'AuthController@DaftarPerusahaan');

//Admin
Route::post('/admin/getHalamanUtama', 'AdminController@getHalamanUtama');
Route::post('/admin/getKeahlian', 'AdminController@getKeahlian');
Route::post('/admin/insertKeahlian', 'AdminController@insertKeahlian');


//Perusahaan
Route::post('/perusahaan/simpleDetail', 'PerusahaanController@simpleDetail');
Route::post('/perusahaan/detailPerusahaan', 'PerusahaanController@detailPerusahaan');
Route::post('/perusahaan/lowonganPunyaPerusahaan', 'PerusahaanController@lowonganPunyaPerusahaan');
Route::post('/perusahaan/buatLowongan', 'PerusahaanController@buatLowongan');
Route::post('/perusahaan/detailLowongan', 'PerusahaanController@detailLowongan');
Route::post('/perusahaan/updateStatusLowongan', 'PerusahaanController@updateStatusLowongan');
Route::post('/perusahaan/updateProfilPerusahaan', 'PerusahaanController@updateProfilPerusahaan');
Route::post('/perusahaan/updateLowonganPerusahaan', 'PerusahaanController@updateLowonganPerusahaan');
Route::post('/perusahaan/updateStatusPendaftar', 'PerusahaanController@updateStatusPendaftar');
Route::post('/perusahaan/getPendaftarByIdLowongan', 'PerusahaanController@getPendaftarByIdLowongan');
Route::post('/perusahaan/getDetailPendaftar', 'PerusahaanController@getDetailPendaftar');
Route::post('/perusahaan/gantiPassword', 'PerusahaanController@gantiPassword');

Route::get('/perusahaan/getKeahlian', 'PerusahaanController@getKeahlian');
Route::get('/perusahaan/getCountKeahlian', 'PerusahaanController@getCountKeahlian');

//Pekerja
Route::post('/pekerja/simpleDetail', 'PekerjaController@simpleDetail');
Route::post('/pekerja/updateProfilPekerja', 'PekerjaController@updateProfilPekerja');
Route::post('/pekerja/detailPekerja', 'PekerjaController@detailPekerja');
Route::post('/pekerja/ubahPassword', 'PekerjaController@ubahPassword');


Route::post('/pekerja/insertPendidikan', 'PekerjaController@insertPendidikan');
Route::post('/pekerja/getPendidikanByIdPekerja', 'PekerjaController@getPendidikanByIdPekerja');
Route::post('/pekerja/deletePendidikan', 'PekerjaController@deletePendidikan');
Route::post('/pekerja/updatePendidikan', 'PekerjaController@updatePendidikan');
Route::get('/pekerja/getLowongan', 'PekerjaController@getLowongan');
Route::post('/pekerja/getCariLowongan', 'PekerjaController@getCariLowongan');
Route::post('/pekerja/getDetailLowongan', 'PekerjaController@getDetailLowongan');
Route::post('/pekerja/daftarLowongan', 'PekerjaController@daftarLowongan');
Route::post('/pekerja/getNotifikasi', 'PekerjaController@getNotifikasi');
Route::post('/pekerja/updateNotifikasi', 'PekerjaController@updateNotifikasi');
Route::post('/pekerja/getPendaftarById', 'PekerjaController@getPendaftarById');
Route::post('/pekerja/updateStatusPekerja', 'PekerjaController@updateStatusPekerja');

Route::post('/pekerja/updateResumePekerja', 'PekerjaController@updateResumePekerja');
Route::post('/pekerja/insertKeahlianPekerja', 'PekerjaController@insertKeahlianPekerja');
Route::post('/pekerja/getKeahlianPekerja', 'PekerjaController@getKeahlianPekerja');
Route::post('/pekerja/deleteKeahlianPekerja', 'PekerjaController@deleteKeahlianPekerja');


