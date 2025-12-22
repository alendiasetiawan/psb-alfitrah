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

   public static function waProcessFinalRegistraion($studentName, $branchName, $programName, $admissionYear)
   {
      $message =
         "_*KONFIRMASI DAFTAR ULANG*_\n\n" .

         "Halo admin, saya orang tua/wali dari siswa di bawah ini : \n\n" .

         "Nama Siswa : *$studentName*\n" .
         "Cabang : *$branchName*\n" .
         "Program : *$programName*\n" .
         "Tahun Ajaran : *$admissionYear*\n\n" .

         "bermaksud untuk melakukan pembayaran biaya Daftar Ulang. Mohon bantuan dan arahannya, terima kasih.";

      return $message;
   }

   public static function waFollowUpPayment($studentName, $branchName, $programName, $academicYear, $amount)
   {
      $message =
         "_*PENGINGAT BIAYA PENDAFTARAN*_\n\n" .

         "Kepada ananda *$studentName* dengan data sebagai berikut : \n\n" .

         "Cabang : *$branchName*\n" .
         "Program : *$programName*\n" .
         "Tahun Ajaran : *$academicYear*\n\n" .

         "proses pendaftaran anda masih tertunda. Harap segera melakukan pembayaran sebesar *Rp $amount* agar bisa melanjutkan tahapan proses penerimaan siswa baru. Terima Kasih.\n\n" .

         "_Panitia PSB_\n" .
         "_Al Fitrah Islamic School_";

      return $message;
   }

   public static function waFollowUpBiodata($studentName, $branchName, $programName, $academicYear)
   {
      $message =
         "_*PENGINGAT PENGISIAN BIODATA*_\n\n" .

         "Kepada ananda *$studentName* dengan data sebagai berikut : \n\n" .

         "Cabang : *$branchName*\n" .
         "Program : *$programName*\n" .
         "Tahun Ajaran : *$academicYear*\n\n" .

         "harap segera mengisi *Biodata* anda sebagai syarat untuk bisa mengikuti Tes Seleksi Masuk Al Fitrah. Terima Kasih.\n\n" .

         "_Panitia PSB_\n" .
         "_Al Fitrah Islamic School_";

      return $message;
   }

   public static function waFollowUpAttachment($studentName, $branchName, $programName, $academicYear)
   {
      $message =
         "_*PENGINGAT PENGISIAN BERKAS*_\n\n" .

         "Kepada ananda *$studentName* dengan data sebagai berikut : \n\n" .

         "Cabang : *$branchName*\n" .
         "Program : *$programName*\n" .
         "Tahun Ajaran : *$academicYear*\n\n" .

         "harap segera melampirkan *Berkas* anda sebagai syarat untuk bisa mengikuti Tes Seleksi Masuk Al Fitrah. Terima Kasih.\n\n" .

         "_Panitia PSB_\n" .
         "_Al Fitrah Islamic School_";

      return $message;
   }

   public static function waInvalidAttachment($studentName, $branchName, $programName, $academicYear, $invalidReason)
   {
      $message =
         "_*BERKAS TIDAK VALID*_\n\n" .

         "Kepada ananda *$studentName* dengan data sebagai berikut : \n\n" .

         "Cabang : *$branchName*\n" .
         "Program : *$programName*\n" .
         "Tahun Ajaran : *$academicYear*\n\n" .

         "kami informasikan bahwa berkas yang anda lampirkan *Tidak Valid* dengan alasan sebagai berikut:\n" .
         "_*$invalidReason*_\n".
         "Harap segera melakukan perbaikan, terima kasih.\n\n".

         "_Panitia PSB_\n" .
         "_Al Fitrah Islamic School_";

      return $message;
   }

   public static function waInvalidBiodata($studentName, $branchName, $programName, $academicYear, $invalidReason)
   {
      $message =
         "_*BIODATA TIDAK VALID*_\n\n" .

         "Kepada ananda *$studentName* dengan data sebagai berikut : \n\n" .

         "Cabang : *$branchName*\n" .
         "Program : *$programName*\n" .
         "Tahun Ajaran : *$academicYear*\n\n" .

         "kami informasikan bahwa biodata anda *Tidak Valid* dengan alasan sebagai berikut:\n" .
         "_*$invalidReason*_\n".
         "Harap segera melakukan perbaikan, terima kasih.\n\n".

         "_Panitia PSB_\n" .
         "_Al Fitrah Islamic School_";

      return $message;
   }
}
