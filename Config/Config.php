<?php

function dbPDOCredentials(){
    return array('host' => 'host',
                'dbName' => 'name',
                'identifiant' => 'id',
                'mdp' => 'mdp'
            );
}

function dbSQLiCredentials(){
    return array('host' => 'host',
                'dbName' => 'name',
                'identifiant' => 'id',
                'mdp' => 'mdp'
            );
}

function logFilePath(){
    return FPUBLIC.DS.'Logs'.DS.'log.txt';
}
