<?php

namespace App\Http\Requests;
use Config;
use Illuminate\Foundation\Http\FormRequest;

class SaveResetPasswordRequest extends FormRequest
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
            'new_password' => $this->passwordValidation,
            'new_password_confirmation' => ['required', 'same:new_password', $this->passwordValidation],
        ];
    }

}
