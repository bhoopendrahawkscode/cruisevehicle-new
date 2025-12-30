<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;
use App\Models\EmailType;

class ContactUs extends Mailable
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
        $template       = EmailTemplate::where('email_type','contact_us')->first();
        $constants      = EmailType::where('type','contact_us')->value('constant');
        $constants      = explode(',', $constants);
        $replace_with   = [$this->data['first_name'],$this->data['last_name'], $this->data['mobile'], $this->data['email'], $this->data['description']];
        $body           = str_replace($constants, $replace_with, $template->email_body);
        return $this->view('email.template')->with(['body' => $body]);
    }
}
