<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;

use Illuminate\Http\Request;

use Response,Hash,DB,App,Config,Validator,Session,Redirect,URL,Auth,File,stdClass;

class CronController extends Controller {

	public function __construct()
    {
		$this->date = date('Y-m-d');
	}


	public function everyDay(){
		try{
			$this->__membershipExpiry();
		}catch (\Exception $exception) {
                echo $exception->getMessage();
        }
		
		//Subscription plan expire
		User::where('subscription_plan','>',0)->where('expire_date','<',$this->date)
			->update(['subscription_plan'=>0,'expire_date'=>Null]);

		die('Done');
	}

}