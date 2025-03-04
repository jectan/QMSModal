<?php

namespace App\Services;

use Exception;
use Image;
use Illuminate\Support\Facades\File;

class FileService
{
    /**
     * Upload images on given folder
     * 
     * @param string $image_data
     * @param string $folder_name
     * @param string $prefix
     * @param string $suffix
     * 
     * @return bool
     * 
     */
    public function uploadImage($image_data, $extension, $folder_name, $prefix = null, $suffix = null)
    {
        try {
            $path = public_path().'/images/'.$folder_name;
            
            if(!File::exists($path)) {
                File::makeDirectory($path, $mode = 0755, true, true);
            }
            
            $image = ($prefix ? $prefix.'-' : '') . time() . ($suffix ? '-' . $suffix : '') . '.'. $extension;
            Image::make($image_data)->save(public_path('images/'.$folder_name.'/') . $image);
            return $image;
        } catch (Exception $ex) {
            // dd($ex);
            return false;
        }
    }

    /**
     * Upload images on given folder
     * 
     * @param string $image
     * @param string $folder_name
     * 
     * @return bool
     */
    public function deleteImage($image, $folder_name)
    {
        try {
            File::delete('images/'.$folder_name.'/' . $image);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
