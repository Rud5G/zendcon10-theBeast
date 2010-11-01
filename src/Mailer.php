<?php
class Mailer
{
    public function sendMail($email, $subject, $body)
    {
        mail($email, $subject, $body);
    }
}
