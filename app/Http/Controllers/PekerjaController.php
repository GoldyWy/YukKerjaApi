<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perusahaan;
use App\Keahlian;
use App\Lowongan;
use App\Lkeahlian;
use App\Pkeahlian;
use App\Pendaftar;
use App\Notifikasi;
use App\Pekerja;
use App\Pendidikan;
use Carbon\Carbon;
use File;

class PekerjaController extends Controller
{

    public function updateStatusPekerja(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        try {
            $pekerja = Pekerja::where('id',$id)->update([
                'status' => $status
            ]);
            $data['status'] = "1";
            $data['message'] = "Berhasil diubah...";
            return $data;
        } catch (\Illuminate\Database\QueryException $e) {
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }


    }


    public function getPendaftarById(Request $request)
    {

        $id = $request->id;

        $pendaftar = Pendaftar::with(array(
                         'perusahaan'=>function($query){
                            $query->select('id','email','nama','nomor_telp','alamat','foto','informasi');
                         },
                         'lowongan'=>function($query){
                            $query->select('id','judul','range_gaji1','range_gaji2','created_at','updated_at');
                         }
                    ))->where('pekerja_id',$id)->orderBy('status','desc')->get();

        // $pendaftar = Pendaf 
        
        if ($pendaftar->count()>0) {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $pendaftar->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak tersedia...";
            $data['data'] = [];
            return $data;
        }

    }

    public function updateNotifikasi(Request $request)
    {
        $id = $request->id;
        try {
            $notifikasi = Notifikasi::where('id',$id)->update([
                'status' => '1'
            ]);
            $data['status'] = "1";
            $data['message'] = "Berhasil diubah...";
            return $data;
        } catch (\Illuminate\Database\QueryException $e) {
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }

    }


    public function getNotifikasi(Request $request)
    {
        $id = $request->id;

        $notifikasi = Notifikasi::with(array(
                        'perusahaan'=>function($query){
                            $query->select('id','email','nama','nomor_telp','alamat','foto','informasi');
                        },
                        'lowongan'=>function($query){
                            $query->select('id','judul','range_gaji1','range_gaji2','created_at','updated_at');
                        }
                    ))->where('pekerja_id', $id)->orderBy('created_at','desc')->get();
        
        $countNotifikasi = $notifikasi->count();
        if ($countNotifikasi>0) {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $notifikasi->toArray();
            return $data;
        }else{
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = [];
            return $data;
        }

    }

    public function daftarLowongan(Request $request)
    {
        $idpekerja = $request->idpekerja;
        $idperusahaan = $request->idperusahaan;
        $idlowongan = $request->idlowongan;

        $activity = Pendaftar::create([
            'pekerja_id' => $idpekerja,
            'perusahaan_id' => $idperusahaan,
            'lowongan_id' => $idlowongan,
            'status' => '2'
        ]);

        if($activity->exists)
        {
            $data['status'] = "1";
            $data['message'] = "Berhasil melamar pekerjaan...";
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }


    }

    public function getDetailLowongan(Request $request)
    {
        $id = $request->id;
        $idpekerja = $request->idpekerja;

        $lowongan = Lowongan::with(array(
                       'lkeahlian'=>function($query){
                            $query->select('id','lowongan_id','keahlian_id','keahlian_nama','created_at','updated_at');
                        },
                         'perusahaan'=>function($query){
                            $query->select('id','email','nama','nomor_telp','alamat','foto','informasi');
                         }
                    ))->where('id',$id)->get();

        $pendaftar = Pendaftar::where('lowongan_id',$id)->where('pekerja_id',$idpekerja)->get();
        $countPendaftar = $pendaftar->count();
        
        $countLowongan = $lowongan->count();
        
        if($countLowongan>0)
        {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            if($countPendaftar>0){
                $data['daftar'] = "1";
            }else{
                $data['daftar'] = "0";
            }
            
            $data['detail'] = $lowongan->first();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak tersedia...";
            $data['detail'] = [];
            return $data;
        }

    }

    public function getCariLowongan(Request $request)
    {
        $cari = $request->cari;

        $lowongan = Lowongan::with(array(
                         'perusahaan'=>function($query){
                            $query->select('id','email','nama','nomor_telp','alamat','foto','informasi');
                         }
                    ))->where('judul','like','%'.$cari.'%')->where('status','1')->orderBy('created_at','desc')->get();

        $countLowongan = $lowongan->count();

        if($countLowongan>0)
        {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $lowongan->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Lowongan tidak ada...";
            $data['data'] = [];
            return $data;
        }
    }

    public function getLowongan()
    {
        $lowongan = Lowongan::with(array(
                         'perusahaan'=>function($query){
                            $query->select('id','email','nama','nomor_telp','alamat','foto','informasi');
                         }
                    ))->where('status','1')->orderBy('created_at','desc')->get();
        
        $countLowongan = $lowongan->count();
        
        if($countLowongan>0)
        {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $lowongan->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak tersedia...";
            $data['data'] = [];
            return $data;
        }


    }

