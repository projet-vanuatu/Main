<?php
@session_start();

require_once '../../Core/Manager.php';

//Fonction de recherche du materiel équipé dans la salle
if(isset($_POST["ids"]) && !empty($_POST["ids"])){
    $id=$_POST["ids"];
    $conn= dbConnect();
    $sql = "SELECT TypeMat, Etat_fonctionnement FROM MATERIELS WHERE IdS=$id";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resMa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION["Materiel"] = $resMa;
}

if(isset($_SESSION['Materiel'])){
    print_r(utf8_encode(json_encode($_SESSION['Materiel'])));
}
