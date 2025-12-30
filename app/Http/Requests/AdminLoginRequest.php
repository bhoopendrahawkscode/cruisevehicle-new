<?php

namespace App\Http\Requests;
use Config;
use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    protected $passwordValidation;
    protected $validPasswordErrorMessage;
    public function __construct()
    {

		$this->passwordValidation = Config::get('constants.PASSWORD_VALIDATION');
        $this->validPasswordErrorMessage = trans("messages.ValidPasswordErrorMessage");
    }
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|email|max:50',
            'password' => $this->passwordValidation,
        ];
    }

}
