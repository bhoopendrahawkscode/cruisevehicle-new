<?php

namespace App\Services;

use App\Models\Meta;
use League\CommonMark\Extension\CommonMark\Parser\Inline\EscapableParser;

class MetaService
{
    public static function getMeta(string $routeName=null, object $languages)
    {

if(!empty($routeName)){


        $data = Meta::where('url', $routeName)->with('meta_translations')->first();
        if (empty($data)) {
            return $data;
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        if (!empty($data['meta_translations'])) {
            foreach ($data['meta_translations'] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        $data = $dataReArrange;
        $meta_tags = '';
        foreach ($languages as $languageRow) {
            $meta_title = $data['meta_title'][$languageRow->id];
            $meta_description =$data['meta_description'][$languageRow->id];
            $meta_tags .= '<meta name="'.$meta_title.'" content="'.$meta_description.'"/>'."\n";
        }
        return $meta_tags;

    }
    return '';
    }
}
