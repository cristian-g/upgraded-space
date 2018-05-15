<?php

namespace Pwbox\Controller\utils;

class EmailSender {
    private static $smtp_server = 'smtp.mailtrap.io';
    private static $username = 'f67054347185ac';
    private static $password = 'eebd296edd6a0f';
    private static $port = '465';

    public static function sendVerificationRequest($verificationLink, $userEmail, $userUsername) {
        // get the html email content
        $directory = __DIR__ . '/../../view/emails/';
        $html_content = file_get_contents($directory . 'email_verify.html');
        $html_content = preg_replace('/{link}/', $verificationLink, $html_content);

        // get plain email content
        $plain_text = file_get_contents($directory . 'email_verify.txt');
        $plain_text = preg_replace('/{link}/', $verificationLink, $plain_text);

        $message = (new \Swift_Message('Confirma tu email - PWBox'))
            ->setSubject("PWBox")
            ->setFrom(['pwbox@pwbox.test' => 'PWBox'])
            ->setTo([$userEmail => $userUsername])
            ->setBody($html_content, 'text/html')// add html content
            ->addPart($plain_text, 'text/plain'); // Add plain text

        // Create the Transport
        $transport = (new \Swift_SmtpTransport(self::$smtp_server, self::$port))
            ->setUsername(self::$username)
            ->setPassword(self::$password);

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        $mailer->send($message);
    }

    public static function sendNotification() {

    }


}