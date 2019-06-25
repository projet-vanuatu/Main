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
            $strPush = '';
            if($key !== 'action'){
                if(is_array($value)){
                    foreach($value as $subKey=>$subValue){
                        $strPush = $strPush.'&'.$subKey.'='.$subValue;
                    }
                }else{
                   $strPush = $key.'='.$value; 
                }
                $str = $str.'&'.$strPush;
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
function renderView($action = 'index', $data = array(), $title = 'Intranet Faculté du Vanuatu', $layout = null, $controller = null, $dossierLayout = null){
    if(is_null($controller)){
        $controller = $_SESSION['request']['controller'];       
    }
    if(is_null($dossierLayout)){
        $dossierLayout = $_SESSION['request']['layout'];
    }
    extract($data);
    ob_start();
    require_once(FPUBLIC.DS.'View/View/'. ucfirst($controller) .DS. $action . '.php');
    $content = ob_get_clean();
    ob_end_clean();
    if(is_null($layout)){
        $layout = $_SESSION['request']['layout'];
    }
    require_once(FPUBLIC.DS.'View/Layout/'.$dossierLayout.'/'.$layout.'.php');
}

/*
 * Fonction qui charge les modèles
 */
function modelLoader($controller = null){
    if(is_null($controller)){
        $controller = $_SESSION['request']['controller'];
    }
    $file = FPUBLIC.DS.'Model'.DS.ucfirst($controller).DS.ucfirst($controller).'.php';
    require_once $file;
}

/*
 * 
 */
function consulterPlanning($params = null){
    if(isset($params['type'])){
       $view = $params['type']; 
    }else{
       $view = "planningFormation"; 
    }
    $_SESSION['criteria'] = "";
    if(!empty($_POST)){
        $selected = $_POST['criteria'];
        $view = $_POST['type'];
        $_SESSION['criteria'] = $_POST['criteria'];
    }else{
        $selected = "";
    } 
    if($view == 'planningEnseingnant'){
        $dataToView = getEnseignantsPlanning();   
    }else if($view == 'planningFormation'){
        $dataToView = getFormationsPlanning();
    }else if($view == 'planningSalle'){
        $dataToView = getSallesPlanning();
    }
    if(isset($_SESSION['id'])){
        renderView($view, array('formulaire' => $dataToView, 'selected' => $selected),
                'Planning par enseignant', $_SESSION['request']['layout'].'Planning', 'Planning', NULL);       
    }else{
        renderView($view, array('formulaire' => $dataToView, 'selected' => $selected),
                'Planning par enseignant', 'Planning', 'Planning', 'Planning');
    }
}

/*
 * Fonction qui gère les erreurs
 * @param string $msg : message d'erreur à afficher
 */
function error($msg){
    $msg = '<h4>Ooops une erreur est survenue !</h4><br><p>'.$msg.'</p>';
    renderView('Erreur', array('msgErreur' => $msg), 'Page d\'erreur', 'Erreur', 'Erreur', 'Erreur');
    die();  
}
