<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class MediaUploadService
{
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        $cloudUrl = (string) config('cloudinary.cloud_url', '');

        if ($cloudUrl !== '') {
            $cloudinary = new Cloudinary($cloudUrl);
            $result = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                ['folder' => $folder]
            );

            return (string) $result['secure_url'];
        }

        return (string) $file->store($folder, 'public');
    }
}
