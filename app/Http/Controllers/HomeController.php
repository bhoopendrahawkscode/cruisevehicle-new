<?php

namespace App\Http\Controllers;
use App\Services\CommonService;
use Illuminate\Http\Request;
use App\Models\TagTranslation;
use App\Models\Setting;
use App\Models\CarModel;
use App\Models\Cms;
use App\Constants\Constant;
use App\Models\CmsTranslation;
use Redirect,Session,Cache;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->message               =       __(Constant::ERROR_OCCURRED);
    }

    public function getCms(Request $request)
    {
      
        $reqData = $request->all();
        $cms = Cms::where('slug', $reqData['slug'])->firstOrFail();
        $languageId = CommonService::getLangIdFromLocale();

        $translation = CmsTranslation::where('cms_id', $cms->id)
            ->where('language_id', $languageId)
            ->first();

        return view('cms.show', compact('translation'));
    }

    public function suggestTags(Request $request)
    {
        $language_id = CommonService::getLangIdFromLocale();
        $keyword = $request->keyword;
        if(!empty($keyword)){
        $tags = TagTranslation::where('name', 'like', '%' . $keyword . '%')->where('language_id',$language_id)->pluck('name');
        echo json_encode($tags);
        }else{
            echo json_encode([]);
        }

    }

    public function getModels($brand_id)
    {
        $models = CarModel::where('brand_id', $brand_id)->pluck('name', 'id');
        return response()->json($models);
    }
    public function paginationLimitChange (Request $request)
    {

        $redirectBack       =   Redirect::back();
        $urlComponents      =   parse_url($redirectBack->getTargetUrl());
        if(isset($urlComponents['port'])){
            $urlComponents['host']      = $urlComponents['host'] .":".$urlComponents['port'];
        }
        $row = Setting::findOrFail(1);
        $row['pageLimit']    = $request->status;
        if($row->save()){
            Cache::put('settingsCache',$row);
        }
        $redirectUrl        =   $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
        Session::flash('success', trans("messages.pageSizeChanged"));
        return redirect($redirectUrl);

    }

}
