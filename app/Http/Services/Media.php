<?php
namespace App\Http\Services;
 use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Media {

    public static function upload(UploadedFile $image,string $folderName):string
    {
       $path =  Storage::disk('images')->put($folderName,$image);

         return  asset('storage/images/' . $path);
    }



    public static function delete(string $image_name,string $folderName):bool
    {

         if(Storage::disk('images')->delete("$folderName/$image_name")){
            return true;
         }
         return false;
    }



}
