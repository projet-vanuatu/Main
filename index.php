<?php
session_start();

define('FPUBLIC', dirname(__FILE__));
define('ROOT', dirname(FPUBLIC));
define('DS', DIRECTORY_SEPARATOR);
define('BASE_URL', $_SERVER['SCRIPT_NAME']);

require_once('./Core/App.php');

try{  
    dispatcher();
} catch (Exception $e) {
    $msgError = $e->getMessage();
    print_r($msgError);
    die();
}
