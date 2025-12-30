<?php
namespace App\Http\Requests;
use App\Http\Requests\FormRequest;
use Session;

class SendNotificationRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user_id='';
        if(isset($_REQUEST['user_id'])){
        $user_id=$_REQUEST['user_id'];
            }
        Session::put('selected_user_type', $this->request->get('user_type'));
        Session::put('selected_user_id',$user_id);
        $validations =  [
            'title' => 'required|min:2|max:200',
            'user_type' => 'required',
            'description'  => 'required|min:2|max:350'
        ];
        if(isset($_REQUEST['user_type']) && $_REQUEST['user_type'] == 2){
            $validations = array_merge($validations,['user_id' => 'required']);
        }
        return $validations;
    }
}




