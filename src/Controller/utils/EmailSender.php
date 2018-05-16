<?php

namespace Pwbox\Controller\utils;

class EmailSender {

    private static $smtp_server = 'smtp.mailtrap.io';
    private static $username = 'f67054347185ac';
    private static $password = 'eebd296edd6a0f';
    private static $port = '465';
    private static $businessEmail = 'pwbox@pwbox.test';
    private static $businessName = 'PWBox';

    public static function sendVerificationRequest($verificationLink, $userEmail, $userUsername) {
        // Get the html email content
        $directory = __DIR__ . '/../../view/emails/';
        $html_content = file_get_contents($directory . 'email_verify.html');
        $html_content = preg_replace('/{link}/', $verificationLink, $html_content);
        // Get plain email content
        $plain_text = file_get_contents($directory . 'email_verify.txt');
        $plain_text = preg_replace('/{link}/', $verificationLink, $plain_text);
        $subject = 'Confirma tu email - PWBox';
        $message = (new \Swift_Message($subject))
            ->setSubject($subject)
            ->setFrom([self::$businessEmail => self::$businessName])
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

    public static function sendNotification($notificationTitle, $notificationMessage, $folderName, $folderLink, $notificationsLink, $userEmail, $userUsername) {
        // Get the html email content
        $directory = __DIR__ . '/../../view/emails/';
        $html_content = file_get_contents($directory . 'notification.html');
        $html_content = preg_replace('/{notification_title}/', $notificationTitle, $html_content);
        $html_content = preg_replace('/{notification_message}/', $notificationMessage, $html_content);
        $html_content = preg_replace('/{folder_name}/', $folderName, $html_content);
        $html_content = preg_replace('/{link1}/', $folderLink, $html_content);
        $html_content = preg_replace('/{link2}/', $notificationsLink, $html_content);
        // Get plain email content
        $plain_text = file_get_contents($directory . 'notification.txt');
        $plain_text = preg_replace('/{notification_title}/', $notificationTitle, $plain_text);
        $plain_text = preg_replace('/{notification_message}/', $notificationMessage, $plain_text);
        $plain_text = preg_replace('/{folder_name}/', $folderName, $plain_text);
        $plain_text = preg_replace('/{link1}/', $folderLink, $plain_text);
        $plain_text = preg_replace('/{link2}/', $notificationsLink, $plain_text);
        $subject = 'Nueva notificación - PWBox';
        $message = (new \Swift_Message($subject))
            ->setSubject($subject)
            ->setFrom([self::$businessEmail => self::$businessName])
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
}