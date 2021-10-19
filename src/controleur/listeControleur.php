<?php
function listeControleur($twig, $db){
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
	$listeDev = $utilisateur->selectSpeFonction('1');
	$listeRespTech = $utilisateur->selectSpeFonction('2');
	$listeCompt = $utilisateur->selectSpeFonction('3');
	$listeRH = $utilisateur->selectSpeFonction('4');
	$listeDirect = $utilisateur->selectSpeFonction('5');

	echo $twig->render('security/listeUser.html.twig', array('form'=>$form, 'liste'=>$liste, 'listeDev'=>$listeDev, 
															 'listeRespTech'=>$listeRespTech, 'listeCompt'=>$listeCompt,
															'listeRH'=>$listeRH, 'listeDirect'=>$listeDirect));
}