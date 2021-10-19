<?php
	//lien de bareme IS en fonction des salaires
	//https://www.service-public.fr/particuliers/vosdroits/F1419

function newPayCheckControleur($twig, $db) {
	$form = array();
	$utilisateur = new User($db);
	$user = $utilisateur->select();

	if (isset($_POST['btCreer'])){
		$email = $_POST['email'];
		$email = $_POST['email'];
		$email = $_POST['email'];
		$email = $_POST['email'];
		$email = $_POST['email'];
		$email = $_POST['email'];
		 


	/*
		$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf']);
		$mpdf->WriteHTML($twig->render('paycheck/.html.twig'));
		$mpdf->Output('../storage/'.md5(uniqid()).'.pdf', 'F');
	*/
	
	}
	echo $twig->render('paycheck/newPayCheck.html.twig', array('form'=>$form, 'user'=>$user));



}


function listePayCheckControleur($twig) {
	echo $twig->render('paycheck/payCheckTemplate.html.twig', array());
}


?>