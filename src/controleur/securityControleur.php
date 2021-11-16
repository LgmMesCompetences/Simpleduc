<?php

use OTPHP\TOTP;

function inscrireControleur($twig, $db){

	$form = array();
	if (isset($_POST['btInscrire'])){
		$email = $_POST['email'];
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$dateEmbauche = $_POST['dateEmbauche'];
		$numSecu = $_POST['numSecu'];
		$password = substr(strtolower($prenom), 0, 1).'.'.strtolower($nom).''.substr($dateEmbauche, 0, 4);
		$fonction = $_POST['fonction'];
		$form['valide'] = true;
		if(strlen($nom) == 0) {
			$form['valide'] = false;
			$form['message'] = 'Merci de spécifier un nom !';
		}
		elseif (strlen($prenom) == 0){
			$form['valide'] = false;
			$form['message'] = 'Merci de spécifier un prenom !';
		}
		elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$form['valide'] = false;
			$form['message'] = 'L\'email est invalide !';
		}
		else{
			$utilisateur = new User($db);
			$exec = $utilisateur->insert($nom, $prenom, $email, password_hash($password, PASSWORD_DEFAULT), $dateEmbauche, $fonction,$numSecu);
			
			if (!$exec){
				$form['valide'] = false;
				$form['message'] = 'Problème d\'ajout de compte !';
			}
			else{
				$mailer = new Mailer($twig);
				$mailer->sendNewAccount($email, $password);
			}
			$form['email'] = $email;
			$form['fonction'] = $fonction;
		}
	

	}


	echo $twig->render('security/ajout.html.twig', array('form'=>$form));
}

function testPswd(String $pswd): bool
{
	$part1 = preg_match('/[a-z]+/', $pswd);
	$part2 = preg_match('/[A-Z]+/', $pswd);
	$part3 = preg_match('/[0-9]+/', $pswd);
	$part4 = preg_match('/[^a-zA-Z0-9]+/', $pswd);

	return $part1 && $part2 && $part3 && $part4;
}

function firstLoginControleur($twig, $db){
	if(!isset($_SESSION['lockFirst'])) header('Location:profile');

	$form = array();
	if (isset($_POST['btConnecter'])){
		$password = $_POST['password'];
		$password2 = $_POST['passwordR'];
		$form['valide'] = true;

		if (strlen($password) == 0){
			$form['valide'] = false;
			$form['message'] = 'Merci de spécifier un mot de passe !';
		}
		elseif (strlen($password) < 8){
			$form['valide'] = false;
			$form['message'] = 'Merci de spécifier un mot de passe d\'au moins 8 caractères !';
		}
		elseif (!testPswd($password)){
			$form['valide'] = false;
			$form['message'] = 'Le mot de passe doit comporter au moins: 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial !';
		}
		elseif ($password!=$password2){
			$form['valide'] = false;
			$form['message'] = 'Les mots de passe sont différents !';
		}
		else{
			$utilisateur = new User($db);
			$utilisateur->updateMdp($_SESSION['id'], password_hash($password, PASSWORD_DEFAULT));
			$utilisateur->updateLastLogin($_SESSION['id'], date('Y-m-d H:i:s'));

			unset($_SESSION['lockFirst']);

			header('Location:profile');
		}
	}

	echo $twig->render('security/firstLogin.html.twig', array('form'=>$form));
}

function dFAControleur($twig, $db) {
	if(!isset($_SESSION['2FA_try'])) $_SESSION['2FA_try'] = 0;
	if(!isset($_SESSION['lock2FA'])) header('Location:profile');

	if(!isset($_SESSION['tempID'])) $_SESSION['tempID'] = strtoupper(substr(uniqid(), 7));
	$otp = TOTP::create($_SESSION['user']['otpKey']);


	include '../config/parametres.php';

	if($config['debug']) {
		echo $_SESSION['tempID'];
	}

	if (!isset($_POST['btConnecter']) && $_SESSION['user']['dfaType'] == 'email') {
		$mailer = new Mailer($twig);
		$mailer->send2FA($_SESSION['login'], $_SESSION['tempID']);
	}
	
	$form = array();
	if (isset($_POST['btConnecter'])){
		$code = $_POST['code'];
		$done = false;

		if($_SESSION['user']['dfaType'] == 'email')
		{
			if (strlen($code) == 0){
				$form['valide'] = false;
				$form['message'] = 'Merci de spécifier le code reçu par email';
			}
			elseif (trim(strtoupper($code))!=$_SESSION['tempID']){
				$form['valide'] = false;
				$form['message'] = 'Code incorrect';
			}else {
				$done = true;
			}
		}
		
		if($_SESSION['user']['dfaType'] == 'otp')
		{
			if (strlen($code) == 0){
				$form['valide'] = false;
				$form['message'] = 'Merci de spécifier le code de votre application d\'OTP';
			}
			elseif (!$otp->verify(trim($code), null, 1) && !(trim(strtoupper($code)) == $_SESSION['tempID'] && $_SESSION['2FA_try']>=2)){
				$_SESSION['2FA_try']++;
				$form['valide'] = false;
				$form['message'] = 'Code incorrect';
			}else {
				$done = true;
			}
		}

		if($done) {
			unset($_SESSION['lock2FA']);
			unset($_SESSION['tempID']);
			unset($_SESSION['2FA_try']);

			header('Location:profile');
		}
	}

	if (isset($_POST['btSendMail'])) {
		if ($_SESSION['user']['dfaType'] == 'otp') {
			$mailer = new Mailer($twig);
			$mailer->send2FA($_SESSION['login'], $_SESSION['tempID']);
		}
	}

	echo $twig->render('security/2FA.html.twig', array('form'=>$form, 'sendMail' => ($_SESSION['2FA_try']>=2)));
}

