<?php

namespace App\Mail;

use App\Models\Configuration;
use App\Models\EmailData;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendImagesToUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $subject;
    public $event_title;
    public $url;
    public $order;
    public $event_code;
    public $order_number;
    public $username;
    public $mail;

    /**
     * Create a new message instance.
     *
     * @param string $username
     * @param Order $order
     * @param string $url
     */
    public function __construct(string $username,
                                Order $order,
                                string $url)
    {
        $this->url = $url;
        $this->event_title = $order->event->title;
        $this->order = $order;
        $this->event_code = $order->event->event_code;
        $this->order_number = $order->order_number;
        $this->username = $username;
        $this->mail = Configuration::getValue("EMAIL");

        $this->subject = $this->event_title . " Images";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send_images_to_user_email');
    }
}
