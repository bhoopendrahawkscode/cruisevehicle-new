<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Constants\Constant;
use App\Models\Setting;
use App\Http\helpers;

class ImageService
{

    public static function manipulateImage($operation, $image, $outputImagePath=null,
    $width=null, $height=null,$fileName=null)
    {
        $name = $fileName;
        $manager = new ImageManager(new Driver());
        $thumbnail = $manager->read($image);

    switch ($operation) {
            case 'resize':
                self::resizeImage($thumbnail,$name, $outputImagePath, $width, $height);
                break;

            case 'crop':
                self::cropImage($thumbnail,$name, $outputImagePath,$width, $height);
                break;

            case 'resizeWithPad':
                self::resizeImageWithPad($thumbnail,$name, $outputImagePath, $width, $height);
                break;

            default:
                // Handle other cases or throw an exception
                break;
        }
        return "Image manipulated using $operation successfully.";
    }

    public static function resizeImage($thumbnail,$name,$outputImagePath, $width, $height)
    {
        $thumbnail->resize($width, $height);
        $thumbnailName = 'thumb_'.$name;
        $storage = Storage::disk(env('FILESYSTEM_DISK'));
        $storage->put($outputImagePath.$thumbnailName, (string)$thumbnail->encode());
    }

    public static function cropImage($thumbnail,$name,$outputImagePath, $width, $height)
    {

        $thumbnail->scale($width, $height);
        $thumbnailName = 'thumb_'.$name;
        $storage = Storage::disk(env('FILESYSTEM_DISK'));
        $storage->put($outputImagePath.$thumbnailName, (string)$thumbnail->encode());
    }

    public static function resizeImageWithPad($thumbnail,$name,$outputImagePath, $width, $height )
    {
        $thumbnail->pad($width, $height, 'fff');
        $thumbnailName = 'thumb_'.$name;
        $storage = Storage::disk(env('FILESYSTEM_DISK'));
        $storage->put($outputImagePath.$thumbnailName, (string)$thumbnail->encode());
    }

    public static function fileUploadImage($image, $oldfile, $path)
     {

        $storage = Storage::disk(env('FILESYSTEM_DISK'));
        $name = \Str::uuid() .'.'. $image->getClientOriginalExtension();

        $storage->put($path.$name, file_get_contents($image));

        /**
         * Image Delete
         */
        if ($oldfile != null) {
            $storage->delete([$path.$oldfile]);
            $storage->delete([$path.'thumb_'.$oldfile]);
        }
        return $name;
    }

    public static function deleteImage($path,$oldFile)
     {
        $storage = Storage::disk(env('FILESYSTEM_DISK'));
        if ($oldFile != null) {
            $storage->delete([$path.$oldFile]);
            $storage->delete([$path.'thumb_'.$oldFile]);
        }
    }

    public static function exists($name){

        return Storage::disk(env('FILESYSTEM_DISK'))->exists($name);
    }

    public static function getImageUrl($name){

      return Storage::disk(env('FILESYSTEM_DISK'))->url($name);
    }

    public static function removeVideoFromS3($fileName){

        $fileName = parse_url($fileName, PHP_URL_PATH);

        $fileName = basename($fileName);

        return Storage::disk(env('FILESYSTEM_DISK'))->delete($fileName);
    }


}
