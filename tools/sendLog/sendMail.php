<?php
require("./phpmailer/PHPMailerAutoload.php");
class Mail{
    private $mail;
    public function __construct()
    {
        $this->mail = new \PHPMailer;
        $this->mail->isSMTP();                                          // Set mailer to use SMTP
        $this->mail->Host = '';                                         // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true;                                  // Enable SMTP authentication
        $this->mail->Username = '';                                    // SMTP username
        $this->mail->Password = '';                                    // SMTP password
        $this->mail->SMTPSecure = 'ssl';                                // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 465;
    }

    public  function sendMail($recipient,$title,$content,$file,$name='myy'){
        $this->mail->setFrom('deng@laiyouxi.com','╮(╯_╰)╭');            // setFrom  
        $this->mail->addAddress($recipient,$name);                          // Add a recipient
        $this->mail->isHTML(true);                                          // Set email format to HTML
        $this->mail->Subject = $title;
        $this->mail->Body    = $content;
        $this->mail->addAttachment($file);
		$this->mail->isHTML(true);
		$this->mail->CharSet = "utf-8";
        if(!$this->mail->send()) {
            return 'Mailer Error: ' . $this->mail->ErrorInfo;
        } else {
           return 0;
        }
    }
}