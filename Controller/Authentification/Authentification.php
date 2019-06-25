<?php

/* Controller */

function connexion(){
    if(!empty($_POST['id']) && !empty($_POST['pwd'])){
        modelLoader();
        $conn = $_POST;
        $result = getConnexion($conn);
        if($result){
            setSessionAttr($result['idA'], $result['NomA'], $result['PrenomA']);
            setSessionRequest($result['StatutA'], 'index', $result['StatutA']);
            $_SESSION['timeConnect'] = date('H:i');
            redirect($_SESSION['request']['controller'], $_SESSION['request']['action']);
        }
        $result = getConnexionEnseignant($conn);
        if($result){
            setSessionAttr($result['idENS'], $result['NomENS'], $result['PrenomENS']);
            setSessionRequest('Enseignant', 'index', 'Enseignant');
            $_SESSION['timeConnect'] = date('H:i');
            redirect($_SESSION['request']['controller'], $_SESSION['request']['action']);            
        }
        $result = getConnexionEtudiant($conn);
        if($result){
            setSessionAttr($result['idE'], $result['NomE'], $result['PrenomE']);
            setSessionRequest('Etudiant', 'index', 'Etudiant');
            $_SESSION['timeConnect'] = date('H:i');
            redirect($_SESSION['request']['controller'], $_SESSION['request']['action']);            
        }
        setSessionRequest('Authentification', 'connexion', 'Authentification');
    }
    renderView('connexion');
}

function deconnection(){
    $_SESSION = array();
    $GLOBALS = array();
    session_unset();
    redirect(AUTH, 'connexion');
}
