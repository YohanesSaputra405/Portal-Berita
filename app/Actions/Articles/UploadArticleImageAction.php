<?php

namespace App\Actions\Articles;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadArticleImageAction
{
    /**
     * Upload & simpan featured image artikel ke storage 'public'.
     * Nama file dibuat unik untuk menghindari tabrakan.
     *
     * @param UploadedFile $file
     * @return string Path gambar yang tersimpan (relative path).
     */
    public function execute(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            'articles/images',
            $filename,
            'public'
        );

        return $path;
    }
}
