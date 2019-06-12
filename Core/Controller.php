<?php
/**
 * Controller général qui regroupe les fonctions utilisées par plusieurs controller
 */

/*
 * Fonction qui permet de rediriger l'utilisateur
 * @param string $controller : le controller à atteindre
 * @param string $action : l'action (fonction) a appeler
 * @param array $args : paramètres (optionnel) à passer en GET
 */
function redirect($controller = null, $action = null, $args = array()){
    is_null($controller) ? $controller = $_SESSION['request']['controller'] : null;
    is_null($controller) ? $action = $_SESSION['request']['action'] : null;
    if(!empty($args)){
        $str = '';
        foreach($args as $key=>$value){
            if($key !== 'action'){
                $str = $str.'&'.$key.'='.$value;
            }      
        }
        $location = "index.php?action=" . $action . $str;
    }else{
        $location = "index.php?action=" . $action;
    }
    header("Location: " . $location);
    exit;
}

/*
 * Fonction qui permet de générer la vue
 * @param array $data : données transmits à la vue
 * @param string $title : titre de la page
 */ 
function renderView($action = 'index', $data = array(), $title = 'Intranet Faculté du Vanuatu'){
    extract($data);
    ob_start();
    require_once(FPUBLIC.DS.'View/View/'. ucfirst($_SESSION['request']['controller']) .DS. $action . '.php');
    $content = ob_get_clean();
    ob_end_clean();
    //ob_clean();
    require_once(FPUBLIC.DS.'View/Layout/'.$_SESSION['request']['layout'].'/'.$_SESSION['request']['layout'].'.php');
}

/*
 * Fonction qui charge les modèles
 */
function modelLoader(){
    $file = FPUBLIC.DS.'Model'.DS.ucfirst($_SESSION['request']['controller']).DS.ucfirst($_SESSION['request']['controller']).'.php';
    require_once $file;
}