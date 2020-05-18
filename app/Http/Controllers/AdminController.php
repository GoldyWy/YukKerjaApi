<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pekerja;
use App\Perusahaan;
use App\Lowongan;
use App\Keahlian;

class AdminController extends Controller
{

    public function insertKeahlian(Request $request)
    {
        $id = $request->id;
        $nama = $request->nama;
        

        if ($id == "0") {
            
            $cariKeahlian = Keahlian::where('nama',$nama)->get()->count();

            if($cariKeahlian == 0) {
                $keahlian = Keahlian::create([
                    'nama' => $nama
                ]);

                if($keahlian->exists){
                    $keahlianData = Keahlian::orderBy('id','desc')->get();
                    $data['status'] = "1";
                    $data['message'] = "Berhasil menambahkan data...";
                    $data['keahlians'] = $keahlianData->toArray();
                    return $data;
                }else{
                    $data['status'] = "0";
                    $data['message'] = "Oops ada kesalahan...";
                    return $data;
                }
            }else{
                $data['status'] = "0";
                $data['message'] = "Keahlian Sudah tersedia...";
                return $data;
            }

            

        }else{
            $data['status'] = "0";
            $data['message'] = "Maaf data untuk admin...";
            return $data;
        }
    }

    public function getKeahlian(Request $request)
    {
        $id = $request->id;

        if ($id == "0") {
            $keahlian = Keahlian::orderBy('id','desc')->get();
            $countKeahlian = $keahlian->count();
            if ($countKeahlian>0) {
                $data['status'] = "1";
                $data['message'] = "Data tersedia...";
                $data['keahlians'] = $keahlian->toArray();
                return $data;
            }else{
                $data['status'] = "0";
                $data['message'] = "Data tidak tersedia...";
                $data['keahlians'] = [];
                return $data;
            }

        }else{
            $data['status'] = "0";
            $data['message'] = "Maaf data untuk admin...";
            return $data;
        }

    }



    public function getHalamanUtama(Request $request)
    {
        $id = $request->id;

        if ($id == "0") {
            $totalperusahaan = Perusahaan::select('id')->get()->count();
            $totalpekerja = Pekerja::select('id')->get()->count();
            $totalpekerjaaktif = Pekerja::select('id')->where('status','1')->get()->count();
            $totalpekerjanonaktif = Pekerja::select('id')->where('status','0')->get()->count();
            $totallowongan = Lowongan::select('id')->get()->count();
            $totallowonganaktif = Lowongan::select('id')->where('status','1')->get()->count();
            $totallowongantutup = Lowongan::select('id')->where('status','0')->get()->count();
            $totallowongansejarah = Lowongan::select('id')->where('status','2')->get()->count();
          
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['perusahaan'] = $totalperusahaan;
            $data['pekerja'] = $totalpekerja;
            $data['pekerjaaktif'] = $totalpekerjaaktif;
            $data['pekerjanonaktif'] = $totalpekerjanonaktif;
            $data['lowongan'] = $totallowongan;
            $data['lowonganaktif'] = $totallowonganaktif;
            $data['lowongantutup'] = $totallowongantutup;
            $data['lowongansejarah'] = $totallowongansejarah;
            return $data;
   
        }else{
            $data['status'] = "0";
            $data['message'] = "Maaf data untuk admin...";
            return $data;
        }
    }




}
