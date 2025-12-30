<?php

namespace App\Services;
use App\Constants\Constant;

class ModelService
{
    public static function getUrlAttribute($thisRef){
        if($thisRef->type == "1"){
            return Constant::S3_URL. $thisRef->attributes['url'];
        }else{
            if (
                ImageService::exists(Constant::ATTACHMENT_FOLDER .$thisRef->attributes['url'])
                && $thisRef->attributes['url'] != null
            ) {
                $url = ImageService::getImageUrl(Constant::ATTACHMENT_FOLDER . $thisRef->attributes['url']);
            } else {

                $url = "";
            }
            return $url;
        }
    }

    public static function deleteAttachment($thisRef){
        if(!empty($thisRef->fileName)){
            if($thisRef->fileName['type'] == 1){
                ImageService::deleteS3($thisRef->fileName['url']);
            }else{
                ImageService::deleteImage(Constant::ATTACHMENT_FOLDER,$thisRef->fileName['url']);
            }
        }
    }

}

