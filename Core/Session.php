<?php

function setSessionRequest($controller, $action, $layout){
    $_SESSION['request']['controller'] = $controller;
    $_SESSION['request']['action'] = $action;
    $_SESSION['request']['layout'] = $layout;       
}

function setSessionAttr($id, $nom, $prenom){
    $_SESSION['id'] = $id;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
}
