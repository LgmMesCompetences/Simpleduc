<?php

class Mailer{

    private $mailer;
    private $twig;

    public function __construct($twig) {
      include '../config/parametres.php';
      $this->twig = $twig;
        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
          ->setUsername($config['mailUsr'])
          ->setPassword($config['mailPswd'])
        ;
   
      // Create the Mailer using your created Transport
      $this->mailer = new Swift_Mailer($transport);
    }

    public function send2FA($target, $code){
            // Create a message
            $body = $this->twig->render('emails/email2FA.html.twig', ['code' => $code]);
         
            $message = (new Swift_Message('Connexion a simpleduc'))
              ->setFrom(['no-reply@simpleduc.fr' => 'NO REPLY'])
              ->setTo([$target])
              ->setBody($body)
              ->setContentType('text/html')
            ;
         
            // Send the message
            $this->mailer->send($message);
    }
}