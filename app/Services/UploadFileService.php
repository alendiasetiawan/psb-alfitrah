<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class UploadFileService {

    public function compressAndSavePhoto($photo, $folderName = "photo") {
        try {
            $extension = $photo->getClientOriginalExtension();
            $year = date('Y');
            $month = date('m');

            $structureFolder = $folderName . '/' . $year . '/' . $month;
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = "{$structureFolder}/{$filename}";

            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($photo->getRealPath())
                    ->encode(new JpegEncoder(quality: 50));

                Storage::disk('public')->put($path, (string) $image);
            } else {
                throw new \Exception("Tipe file tidak didukung: $extension");
            }
        } catch (\Throwable $th) {
            throw new \Exception("Gagal kompress dan upload");
        }

        return $path;
    }
}
