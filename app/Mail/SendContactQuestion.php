<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactQuestion extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $fullname;
    public $question;

    /**
     * Create a new message instance.
     *
     * @param $email
     * @param $fullname
     * @param $question
     */
    public function __construct($email, $fullname, $question)
    {
        $this->email =  $email;
        $this->fullname =  $fullname;
        $this->question =  $question;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact', [
            "email" => $this->email,
            "fullname" => $this->fullname,
            "question" => $this->question,
        ])
            ->subject("Contact Email");
    }
}
