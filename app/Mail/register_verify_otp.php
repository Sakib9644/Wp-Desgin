<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class register_verify_otp extends Mailable
{
    use Queueable, SerializesModels;

    public $form;
    public $name;
    public $messageBody; // Renamed from $mess for clarity
    public $subject;
    public $otp;

    public $tos;

    // Add any additional properties as needed
    public function __construct($form,$subject,$otp, $to)
    {
        $this->form = $form;
        $this->subject = $subject;
        $this->otp = $otp;
        $this->tos = $to;
    }
public function build()
{
    $otp = $this->otp; 
    $tos =  $this->to;
    return $this->from($this->form, $this->name)
                ->subject($this->subject)
                ->view('backend.helpers.register-otp-verify', compact('otp','tos'));
}

}
