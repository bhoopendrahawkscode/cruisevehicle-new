<?php

namespace App\Http\Requests;
use Config;
use Illuminate\Foundation\Http\FormRequest;

class SendPasswordRequest extends FormRequest
{
    protected $passwordValidation;
    public function __construct()
    {

		$this->passwordValidation = Config::get('constants.PASSWORD_VALIDATION');
    }
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

}
