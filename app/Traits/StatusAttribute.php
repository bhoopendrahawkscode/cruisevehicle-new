<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait StatusAttribute{
    protected function status():Attribute{
        return Attribute::make(
            get:fn(string $value)=>$value=='0'?'Inactive':'Active',
        );
    }
}