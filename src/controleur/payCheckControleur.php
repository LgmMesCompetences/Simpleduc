<?php
	//lien de bareme IS en fonction des salaires
	//https://www.service-public.fr/particuliers/vosdroits/F1419

function newPayCheckControleur($twig, $db) {
	$form = array();
	$utilisateur = new User($db);
	$user = $utilisateur->get($_GET['id']);

	$user['dateEmbauche'] = DateTimeImmutable::createFromFormat('Y-m-d', $user['dateEmbauche']);
	//dd($user['dateEmbauche']);


	//https://validator.w3.org/nu/#textarea to validate the html template


	$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf']);
	$mpdf->WriteHTML($twig->render('paycheck/payCheckTemplate.html.twig', ['user'=>$user]));
	$mpdf->Output();
	//$mpdf->Output('../storage/'.md5(uniqid()).'.pdf', 'F');

	if (isset($_POST['btCreer'])){
		$email = $_POST['email'];
		$dateEmission = new \DateTime();

		 


	


	
	}
	echo $twig->render('paycheck/newPayCheck.html.twig', array('form'=>$form, 'u'=>$user));



}


function listePayCheckControleur($twig) {
	echo $twig->render('paycheck/payCheckTemplate.html.twig', array());
}


?>