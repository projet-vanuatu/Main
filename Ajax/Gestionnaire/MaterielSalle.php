<?php
@session_start();

require_once '../../Core/Manager.php';
require_once '../../Config/Config.php';

$var = dbCredentials();
$conn = dbConnect($var);

//Fonction de recherche du materiel équipé dans la salle
if(isset($_POST["ids"]) && !empty($_POST["ids"])){
    $id=$_POST["ids"];
    $sql = "SELECT TypeMat, Etat_fonctionnement FROM MATERIELS WHERE IdS=$id";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resMa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION["Materiel"] = $resMa;
}

if(isset($_SESSION['Materiel'])){
    print_r(utf8_encode(json_encode($_SESSION['Materiel'])));
}
