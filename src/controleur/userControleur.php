<?php

function profileControleur($twig) {
	echo $twig->render('user/profile.html.twig', array());
}

function userControleur($twig, $db){
	$form = array();
	$utilisateur = new User($db);

	if(isset($_GET['id'])){
		$exec=$utilisateur->delete($_GET['id']);
		if (!$exec){
			$etat = false;
		}
		else{
			$etat = true;
		}
		header('Location:listeUser?etat='.$etat);
		exit;
	}
	if(isset($_GET['etat'])){
		$form['etat'] = $_GET['etat'];
	}

	$liste = $utilisateur->select();
	echo $twig->render('security/listeUser.html.twig', array('form'=>$form, 'liste'=>$liste));
}