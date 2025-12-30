<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Services\CommonService;
use App\Services\GeneralService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{

    protected $requiredMin2Max30, $somethingWentWrongMsg;
    protected $imageFolderCoupon, $couponListRoute, $coupon, $offer_type;

    public function __construct()
    {

        $this->middleware('auth:admin');
        $this->couponListRoute = 'admin.coupons.list';
        $version = GeneralService::getSettings('version');
        $this->imageFolderCoupon = Config::get('constants.COUPON_FOLDER');
        $this->coupon = 'Coupon';
        $this->offer_type = Constant::OFFER_TYPE;
        
        View::share([
            'version' => $version,
            'offer_type' => $this->offer_type
        ]);
        $this->requiredMin2Max30 =  Config::get('constants.REQUIRED_MIN_2_MAX_30');
    }
    public function couponList(Request $request)
    {

        $DB                            =   Coupon::query();
        $fieldsToSearch              =   ['*name-code-offer_type*' => '*like-like-like*'];
        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/coupons/status/';
        return view('admin.coupon.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'statusChangeUrl'));
    }

    public function addCoupon()
    {
        $selected_offer_type='';
        return view('admin.coupon.add',compact('selected_offer_type'));
    }

    public function saveCoupon(CouponRequest $request)
    {
        
        try {
            DB::beginTransaction();
           
            $input = $request->all();
            if (!empty($request->image)) {
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderCoupon);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderCoupon,
                    150,
                    100,
                    $fileName
                );

                $input['image'] = $fileName;
            }
            Coupon::create($input);
            DB::commit();
            return redirect()->route($this->couponListRoute)->with('success', $this->coupon . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function UpdateCoupon(CouponRequest $request, Coupon $coupon)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            if (!empty($request->image)) {
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderCoupon);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderCoupon,
                    150,
                    100,
                    $fileName
                );

                $input['image'] = $fileName;
            }
            $coupon->update($input);
            DB::commit();
            return redirect()->route($this->couponListRoute)->with('success', $this->coupon . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function edit(Coupon $coupon)
    {
        $selected_offer_type = $coupon->offer_type;
        return view('admin.coupon.edit', compact('coupon','selected_offer_type'));
    }

    public function validateCouponCode(Request $request)
    {
        if ($request->code) {
            $query = Coupon::where('code', $request->code);
            if ($request->id) {
                $query->where('id', '!=', $request->id);
            }
            $user = $query->first();
            if ($user) {
                return "false";
            } else {
                return "true";
            }
        }
        return "true";
    }

    public function validateCouponName(Request $request){
        if ($request->name) {
            $query = Coupon::where('name', $request->name);
            if ($request->id) {
                $query->where('id', '!=', $request->id);
            }
            $user = $query->first();
            if ($user) {
                return "false";
            } else {
                return "true";
            }
        }
        return "true";
    }


    public function delete(Coupon $coupon)
    {
        try {
            DB::beginTransaction();
            $coupon->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS,  $this->coupon . __('messages.recordDeleted'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function updateStatus($id, $value)
    {
        Coupon::find($id)->update(['status' => $value]);
        Session::flash('success', trans("messages.statusUpdated"));
        return CommonService::redirectStatusChange(Redirect::back());
    }
    public function couponDetails(Coupon $coupon)
    {
        return view('admin.coupon.view',compact('coupon'));
    }
}
