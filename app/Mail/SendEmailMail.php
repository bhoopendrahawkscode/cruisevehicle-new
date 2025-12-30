<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmailTemplate;
use App\Models\EmailType;
use App\Services\GeneralService;
class SendEmailMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param  string  $username
     * @param  string  $link
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

        $template = EmailTemplate::where('email_type',$this->data['email_type'])
        ->with('emailtemplate_translation')->first();

        $emailBody = null;
        $subject = null;

       if(isset($template['emailtemplate_translation'])){
            $emailBody =  $template['emailtemplate_translation']['email_body'];
            $subject =  $template['emailtemplate_translation']['subject'];
       }

        $constants = EmailType::where('type',$this->data['email_type'])->value('constant');
        $constants = explode(',', $constants);
        $body = str_replace($constants, $this->data['replaceData'], $emailBody);
        $subject = str_replace($constants, $this->data['replaceData'], $subject);



        $header='';
        $footer='';
        if($template->global_header_footer==1){
            $header=GeneralService::getSettings("header");
            $footer=GeneralService::getSettings("footer");
        }
        return $this->view('email.template')->with(['body' => $body,'header'=>$header,'footer'=>$footer])->subject($subject);
    }
}
