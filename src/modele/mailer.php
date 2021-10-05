<?php

class Mailer{

    private $mailer;

    public function __construct() {
        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
        ->setUsername('testa.charly')
        ->setPassword('anxiggbwrmobmero')
      ;
   
      // Create the Mailer using your created Transport
      $this->mailer = new Swift_Mailer($transport);
    }

    public function send2FA($target, $code){
	 
        try {
            // Create a message
            $body = $twig->render('emails/email2FA.html.twig', ['code' => $code]);
         
            $message = (new Swift_Message('Email Through Swift Mailer'))
              ->setFrom(['' => 'NO REPLY'])
              ->setTo([$target])
              ->setBody($body)
              ->setContentType('text/html')
            ;
         
            // Send the message
            $mailer->send($message);
        } catch(Exception $e) {
        }
    }
}