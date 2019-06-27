<?php
/*
 * Fichier qui gère les connections aux base de données
 */

/**
 * PDO connection
 * @return \PDO
 */
function dbConnect($var = null){
    if(!isset($GLOBALS['dbPDO'])){
        if(is_null($var)){
            require_once './Config/Config.php';
            $var = dbCredentials();           
        }
       $db = new PDO("mysql:host=".$var['host'].";dbname=".$var['dbName'].";charset=utf8", $var['identifiant'], $var['mdp']);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $GLOBALS['dbPDO'] = $db;
    }else{
       $db = $GLOBALS['dbPDO'];
    }   
    return $db;
}

/**
 * Mysqli connection
 * @return \MySqli
 */
function bdConnect($var = null){
    if(!isset($GLOBALS['dbSQLi'])){
        if(is_null($var)){
            require_once './Config/Config.php';
            $var = dbCredentials();           
        }
        $db = mysqli_connect($var['host'], $var['identifiant'], $var['mdp'], $var['dbName']);
        $GLOBALS['dbSQLi'] = $db;
    }else{
        $db = $GLOBALS['dbSQLi'];
    }
    return $db;
}

