<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TimezoneConvert;


class BaseModel extends Model
{
    use TimezoneConvert;
    public  function getTranslationAttributes(){
        return $this->translationsAttributes;
    }
}
