<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perusahaan;
use App\Keahlian;
use App\Lowongan;
use App\Lkeahlian;
use App\Pendaftar;
use App\Pekerja;
use App\Pkeahlian;
use App\Notifikasi;
use Carbon\Carbon;
use File;


class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function gantiPassword(Request $request)
    {
        $id = $request->id;
        $passlama = $request->passlama;
        $passbaru = $request->passbaru;
        $enkripsi = PASSWORD_HASH($passbaru,PASSWORD_BCRYPT);
        
        $perusahaan = Perusahaan::where('id',$id)->get()->first();

        $cek = PASSWORD_VERIFY($passlama, $perusahaan->password);
        if($cek)
        {
            try {
                $gantipass = Perusahaan::where('id',$id)->update([
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
        
        // return $perusahaan->password;


    }
    

    public function updateStatusPendaftar(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        try {
            $pendaftar = Pendaftar::where('id',$id)->update([
                'status' => $status
            ]); 

            if($status == 1){
                $data['status'] = "1";
                $data['message'] = "Pendaftar telah diterima...";
                return $data;
            }else{
                $data['status'] = "1";
                $data['message'] = "Pendaftar telah ditolak...";
                return $data;
            }

            
            
        } catch (\Illuminate\Database\QueryException $e) {
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }
    }

    public function getDetailPendaftar(Request $request)
    {
        $id = $request->id;
        
        $pekerja = Pekerja::with(array(
                         'pkeahlian'=>function($query){
                            $query->select('id','pekerja_id','keahlian_id','keahlian_nama','tingkat','created_at','updated_at');
                         }
                    ))->where('id',$id)->get();
        $pekerjaCount = $pekerja->count();

        if ($pekerjaCount>0) {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['detail'] = $pekerja->first();
            return $data;
        }else{
             $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan";
            $data['detail'] = null;
            return $data;
        }


    }

    public function getPendaftarByIdLowongan(Request $request)
    {

        $id = $request->id;
        $pendaftar = Pendaftar::with(array(
                         'pekerja'=>function($query){
                            $query->select('id','email','nama_depan','nama_belakang','jk','nomor_telp','alamat','foto','gaji_harapan');
                         },
                         'perusahaan'=>function($query){
                            $query->select('id','email','nama','nomor_telp','alamat','foto','informasi');
                         },
                         'lowongan'=>function($query){
                            $query->select('id','judul','deskripsi','requirement','waktu_kerja','range_gaji1','range_gaji2','created_at','updated_at');
                         }

                    ))->where('lowongan_id',$id)->orderBy('status','desc')->get();

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

    public function updateLowonganPerusahaan(Request $request)
    {
        $id = $request->id;
        $deskripsi = $request->deskripsi;
        $requirement = $request->requirement;
        $waktu = $request->waktu;
        $gaji1 = $request->gaji1;
        $gaji2 = $request->gaji2;
        $keahlian = $request->keahlian;
        $lokasi = $request->lokasi;
        
        try {
            $lowongan = Lowongan::where('id',$id)->update([
                'deskripsi' => $deskripsi,
                'requirement' => $requirement,
                'waktu_kerja' => $waktu,
                'range_gaji1' => $gaji1,
                'range_gaji2' => $gaji2,
                'lokasi' => $lokasi
            ]); 

            $deleteKeahlian = Lkeahlian::where('lowongan_id',$id)->delete();

            $arrayKeahlian = explode(",",$keahlian);

            for($i=0; $i < sizeof($arrayKeahlian) ;$i++ ){
                $keahlianDb = Keahlian::where('nama',$arrayKeahlian[$i])->get()->toArray();
                $insert = Lkeahlian::create([
                    'lowongan_id' => $id,
                    'keahlian_id' => $keahlianDb[0]['id'],
                    'keahlian_nama' => $keahlianDb[0]['nama']
                ]);
            }




            $data['status'] = "1";
            $data['message'] = "Berhasil menyimpan data...";
            return $data;
            
        } catch (\Illuminate\Database\QueryException $e) {
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }


    }

    public function detailPerusahaan(Request $request)
    {
        $email = $request->email;

        $perusahaan = Perusahaan::select()->where('email',$email)->get();
        $countPerusahaan = $perusahaan->count();

        if ($countPerusahaan>0) {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $perusahaan->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak ada...";
            return $data;
        }

    }

    public function updateProfilPerusahaan(Request $request)
    {
        // $id = $request->id;
        $id = $request->id;
        $nama = $request->nama;
        $telp = $request->telp;
        $alamat = $request->alamat;
        $informasi = $request->informasi;
        $fotolama = $request->fotolama;

        if ($request->foto == null ) {
            $update = Perusahaan::where('id',$id)->update([
                'nama'=> $nama,
                'nomor_telp' => $telp,
                'alamat' => $alamat,
                'informasi' => $informasi            
                ]);

            $perusahaan = Perusahaan::select('id','email','nama','foto')->where('id',$id)->get()->toArray();

            $data['status'] = "1";
            $data['message'] = "Berhasil menyimpan data";
            $data['data'] = $perusahaan;

            return $data;
        }else{
            if($fotolama != ""){
                File::delete(public_path()."/".$fotolama);
            }
            
            $images = $request->foto->getClientOriginalName();
            $array = explode(".",$images);
            $gabung = "img-perusahaan/".$id.$array[0].Carbon::now()->format('ymd')."FP.".end($array);
            $simpan = $id.$array[0].Carbon::now()->format('ymd')."FP.".end($array);
            $request->foto->move(public_path()."/img-perusahaan",$simpan);

            $update = Perusahaan::where('id',$id)->update([
                'foto' => $gabung,
                'nama'=> $nama,
                'nomor_telp' => $telp,
                'alamat' => $alamat,
                'informasi' => $informasi            
                ]);

            $perusahaan = Perusahaan::select('id','email','nama','foto')->where('id',$id)->get()->toArray();

            $data['status'] = "1";
            $data['message'] = "Berhasil menyimpan data";
            $data['data'] = $perusahaan;

            return $data;
        }


        
    }

    public function updateStatusLowongan(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD']=='POST') {
            $id = $request->id;
            $status = $request->status;
            
            if($id != null){
                $update = Lowongan::where('id',$id)->update(['status' => $status]);

                if($status == 0){
                    $data['status'] = "1";
                    $data['message'] = "Lowongan berhasil ditutup...";
                    return $data;
                }else if ($status ==1) {
                    $data['status'] = "1";
                    $data['message'] = "Lowongan berhasil dibuka...";
                    return $data;
                }else{
                    $data['status'] = "1";
                    $data['message'] = "Lowongan telah menjadi sejarah...";
                    return $data;
                }

                
            }else{
                $data['status'] = "0";
                $data['message'] = "Ops ada kesalahan...";

                return $data;
            }
        }else{
            $data['status'] = "0";
            $data['message'] = "Ops ada kesalahan...";

            return $data;
        }
    }

    public function detailLowongan(Request $request)
    {
        
        if ($_SERVER['REQUEST_METHOD']=='POST') {
            $id = $request->id;
            
            if($id != null){
                $lowongan = Lowongan::with(array('lkeahlian'=>function($query){
                    $query->select('id','lowongan_id','keahlian_id','keahlian_nama','created_at','updated_at');
                }))->where('id',$id)->get();

                $lowonganArray = $lowongan->toArray();
                $data['status'] = "1";
                $data['message'] = "Data tersedia...";
                $data['data'] = $lowonganArray;
                return $data;
            }else{
                $data['status'] = "0";
                $data['message'] = "Ops ada kesalahan...";

                return $data;
            }
        }else{
            $data['status'] = "0";
            $data['message'] = "Ops ada kesalahan...";

            return $data;
        }
        
    }

    public function getCountKeahlian()
    {
        $keahlian = Keahlian::all();
        $countKeahlian = $keahlian->count();
        $data['size'] = $countKeahlian;
        return $data;
    }

    public function getKeahlian()
    {
        $keahlian = Keahlian::orderBy('nama','asc')->get();
        $countKeahlian = $keahlian->count();
        if ($countKeahlian>0) {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $keahlian->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak tersedia";
            $data['data'] = null;
            return $data;
        }
    }

    public function lowonganPunyaPerusahaan(Request $request)
    {
        $perusahaanId = $request->perusahaanId;
        $status = $request->status;
        $lowongan = Lowongan::where(['perusahaan_id' => $perusahaanId,
                                    'status' => $status])->orderBy('id','desc')->get();
        $countLowongan = $lowongan->count();
        if ($countLowongan > 0) {
            $data['status'] = "1";
            $data['message'] = "Data tersedia...";
            $data['data'] = $lowongan->toArray();
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Data tidak ada...";
            $data['data'] =[];
            return $data;
        }

    }

    public function buatLowongan(Request $request)
    {   
        $perusahaanId = $request->perusahaanId;
        $judul = $request->judul;
        $deskripsi = $request->deskripsi;
        $requirement = $request->requirement;
        $waktu = $request->waktu;
        $gaji1 = $request->gaji1;
        $gaji2 = $request->gaji2;
        $lokasi = $request->lokasi;
        $keahlian = $request->keahlian;
        $arrayKeahlianId = [];
        $arrayIdPekerja = [];
        $activity = Lowongan::create([
            'perusahaan_id' => $perusahaanId,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'requirement' => $requirement,
            'waktu_kerja' => $waktu,
            'range_gaji1' => $gaji1,
            'range_gaji2' => $gaji2,
            'status' => "1",
            'lokasi' => $lokasi
        ]);
        $arrayKeahlian = explode(",",$keahlian);
        //insert lowongan keahlian
        for($i=0; $i < sizeof($arrayKeahlian) ;$i++ ){
            $keahlianDb = Keahlian::where('nama',$arrayKeahlian[$i])->get()->first();
            $insert = Lkeahlian::create([
                'lowongan_id' => $activity->id,
                'keahlian_id' => $keahlianDb['id'],
                'keahlian_nama' => $keahlianDb['nama']
            ]);
            $arrayKeahlianId[$i] = $keahlianDb['id'];
        }

        //cari keahlian pekerja
        for($i = 0 ; $i < sizeof($arrayKeahlianId);$i++)
        {
            $pekerja = Pkeahlian::where('keahlian_id',$arrayKeahlianId[$i])->get();
            $countPekerja = $pekerja->count();
            if($countPekerja>0){
                $arrayPekerja = $pekerja->toArray();
                for($j = 0; $j< $countPekerja ; $j++){
                    $arrayIdPekerja[sizeOf($arrayIdPekerja)] = $arrayPekerja[$j]['pekerja_id'];
                }
            }
        }

        $hasilIdPekerja = array_unique($arrayIdPekerja);
        $finalArray = array_values($hasilIdPekerja);
        // var_dump($finalArray);
        $tokenArray = [];

        //insert ke table notif
        if(sizeOf($finalArray)>0){
            
                for($i = 0 ; $i<sizeOf($finalArray);$i++){
                    $token = Pekerja::where('id',$finalArray[$i])->get()->first();
                    if($token->status == "1"){
                        $insertNotif = Notifikasi::create([
                            'pekerja_id' => $finalArray[$i],
                            'perusahaan_id' => $activity->perusahaan_id,
                            'lowongan_id' => $activity->id,
                            'status' => '0'
                        ]);
                        $tokenArray[sizeOf($tokenArray)] = $token->token;
                    }
                }
        }

        // //buat array jadi string token
        if(sizeOf($tokenArray)==1){
            $stringToken = '["'.$tokenArray[0].'"]';
        }else{
            $stringToken = '["'. implode('","',$tokenArray).'"]';
        }
        $body = $activity->judul;
        if(sizeOf($tokenArray)>0){
            $this->pushNotification($stringToken,$body);
        }
        if ($activity->exists) {
            $data['status'] = "1";
            $data['message'] = "Berhasil membuat lowongan...";
            return $data;
        }else{
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }   
    }

    public function simpleDetail(Request $request)
    {
      $email = $request->email;

      $perusahaan = Perusahaan::select('id','email','nama','foto')->where('email',$email)->get();
      $countPerusahaan = $perusahaan->count();

      if ($countPerusahaan>0) {
        $data['status'] = "1";
        $data['message'] = "Data tersedia...";
        $data['data'] = $perusahaan->toArray();
        return $data;
      }else{
        $data['status'] = "0";
        $data['message'] = "Data tidak ada...";
        return $data;
      }

    }

    public function pushNotification($token,$body){

        $curl = curl_init();
        // $tokenuser = $tokenArray;
        $tokenuser = ''.$token.'';
        $authKey = "key=AAAAHnBKhAI:APA91bEKVdFt-oc3ZSZHDUF8-gb9IyUI4NN3-YO_FacwPFc1rMW42HMkircoeMSJmoQYE9yD9eh4ZJ3ErRE2gB7TFn1g3iGNjk2hfuUlmyvmSdHiMeXz8syyfuFGuacE0bQ-eoa_Gj_u";

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
                "body":"'.$body.'"
            }
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-type: application/json",
                "Authorization: ".$authKey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

    }

    public function pushNotif($json_data){
        $data = json_encode($json_data);
        //FCM API end-point
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAHnBKhAI:APA91bEKVdFt-oc3ZSZHDUF8-gb9IyUI4NN3-YO_FacwPFc1rMW42HMkircoeMSJmoQYE9yD9eh4ZJ3ErRE2gB7TFn1g3iGNjk2hfuUlmyvmSdHiMeXz8syyfuFGuacE0bQ-eoa_Gj_u';
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        echo $result;
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);

        
    }


   
}
