<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {

    private $container;

    public function __construct()
    {
        
    }

    public function uploadFile(UploadedFile $file, string $uploads_dir) 
    {
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

        $file->move(
            $uploads_dir,
            $filename
        );

        return $filename;
    }
}