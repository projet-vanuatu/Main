<?php

/* Controller */

function connexion(){
    if(!empty($_POST['id']) && !empty($_POST['pwd'])){
        modelLoader();
        $result = getConnexion($_POST);
        if($result){
            $_SESSION['id'] = $result['idA'];
            $_SESSION['nom'] = $result['NomA'];
            $_SESSION['prenom'] = $result['PrenomA'];
            $_SESSION['request']['controller'] = $result['StatutA'];
            $_SESSION['request']['action'] = 'index';
            $_SESSION['request']['layout'] = $result['StatutA'];
            redirect($_SESSION['request']['controller'], $_SESSION['request']['action']);
        }else{
            $_SESSION['request']['controller'] = 'Authentification';
            $_SESSION['request']['action'] = 'connexion';
            $_SESSION['request']['layout'] = 'Authentification';
            redirect(AUTH, 'connexion');
        }
    }
    renderView('connexion');
}

function deconnection(){
    $_SESSION = array();
    $GLOBALS = array();
    redirect(AUTH, 'connexion');
}
