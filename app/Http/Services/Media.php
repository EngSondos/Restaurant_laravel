<?php
namespace App\Http\Services;
 use Illuminate\Http\UploadedFile;


class Media {

    public static function upload(UploadedFile $image,string $dir):string
    {

         $newImageName = $image->hashName();
         $image->move(public_path($dir),$newImageName);
         return $newImageName;
    }



    public static function delete(string $path):bool
    {

         if(file_exists($path)){
            unlink($path);
            return true;
         }
         return false;
    }



}