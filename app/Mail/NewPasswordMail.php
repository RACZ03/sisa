<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $newPassword;

    public function __construct($username, $newPassword)
    {
        $this->username = $username;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Nueva Contraseña en SISA')
                    ->view('emails.new_password');
    }
}
