<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pekerja;
use App\Perusahaan;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $id = $request->id;

        try {
            $pekerja = Pekerja::where('id',$id)->update([
                'token' => '0'
            ]);

            $data['status'] = "1";
            $data['message'] = "Token berhasil diubah";
            return $data;
            
            
        } catch (\Illuminate\Database\QueryException $e) {
            $data['status'] = "0";
            $data['message'] = "Oops ada kesalahan...";
            return $data;
        }

    }

    public function Masuk(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $checkPekerja = Pekerja::where('email',$email)->get();
        $countPekerja = $checkPekerja->count();
        $checkPerusahaan = Perusahaan::where('email',$email)->get();
        $countPerusahaan = $checkPerusahaan->count();

        if ($email == "admin@gmail.com" && $password == "admin123"){
                $data['status'] = "1";
                $data['message'] = "Berhasil masuk...";
                $data['role'] = "admin";
                $data['id'] = 0;
                return $data;
        }


        if ($countPekerja>0) {
            $token = $request->token;
            $pekerja = $checkPekerja->toArray();
            $cek = password_verify($password,$pekerja[0]['password']);
            if ($cek) {
                $updateToken = Pekerja::where('email',$email)->update([
                    'token' => $token
                ]);
                $data['status'] = "1";
                $data['message'] = "Berhasil masuk...";
                $data['role'] = "pekerja";
                $data['id'] = $pekerja[0]["id"];
                return $data;
            }else{
                $data['status'] = "0";
                $data['message'] = "Oops password anda salah...";
                return $data;
            }
        }
        else if($countPerusahaan > 0 ){
            $perusahaan = $checkPerusahaan->toArray();
            $cek = password_verify($password,$perusahaan[0]['password']);
            if ($cek) {
                $data['status'] = "1";
                $data['message'] = "Berhasil masuk...";
                $data['role'] = "perusahaan";
                $data['id'] = $perusahaan[0]["id"];
                return $data;
            }else{
                $data['status'] = "0";
                $data['message'] = "Oops password anda salah...";
                return $data;
            }
        }else{
            $data['status'] = "0";
            $data['message'] = "Maaf anda belum terdaftar...";
            return $data;
        }
    }

    public function DaftarPerusahaan(Request $request)
    {
        $email = $request->email;
        $nama = $request->nama;
        $password = $request->password;
        $telepon = $request->telepon;
        $enkripsi = password_hash($password, PASSWORD_BCRYPT);
        $cekemail = $this->CheckUser($email);
        if ($cekemail>0) {
            $data['status'] = "0";
            $data['message'] = "Email sudah digunakan...";
            return $data;
        }else{
            $insert = Perusahaan::create([
                'email' => $email,
                'nama' => $nama,
                'nomor_telp' => $telepon,
                'password' => $enkripsi
            ]);
            if ($insert->exists) {
                $data['status'] = "1";
                $data['message'] = "Berhasil mendaftar...";
                return $data;
            }else {
                $data['status'] = "0";
                $data['message'] = "Oops ada kesalahan...";
                return $data;
            }
        }
    }

    public function DaftarPekerja(Request $request)
    {
      $jk = $request->jk;
      $email = $request->email;
      $namadepan = $request->namadepan;
      $namabelakang = $request->namabelakang;
      $password = $request->password;
      $telepon = $request->telepon;
      $enkripsi = password_hash($password, PASSWORD_BCRYPT);
      $cekemail = $this->CheckUser($email);
      if ($cekemail>0) {
          $data['status'] = "0";
          $data['message'] = "Email sudah digunakan...";
          return $data;
      }else{
          $insert = Pekerja::create([
            'email' => $email,
            'nama_depan' => $namadepan,
            'nama_belakang' => $namabelakang,
            'password' => $enkripsi,
            'jk' => $jk,
            'status' => '1',
            'nomor_telp' => $telepon
          ]);
          if ($insert->exists) {
              $data['status'] = "1";
              $data['message'] = "Berhasil mendaftar...";
              return $data;
          }else {
              $data['status'] = "0";
              $data['message'] = "Oops ada kesalahan...";
              return $data;
          }
      }
    }

     public function CheckUser($email)
     {
       $pekerja = Pekerja::where('email',$email)->get();
       $countPekerja = $pekerja->count();

       $perusahaan = Perusahaan::where('email',$email)->get();
       $countPerusahaan = $perusahaan->count();

       $count = $countPekerja + $countPerusahaan;

       if ($count > 0) {
         return 1;
       }else {
         return 0;
       }
     }


    public function index(Request $request)
    {

      $pekerja = Pekerja::all();
      $count = $pekerja->count();
      // $cek = $this->CheckUser($request->email);

      if ($count > 0) {
        $data = [
          'status' => '1',
          'message' => 'Data tersedia',
          'data' => $pekerja->toArray()
        ];
      }else {
        $data = [
          'status' => '0',
          'message' => 'Data tidak ada',
          'data' => null
        ];
      }


      return $data;

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
        //
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
