<?php

namespace App\Mail;

class Mail
{
    public function __construct(public string $email, public string $subject, public string $body)
    {
    }
}