    public function pushNotification(){
        
        $tokenuser = '["eRTOeqlsNAA:APA91bELlT0h55-dgoc7EQz7hUa02anMaN5DY5ZzPoHmdWvj-N07QHzRC9334yPOxzF4TfclP2dU2yH_iX0rigPW_NORXhdEeLUFL-UABhMS2wKLFG1EaguvhNE_vtr2MIPwHHRd56Ur"]';
        $authKey = "key=AAAAHnBKhAI:APA91bEKVdFt-oc3ZSZHDUF8-gb9IyUI4NN3-YO_FacwPFc1rMW42HMkircoeMSJmoQYE9yD9eh4ZJ3ErRE2gB7TFn1g3iGNjk2hfuUlmyvmSdHiMeXz8syyfuFGuacE0bQ-eoa_Gj_u";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>'{
            "registration_ids": '.$tokenuser.',
            "notification": {
                "title":"Notifikasi Pekerjaan",
                "body":"Web Developer pada PT Kirana Megatara"
            }
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-type: application/json",
                "Authorization: ".$authKey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    }

    public function deleteKeahlianPekerja(Request $request)
    {
        $id = $request->id;
        $idpkeahlian = $request->idpkeahlian;
        $keahlian = Pkeahlian::where('id',$idpkeahlian)->delete();

        $keahlianPekerja = Pkeahlian::where('pekerja_id',$id)->get();
        $countKeahlian = $keahlianPekerja->count();

        if($countKeahlian>0){
            $data['status'] = "1";
            $data['message'] = "Berhasil menyimpan data...";
            $data['data'] = $keahlianPekerja->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak ada...";
            $data['data'] = [];
            return $data;
        }


    }

    public function getKeahlianPekerja(Request $request)
    {
        $id = $request->id;

        $keahlian = Pkeahlian::where('pekerja_id',$id)->get();
        $countKeahlian = $keahlian->count();

        if($countKeahlian>0){
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $keahlian->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak ada...";
            $data['data'] = [];
            return $data;
        }


    }


    public function insertKeahlianPekerja(Request $request)
    {
        $id = $request->id;
        $keahlian = $request->keahlian;
        $tingkat = $request->tingkat;

        $keahlianid = Keahlian::select('id')->where('nama',$keahlian)->get()->first()->id;

        $activity = Pkeahlian::create([
            'pekerja_id' => $id,
            'keahlian_id' => $keahlianid,
            'keahlian_nama' => $keahlian,
            'tingkat' => $tingkat
        ]);

        if ($activity->exists) {
            $keahlianPekerja = Pkeahlian::where('pekerja_id',$id)->get();
            $countKeahlian = $keahlianPekerja->count();

            if($countKeahlian>0){
                $data['status'] = "1";
                $data['message'] = "Berhasil menyimpan data...";
                $data['data'] = $keahlianPekerja->toArray();
                return $data;
            }else{
                $data['status'] = "0";
                $data['message'] = "Data tidak ada...";
                $data['data'] = [];
                return $data;
            }

            
        }else{
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }

    }

    public function updateResumePekerja(Request $request)
    {
        $id = $request->id;
        $filelama = $request->filelama;

        if($filelama != ""){
            File::delete(public_path()."/".$filelama);
        }
        
        $doc = $request->file->getClientOriginalName();
        $array = explode(".",$doc);
        $gabung = "resume-pekerja/".$id.$array[0].Carbon::now()->format('ymd')."RSPK.".end($array);
        $simpan = $id.$array[0].Carbon::now()->format('ymd')."RSPK.".end($array);
        $request->file->move(public_path()."/resume-pekerja",$simpan);

        $update = Pekerja::where('id',$id)->update([
            'resume' => $gabung,
            'resume_updated_at' => Carbon::now()->format('Y-m-d h:i:s')
        ]);

        $pekerja = Pekerja::where('id',$id)->get()->first();
        
        $data['status'] = "1";
        $data['message'] = "Berhasil menyimpan data...";
        $data['detail'] = $pekerja;
        return $data;
        


    }

    public function updatePendidikan(Request $request)
    {
        $id = $request->id;
        $nama = $request->nama;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kualifikasi = $request->kualifikasi;
        $jurusan = $request->jurusan;
        $nilai = $request->nilai;

        
        try {
            $pendidikan = Pendidikan::where('id',$id)->update([
                'institusi' => $nama,
                'bulan_wisuda' => $bulan,
                'tahun_wisuda' => $tahun,
                'kualifikasi' => $kualifikasi,
                'jurusan' => $jurusan,
                'nilai_akhir' => $nilai
            ]);

            $data['status'] = "1";
            $data['message'] = "Berhasil diubah...";
            return $data;
            
            
        } catch (\Illuminate\Database\QueryException $e) {
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }
        


    }

    public function deletePendidikan(Request $request)
    {
        $id = $request->id;
        try {
            $hapus = Pendidikan::where('id',$id)->delete(); 

            $data['status'] = "1";
            $data['message'] = "Berhasil dihapus...";
            return $data;
            
            
        } catch (\Illuminate\Database\QueryException $e) {
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }


    }

