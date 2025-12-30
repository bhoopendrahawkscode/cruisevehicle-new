<?php
namespace App\Http\Requests;
use App\Constants\Constant;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Session;
class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function attributes()
    {
        return[
            'name.*' => __('messages.name'),
            'description.*' => __('messages.description'),
            'title.*' => __('messages.title'),
            'question.*' => __('messages.question'),
            'answer.*' => __('messages.answer'),
            'designation.*' => __('messages.designation'),
            'meta_title.*' => __('messages.metaTitle'),
            'meta_keywords.*' => __('messages.metaKeywords'),
            'meta_description.*' => __('messages.metaDescription'),
            'body.*' => __('messages.content'),
            'email_body.*' => __('messages.content'),
            'subject.*' => __('messages.subject'),
            'name' => __('messages.name'),
            'email' => __('messages.email'),
            'password' => __('messages.password'),
            'refreshToken' => __('messages.refreshToken'),
            'header' => __('messages.emailHeaderContent'),
            'footer' => __('messages.emailFooterContent'),
            'image' => __('messages.image'),
            'message' => __('messages.message'),
            'subject' => __('messages.subject'),
            'emailBody' => __('messages.emailBody'),
            'metaTitle' => __("metaTitle"),
            'metaKeywords' => __("metaKeywords"),
            'metaDescription' => __("metaDescription"),
            'cmsBody' => __("cmsBody"),
            'user_id' => __("messages.specificUser"),
        ];
    }

    public function messages()
    {
        define("MIN",":min");
        define("MAX",":max");
        define("MIN_FIELD","min");
        define("MAX_FIELD","max");
        define("ATTRIBUTE","attribute");
        return [

            'username.regex' => __(Constant::USERNAME_REGEX,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'full_name.regex' => __(Constant::FULL_NAME_REGEX,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'faqcategories_id.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>__("messages.faqCategory")]),

            'name.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'name.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'name.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'name.*.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'title.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'title.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'title.*.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),


            'meta_title.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_title.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_title.*.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'meta_keywords.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_keywords.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_keywords.*.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'meta_description.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_description.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_description.*.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'body.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'body.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'subject.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'subject.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'subject.*.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'name.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'name.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'name.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'name.unique' => __(Constant::VALIDATION_UNIQUE),

            'email.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'email.email' => __(Constant::VALIDATION_EMAIL,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'password.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'password.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'password.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE] ),
            'password.regex' => __(Constant::PASSWORD_REGEX,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'refreshToken.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'title.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'title.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'title.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'user_type.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'description.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'description.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'description.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'header.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'footer.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'mimes' => __(Constant::VALIDATION_MIMES,[ATTRIBUTE=>Constant::ATTRIBUTE,'values'=>":values"]),
            'image.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'image.uploaded' => __(Constant::VALIDATION_IMAGE_UPLOADED,[ATTRIBUTE=>Constant::ATTRIBUTE]),


            'message.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'message.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'message.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'email_body.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'subject.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'subject.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'subject.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'current_password.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'current_password.password_validation' => __('validations.currentPasswordWrong') ,
            'new_password.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'new_password.different' => __('validations.newPasswordDifferentFromCurrentPassword') ,
            'confirm_password.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'confirm_password.same' => __(Constant::VALIDATION_SAME,[ATTRIBUTE=>Constant::ATTRIBUTE
            ,'other'=>':other']),
            "new_password.regex"					=>	__(Constant::PASSWORD_REGEX,[ATTRIBUTE=>Constant::ATTRIBUTE]),
			"confirm_password.regex"					=>	__(Constant::PASSWORD_REGEX,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            "current_password.regex"					=>	__(Constant::PASSWORD_REGEX,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'new_password.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'new_password.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE] ),
            'confirm_password.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'confirm_password.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE] ),
            'current_password.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'current_password.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE] ),



            'body.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'body.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'body.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'meta_title.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_title.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_title.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'meta_description.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_description.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_description.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'meta_keywords.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_keywords.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),
            'meta_keywords.max' => __(Constant::VALIDATION_MAX,[MAX_FIELD=>MAX,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'loginType.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),

            'question.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'question.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),

            'answer.*.required' => __(Constant::VALIDATION_REQUIRED,[ATTRIBUTE=>Constant::ATTRIBUTE]),
            'answer.*.min' => __(Constant::VALIDATION_MIN,[MIN_FIELD=>MIN,ATTRIBUTE=>Constant::ATTRIBUTE]),






        ];
    }
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            //sendError overRide Pankaj Gupta 2023-12-20
            throw new HttpResponseException(response()->json([
                'success' => 422,
                'message' =>$validator->errors()->first(),
                'data' => new \stdClass()
            ], 422));
        }

        parent::failedValidation($validator);
    }
    protected function withValidator($validator) //PGupta
    {   // PGupta custom validation message
        $errors         =   $validator->errors();
        if(!empty($errors->all())){
            Session::flash('error', __("messages.errorOccurredForm"));
        }
    }
}


