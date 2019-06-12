<?php
/**
 * Fichier qui gère les requêtes
 */

require_once FPUBLIC.DS.'Core/Controller.php';
require_once FPUBLIC.DS.'Core/Session.php';
require_once FPUBLIC.DS.'Core/Model.php';
require_once FPUBLIC.DS.'Core/Define.php';
require_once FPUBLIC.DS.'Config/Config.php';

/*
 * Fonction qui redirige sur le controller et l'action demandée
 */
function dispatcher(){
//    var_dump($_SESSION);
//    die();
    //Si l'utilisateur est connecté et ne veux pas se deconnecter
    if(isset($_SESSION['id'])){
        $action = $_GET['action'];
        //Si l'utilisateur ne se deconnecte pas
        if($action != 'deconnection'){       
            $params = parser($_GET);
            loadController($_SESSION['request']['controller']);
            $_SESSION['request']['action'] = $action;
            if(function_exists($action)){
                if(!empty($params)){
                    call_user_func_array($action, array($params));
                }else{
                    call_user_func($action);
                }            
            }else{
                error("La page demandée n'est pas trouvée");
            }
        }else{
            $_SESSION['request']['controller'] = AUTH;
            $_SESSION['request']['action'] = 'connexion';
            $_SESSION['request']['layout'] = AUTH;           
            loadController(AUTH);
            call_user_func('deconnection');                
        }
    //Si l'utilisateur veut se deconnecter
    }else{
        $_SESSION['request']['controller'] = AUTH;
        $_SESSION['request']['action'] = 'connexion';
        $_SESSION['request']['layout'] = AUTH;           
        loadController(AUTH);
        call_user_func('connexion');       
    }
}

/*
 * Fonction qui ségmente le GET
 * @param associative array() $inputs
 * @return associative array(param1 => value1)
 */
function parser($inputs){
    $request = array();
    if(!empty($inputs)){
        foreach($inputs as $key=>$value){
            if($key !== 'action'){
                $request["$key"] = $value;
            }
        }
    }
    return $request;
}

/**
 * Fonction qui renvoie la requête HTTP
 * @return HTTP Request
 */
function userRequest(){
    return $_SERVER['REQUEST_URI'];
}

/*
 * Fonction qui charge le controller
 * @param string $name : nom du controller demandé
 */
function loadController($name){
    $file = FPUBLIC.DS.'Controller'.DS.ucfirst($name).DS.ucfirst($name).'.php';
    //echo $file;
    //die();
    if(file_exists($file)){
        require_once $file;
    }else{
        error('La page demandée est introuvable');
    }   
}

/*
 * Fonction qui gère les erreurs
 * @param string $msg : message d'erreur à afficher
 */
function error($msg){
    echo '<h4>'.$msg.'</h4><br><p>Ooops une erreur est survenue !';
    die();  
}