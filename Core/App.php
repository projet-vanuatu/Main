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
    date_default_timezone_set(TIMEZONE);  
    $action = $_GET['action'];
    $params = parser($_GET);
    //consulter planning hors connexion
    if(isset($_SESSION['id'])){
        $expireConection = checkConnection();
        //si la session est toujours active
        if(!$expireConection){
            $_SESSION['timeConnect'] = date('H:i');
            //Si l'utilisateur ne se deconnecte pas
            if($action != 'deconnection'){       
                loadController($_SESSION['request']['controller']);
                $_SESSION['request']['action'] = $action;
                //Si l'action demandé est disponible (existe)
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
                setSessionRequest('Authentification', 'connexion', 'Authentification');
                loadController(AUTH);
                call_user_func('deconnection');                
            }           
        }else{
            setSessionRequest('Authentification', 'connexion', 'Authentification');
            loadController(AUTH);
            call_user_func('deconnection');               
        }    
    //Si l'utilisateur veut se deconnecter
    }else if($action == 'consulterPlanning'){
        consulterPlanning($params);
    }else{
        setSessionRequest('Authentification', 'connexion', 'Authentification');        
        loadController(AUTH);
        call_user_func('connexion');       
    }
}

function checkConnection(){
    $activeConnection = strtotime($_SESSION['timeConnect']);
    $systemTime = strtotime(date('H:i'));
    $delai = 1800; //30 minutes
    if($systemTime > ($activeConnection + $delai)){
        return true;
    }else{
        return false;
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
    if(file_exists($file)){
        require_once $file;
    }else{
        error('La page demandée est introuvable');
    }   
}