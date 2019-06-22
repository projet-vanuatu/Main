<?php
/*
 * Modèle général qui regroupe les fonctions utilisées par plusieurs modèles
 */

require_once 'Manager.php';

function getLastID($attr, $table){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT max($attr) as attr FROM $table;");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res['attr'];  
}

function rebuildArray($array, $index){
    $newArray = array();
    $cmp = 1;
    for($i=0;$i<=count($array)-1;$i++){
        $newArray[$cmp] = $array[$i][$index];
        $cmp++;
    }
    return $newArray;
}

function arrayAssocToIndex($data, $pattern, $len = 8){
    $matches = array();
    $indice = 1;
    foreach($data as $key => $value) {      
        if(substr($key, 0, $len) === $pattern){
            $matches[$indice] = $value;
            $indice++;     
        }
    }  
    return $matches;
}

/*
 * Fonction d'écriture du fichier log
 * @param string $id
 * @param string $nom
 * @param string $prenom
 * @param string $action
 * @param string $idAction
 */
function writeLog($id = '', $nom = 'N/A', $prenom = 'N/A', $action = 'N/A', $idAction = 'N/A'){
//    $path = logFilePath();
    $path = './Logs/Log.txt';
    $file = fopen($path, 'a');
    $ligne = $id . ' - ' . $nom . ' ' . $prenom . ' - a ' . $action . ' : ' . $idAction . '\n';
    fputs($file, $ligne);
    fclose($file);
}

/*
 * Recherche planning
 */
