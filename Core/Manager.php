<?php
/*
 * Fichier qui gère les connections aux base de données
 */

/**
 * PDO connection
 * @return \PDO
 */
function dbConnect(){
    if(!isset($GLOBALS['dbPDO'])){
       $db = new PDO('mysql:host=localhost;dbname=db_21100905;charset=utf8', '21100905', '35952H');
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
function bdConnect(){
    if(!isset($GLOBALS['dbSQLi'])){
        $db = mysqli_connect('localhost','21100905','35952H','db_21100905');
        $GLOBALS['dbSQLi'] = $db;;
    }else{
        $db = $GLOBALS['dbSQLi'];
    }
    return $db;
}

