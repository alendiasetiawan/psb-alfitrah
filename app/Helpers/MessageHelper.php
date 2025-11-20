<?php

namespace App\Helpers;

class MessageHelper
{

   public static function waCheckNumberMessage()
   {
      return "Al Fitrah Islamic School";
   }


   public static function waRegistrationSuccess($otp, $branchName, $studentName, $programName)
   {
      $message =
         "_*VERIFIKASI PENDAFTARAN*_\n\n" .

         "Selamat, ananda *$studentName* telah berhasil menjadi calon siswa *$branchName* program *$programName*. Selanjutnya silahkan melakukan aktivasi akun anda dengan memasukan kode berikut :\n\n" .

         "$otp \n\n" .

         "Kode hanya berlaku untuk 10 menit. \n\n" .

         "_Panitia PSB_\n" .
         "_Al Fitrah Islamic School_";

      return $message;
   }

   public static function waResendOtp($otp)
   {
      $message =
         "_*KODE OTP*_\n\n" .

         "Anda telah melakukan request kode OTP. Silahkan masukan kode di bawah ini :\n\n" .

         "$otp \n\n" .

         "Kode hanya berlaku untuk 10 menit. \n\n" .

         "_Panitia PSB_\n" .
         "_Al Fitrah Islamic School_";

      return $message;
   }

   public static function waProcessFinalRegistraion($studentName, $branchName, $programName, $admissionYear) {
      $message = 
      "_*KONFIRMASI DAFTAR ULANG*_\n\n".

      "Halo admin, saya orang tua/wali dari siswa di bawah ini : \n\n".

      "Nama Siswa : *$studentName*\n".
      "Cabang : *$branchName*\n".
      "Program : *$programName*\n".
      "Tahun Ajaran : *$admissionYear*\n\n".

      "bermaksud untuk melakukan pembayaran biaya Daftar Ulang. Mohon bantuan dan arahannya, terima kasih.";

      return $message;
   }
}
