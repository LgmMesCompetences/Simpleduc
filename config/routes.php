<?php
function getPage($db){
    $lesPages['accueil'] = "accueilControleur";
    $lesPages['404'] = "notfoundControleur";
    $lesPages['maintenance'] = "maintenanceControleur";

if($db!=null) {
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }else {
        $page = 'accueil';
    }
    if(!isset($lesPages[$page])){
        $contenu = 'notfoundControleur';
    }else {
        $contenu = $lesPages[$page];
    }
}else {
    $contenu = 'maintenanceControleur';
}

return $contenu;
}