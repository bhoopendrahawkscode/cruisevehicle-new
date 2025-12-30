<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmailTemplate;
use App\Models\EmailType;
use App\Models\User;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        $template = EmailTemplate::where('email_type','forgot_password')->first();
        $constants = EmailType::where('type','forgot_password')->value('constant');
        $constants = explode(',', $constants);
        
        $replace_with = [$this->data['username'],$this->data['link']];

        $body = str_replace($constants, $replace_with, $template->email_body);     
        echo $body;
        return $this->view('email.template')->with(['body' => $body]);
    }
}

