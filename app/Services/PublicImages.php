<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PublicImages
{
    public function store(UploadedFile $file, string $folder = 'images'): string
    {
        $info = getimagesize($file->getRealPath());
        if (! $info || $info[0] > 12000 || $info[1] > 12000 || $info[0] * $info[1] > 40000000) {
            throw ValidationException::withMessages(['image' => 'La imagen excede las dimensiones permitidas.']);
        }
        // Re-encode, remove metadata and limit the longest edge. GD is a deployment requirement.
        $source = match ($info['mime']) {
            'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),'image/png' => imagecreatefrompng($file->getRealPath()),'image/webp' => imagecreatefromwebp($file->getRealPath()),default => false
        };
        if (! $source) {
            throw ValidationException::withMessages(['image' => 'No se pudo leer la imagen.']);
        }
        $scale = min(1, 1920 / max($info[0], $info[1]));
        $dest = imagecreatetruecolor(max(1, (int) round($info[0] * $scale)), max(1, (int) round($info[1] * $scale)));
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        imagecopyresampled($dest, $source, 0, 0, 0, 0, imagesx($dest), imagesy($dest), $info[0], $info[1]);
        ob_start();
        imagewebp($dest, null, 82);
        $bytes = ob_get_clean();
        imagedestroy($source);
        imagedestroy($dest);
        $path = $folder.'/'.Str::uuid().'.webp';
        Storage::disk('public')->put($path, $bytes);

        return $path;
    }
}
