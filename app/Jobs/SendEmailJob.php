<?php

namespace App\Jobs;

use App\Mail\SendEmailMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
class SendEmailJob implements ShouldQueue
{
    public $username;
    public $link;

    public $data;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $email = new SendEmailMail($this->data);
            Mail::to($this->data['email'])->send($email);
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
        }
    }
}
