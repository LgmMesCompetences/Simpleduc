<?php

function newPayCheckControleur($twig) {
	echo $twig->render('paycheck/newPayCheck.html.twig', array());


}


function listePayCheckControleur($twig) {
	echo $twig->render('paycheck/payCheckTemplate.html.twig', array());
}


?>