function mdpOublieControleur($twig, $db) {
	
	$form = array();
	
	if (isset($_POST['btEnvoyer'])){
		$email = $_POST['email'];
		$utilisateur = new User($db);
		$unUtilisateur = $utilisateur->selectByEmail($email);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$form['valide'] = false;
			$form['message'] = 'L\'email est invalide !';
		}
		if ($unUtilisateur!=null) {
			$token = md5(uniqid());
			$utilisateur->setToken($unUtilisateur['id'],$token);
			
			$mailer = new Mailer($twig);
			$mailer->sendNewPassword($email, $token);
			$form['message'] = 'Un email a été envoyé !';

		}

	}

	
	echo $twig->render('security/mdpoublie.html.twig', array('form'=>$form));
}

function newPasswordControleur($twig, $db){
	$token = $_GET['token'];

	$utilisateur = new User($db);
	$unUtilisateur = $utilisateur->getByToken($token);

	if ($unUtilisateur == null) {
		header('Location: connexion');
	}

	$form = array();

	if(isset($_POST['btChanger'])){
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$form['valide'] = true;

		if (strlen($password) == 0){
			$form['valide'] = false;
			$form['message'] = 'Merci de spécifier un mot de passe !';
		}
		elseif (strlen($password) < 8){
			$form['valide'] = false;
			$form['message'] = 'Merci de spécifier un mot de passe d\'au moins 8 caractères !';
		}
		elseif (!testPswd($password)){
			$form['valide'] = false;
			$form['message'] = 'Le mot de passe doit comporter au moins: 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial !';
		}
		elseif ($password!=$password2){
			$form['valide'] = false;
			$form['message'] = 'Les mots de passe sont différents !';
		}
		else{
			$utilisateur->updateMdp($unUtilisateur['id'], password_hash($password, PASSWORD_DEFAULT));
			$utilisateur->setToken($unUtilisateur['id'], null);

			header('Location: connexion');
		}

	}
	echo $twig->render('security/newPassword.html.twig', array('form'=>$form));

}

function connexionControleur($twig, $db) {
	if (isset($_SESSION['id']) && $_SESSION['id'] != null) {
		header('Location: profile');
	}

	$form = array();
	$email = null;

	if (isset($_POST['btConnecter'])){
		$form['valide'] = true;
		$email = $_POST['email'];
		$password = $_POST['password'];

		$utilisateur = new User($db);
		$unUtilisateur = $utilisateur->connect($email);
		if ($unUtilisateur!=null){
			if(!password_verify($password, $unUtilisateur['password'])){
				$form['valide'] = false;
				$form['message'] = 'Mot de passe incorrect !';
			}
			else{
				$_SESSION['id'] = $unUtilisateur['id'];
				$_SESSION['login'] = $unUtilisateur['email'];
				$_SESSION['role'] = $unUtilisateur['fonction'];
				$_SESSION['user'] = $utilisateur->get($unUtilisateur['id']);

				if($unUtilisateur['lastLogin'] == null) {
					$_SESSION['lockFirst'] = true;
					header("Location:firstLogin");
					
				}else {
					$utilisateur->updateLastLogin($unUtilisateur['id'], date('Y-m-d H:i:s'));
					$_SESSION['lock2FA'] = true;
					header("Location:2FA");
				}
			}
		}
		else{
			$form['valide'] = false;
			$form['message'] = 'Email incorrect !';
		}
	}
	echo $twig->render('security/connexion.html.twig', array('form'=>$form, 'email'=>$email));
}


function deconnexionControleur($twig, $db){
	session_unset();
	session_destroy();
	header("Location:connexion");
}