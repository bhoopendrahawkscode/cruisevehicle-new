<?php

namespace App\Http\Requests;

use App\Constants\Constant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $coupon = $this->coupon;
        return [
            'name*' => [
                'required', 'min:2', 'max:50', ($coupon) ? Rule::unique('coupons', 'name')->ignore($coupon->id) : 'unique:coupons,name',
            ],
            'offer_type' => [
                'required', 'in:flat,percentage'
            ],

            'discount' => ['required','numeric',$this->offer_type=='percentage'?'max:100':null],
            'min_order_value' => [
                'required','numeric', Constant::FLOT_NUMBER_REGEX, Constant::MIN_1,
            ],
            'discount_up_to' => [
                'required','numeric', Constant::FLOT_NUMBER_REGEX, Constant::MIN_1,$this->offer_type==='flat'?'lte:discount':null,
            ],
            'code' => [
                'required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z0-9_-]+$/', ($coupon) ? Rule::unique('coupons', 'code')->ignore($coupon->id) : 'unique:coupons,code'
            ],
            'start_date' => [
                'required', 'date',
            ],
            'expiry_date' => [
                'required', 'date', 'after:start_date'
            ],
            'maximum_uses' => [
                'required', 'integer', Constant::MIN_1,'between:1,100',
            ],
            'single_user_use_limit' => [
                'required', 'integer', Constant::MIN_1,'between:1,100',
            ],

            'description' => [
                'required'
            ],
            'image' => ['image', 'mimes:jpeg,jpg,png', 'max:1024']

        ];
    }

    public function  messages()  {
        return [
            'name.min'=>trans('messages.min2Max30'),
            'name.max'=>trans('messages.min2Max30'),
            'name.unique'=>trans('messages.name_is_already_exists'),
            'discount.max'=>trans('messages.max_up_to_discount'),
            'discount_up_to.min'=>trans('messages.min3'),
            'discount_up_to.numeric'=>trans('messages.notNumberMessage'),
            'code.unique'=>trans('messages.code_is_already_exists'),
            'code.required'=>trans('messages.code_is_required'),
        ];
    }
}
