<?php

namespace App\Services;

/**
 * Class MailInterface.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
interface MailerInterface
{
    public function send($view, $email, $subject, $data = []);

    public function queue($view, $email, $subject, $data = []);
}
