<?php
    require_once '../lib/vendor/autoload.php';
    require_once '../src/controleur/_controleurs.php';
    require_once '../config/routes.php';
    require_once '../config/parameters.php';
    require_once '../config/connection.php';
    require_once '../src/modele/_classes.php';

    $loader = new \Twig\Loader\FilesystemLoader('../src/vue/'); 
    $twig = $twig = new \Twig\Environment($loader, []);
?>
