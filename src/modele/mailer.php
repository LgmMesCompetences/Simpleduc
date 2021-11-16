<?php

class Mailer{

    private $mailer;
    private $twig;
    private $send;

    public function __construct($twig) {
      include '../config/parametres.php';
      $this->twig = $twig;
      $this->send = $config['sendEmails'];
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
         
            $message = (new Swift_Message('Connexion à Simpl\'Educ'))
              ->setFrom(['no-reply@simpleduc.fr' => 'NO REPLY'])
              ->setTo([$target])
              ->setBody($body)
              ->setContentType('text/html')
            ;
         
            // Send the message
            if($this->send) $this->mailer->send($message);
    }

    public function sendNewAccount($target, $mdp){
        // Create a message
        $body = $this->twig->render('emails/emailNewAccount.html.twig', ['mdp' => $mdp]);
     
        $message = (new Swift_Message('Connexion à Simpl\'Educ'))
          ->setFrom(['no-reply@simpleduc.fr' => 'NO REPLY'])
          ->setTo([$target])
          ->setBody($body)
          ->setContentType('text/html')
        ;
     
        // Send the message
        if($this->send) $this->mailer->send($message);
    }

    public function sendNewPassword($target, $token){
      // Create a message
      $body = $this->twig->render('emails/emailNewPassword.html.twig', ['token' => $token]);
   
      $message = (new Swift_Message('Nouveau Mot de passe pour Simpl\'Educ'))
        ->setFrom(['no-reply@simpleduc.fr' => 'NO REPLY'])
        ->setTo([$target])
        ->setBody($body)
        ->setContentType('text/html')
      ;
   
      // Send the message
      if($this->send) $this->mailer->send($message);
  }
}