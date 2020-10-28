<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer {
    private $sender;
    private $sender_name;
    private $receiver;
    private $bcc;
    private $title;
    private $message;
    private $attachment;

    public function __construct() {

    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setReceiver($receiver) {
        $this->receiver = $receiver;
    }

    public function setSender($sender = WEBSITE_MAIL_ADDRESS, $name = WEBSITE_MAIL_NAME) {
        $this->sender = $sender;
        $this->sender_name = $name;
    }

    public function setBCC($email) {
        $this->bcc = $email;
    }

    public function setAttachment($path = null) {
        $this->attachment = $path;
    }

    /**
     * Sends an email with properties as specified using {@link Mailer}'s setters.
     *
     * @throws SystemException if the mail failed to send.
     */
    public function send_mail() {
        date_default_timezone_set('Europe/Amsterdam');

        $mail = new PHPMailer();

        // Server settings
        $mail->isSMTP();

        $mail->SMTPDebug = Config::_c('debug') ? 2 : 0;
        $mail->Debugoutput = 'html';

        // 465 => ssl, 587 => tls
        $mail->Host = WEBSITE_MAIL_HOST;
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';

        $mail->SMTPAuth = true;
        $mail->Username = WEBSITE_MAIL_ADDRESS;
        $mail->Password = WEBSITE_MAIL_PASSWORD;

        // Recipients and sender(s)
        try {
            $mail->setFrom($this->sender, $this->sender_name);
        } catch (Exception $e) {
            throw new SystemException('Setting sender failed. ' . $mail->ErrorInfo, 0, $e);
        }

        $mail->addReplyTo($this->sender, $this->sender_name);

        $mail->addAddress($this->receiver);

        if (!empty($this->bcc)) {
            $mail->addBCC($this->bcc);
        }

        // Contents
        $mail->Subject = $this->title;
        $mail->msgHTML($this->message);

        // Add attachment
        if (isset($this->attachment)) {
            try {
                $added = $mail->addAttachment($this->attachment);
            } catch (Exception $e) {
                throw new SystemException('Adding attachment failed. ' . $mail->ErrorInfo, 0, $e);
            }

            if (!$added) {
                throw new SystemException('Adding attachment failed. ' . $mail->ErrorInfo);
            }
        }

        // Send mail
        try {
            $sent = $mail->send();
        } catch (Exception $e) {
            throw new SystemException('Mail sending failed. ' . $mail->ErrorInfo, 0, $e);
        }

        if (!$sent) {
            throw new SystemException('Mail sending failed. ' . $mail->ErrorInfo);
        }
    }

}
