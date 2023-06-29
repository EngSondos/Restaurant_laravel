<?php
namespace App\Http\Services;
 use Illuminate\Http\UploadedFile;
 use Illuminate\Support\Facades\Storage;



class Media {

    public static function upload(UploadedFile $image,string $folderName):string
    {

         $newImageName = $image->hashName();
         Storage::disk('images')->put($folderName,$image);
         $url = Storage::url($folderName . '/' . $newImageName);
         return $url;

    }



    public static function delete(string $path):bool
    {

     $path = str_replace('/storage', '', $path);
     if (Storage::disk('images')->delete($path)) {
         return true;
     }
     return false;
    }



}