<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class LanguageController extends BaseController
{
    public function changeLanguage(Request $request, $locale)
    {
        if (in_array($locale, ['en', 'fr'])) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }
}
