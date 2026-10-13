<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMahasiswa extends Mailable
{
    public function __construct(public string $token) {}

    public function build(): self
    {
        return $this->subject('Reset Password Sinergi Markandeya')
            ->view('emails.reset-password-mahasiswa', [
                'resetUrl' => route('reset-password', $this->token),
            ]);
    }
}
