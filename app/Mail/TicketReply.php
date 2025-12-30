<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmailTemplate;
use App\Models\EmailtemplateTranslation;
use App\Models\EmailType;
use App\Models\User;

class TicketReply extends Mailable
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
        $template =	EmailTemplate::where('email_type','support_reply')->first();
        $emilTemplete =	EmailtemplateTranslation::where('emailtemplate_id',$template->id)->where('language_id',1)->first();
        $constants = EmailType::where('type','support_reply')->value('constant');
        $constants = explode(',', $constants);
        $body = str_replace($constants, [$this->data['name'],$this->data['message']], $emilTemplete->email_body);
        return $this->view('email.template')->with(['body' => $body])->subject($emilTemplete->subject);
    }



}

