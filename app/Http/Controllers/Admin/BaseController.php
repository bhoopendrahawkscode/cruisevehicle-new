<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Routing\Controller;
use App\Services\GeneralService;
use App\Services\MetaService;
use Illuminate\Support\Facades\Route;

/**
* Base Controller
*
* Add your methods in the class below
*
* This is the base controller called every time on every request
*/
class BaseController extends Controller {

    public function __construct()
    {

        $currentRoute = Route::currentRouteName();
        $languages = GeneralService::getLanguages();
        $version = GeneralService::getSettings('version');
        $meta_info = MetaService::getMeta($currentRoute,$languages);
        \View::share([
            'languages' =>  $languages,
            'version' => $version,
            'meta_info' => $meta_info

        ]);

    }

}