    public function getPendidikanByIdPekerja(Request $request)
    {
        $idpekerja = $request->id;

        $pendidikan = Pendidikan::where('pekerja_id',$idpekerja)->get();
        $countPendidikan = $pendidikan->count();

        if ($countPendidikan>0) {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $pendidikan->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data kosong...";
            $data['data'] = [];
            return $data;
        }
        
    }

    public function insertPendidikan(Request $request)
    {
        $idpekerja = $request->id;
        $institusi = $request->institusi;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kualifikasi = $request->kualifikasi;
        $jurusan = $request->jurusan;
        $nilai = $request->nilai;
        
        $activity = Pendidikan::create([
            'pekerja_id' => $idpekerja,
            'institusi' => $institusi,
            'bulan_wisuda' => $bulan,
            'tahun_wisuda' => $tahun,
            'kualifikasi' => $kualifikasi,
            'jurusan' => $jurusan,
            'nilai_akhir' => $nilai
        ]);

        if ($activity->exists) {
            $data['status'] = "1";
            $data['message'] = "Berhasil menambah pendidikan...";
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }


    }

    public function ubahPassword(Request $request)
    {
        $id = $request->id;
        $passlama = $request->passlama;
        $passbaru = $request->passbaru;
        $enkripsi = PASSWORD_HASH($passbaru,PASSWORD_BCRYPT);

        $pekerja = Pekerja::where('id',$id)->get()->first();

        $cek = PASSWORD_VERIFY($passlama, $pekerja->password);

        if ($cek) {
             try {
                $gantipass = Pekerja::where('id',$id)->update([
                    'password' => $enkripsi
                ]); 

                $data['status'] = "1";
                $data['message'] = "Password berhasil diubah...";
                return $data;
                
                
            } catch (\Illuminate\Database\QueryException $e) {
                $data['status'] = "0";
                $data['message'] = "Oops ada kesalahan...";
                return $data;
            }
        }else{
            $data['status'] = "0";
            $data['message'] = "Password lama salah...";
            return $data;
        }


    }

    public function detailPekerja(Request $request)
    {
        $id = $request->id;
        $pekerja = Pekerja::where('id',$id)->get()->first();
        $data['status'] = "1";
        $data['message'] = "Berhasil menyimpan data";
        $data['detail'] = $pekerja;
        return $data;
    }

    public function updateProfilPekerja(Request $request)
    {
        $id = $request->id;
        $namadepan = $request->namadepan;
        $namabelakang = $request->namabelakang;
        $telepon = $request->telepon;
        $alamat = $request->alamat;
        $deskripsi = $request->deskripsi;
        $gajiharapan = $request->gajiharapan;
        $lokasi = $request->lokasi;
        $fotolama = $request->fotolama;

        if($request->foto == null){
            $update = Pekerja::where('id',$id)->update([
                'nama_depan' => $namadepan,
                'nama_belakang' => $namabelakang,
                'nomor_telp' => $telepon,
                'alamat' => $alamat,
                'gaji_harapan' => $gajiharapan,
                'lokasi_kerja' => $lokasi,
                'deskripsi' => $deskripsi
            ]);

            $pekerja = Pekerja::select('id','email','nama_depan','foto')->where('id',$id)->get()->first();

            $data['status'] = "1";
            $data['message'] = "Berhasil menyimpan data";
            $data['detail'] = $pekerja;
            return $data;

        }else{
            if($fotolama != ""){
                File::delete(public_path()."/".$fotolama);
            }
            
            $images = $request->foto->getClientOriginalName();
            $array = explode(".",$images);
            $gabung = "img-pekerja/".$id.$array[0].Carbon::now()->format('ymd')."FPK.".end($array);
            $simpan = $id.$array[0].Carbon::now()->format('ymd')."FPK.".end($array);
            $request->foto->move(public_path()."/img-pekerja",$simpan);

            $update = Pekerja::where('id',$id)->update([
                'nama_depan' => $namadepan,
                'nama_belakang' => $namabelakang,
                'nomor_telp' => $telepon,
                'alamat' => $alamat,
                'foto' => $gabung,
                'gaji_harapan' => $gajiharapan,
                'lokasi_kerja' => $lokasi,
                'deskripsi' => $deskripsi
            ]);

            $pekerja = Pekerja::select('id','email','nama_depan','foto')->where('id',$id)->get()->first();

            $data['status'] = "1";
            $data['message'] = "Berhasil menyimpan data";
            $data['detail'] = $pekerja;
            return $data;
        }


    }

    public function simpleDetail(Request $request)
    {
        $email = $request->email;

        $pekerja = Pekerja::select('id','email','nama_depan','foto','status','jk')->where('email',$email)->get()->first();
        $countPekerja = $pekerja->count();

        if ($countPekerja>0) {
          $data['status'] = "1";
          $data['message'] = "Data tersedia...";
          $data['detail'] = $pekerja->toArray();
          return $data;
        }else{
          $data['status'] = "0";
          $data['message'] = "Data tidak ada...";
          return $data;
        }

    }
}
