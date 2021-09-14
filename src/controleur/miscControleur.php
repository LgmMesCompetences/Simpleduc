<?php

function accueilControleur($twig) {
	echo $twig->render('misc/accueil.html.twig', array());
}

function notfoundControleur($twig) {
	echo $twig->render('misc/notfound.html.twig', array());
}

function maintenanceControleur($twig) {
	echo $twig->render('misc/maintenance.html.twig', array());
}