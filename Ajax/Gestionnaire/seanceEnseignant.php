<?php

require_once '../../Core/Manager.php';
require_once '../../Core/Define.php';
require_once '../../Config/Config.php';

$var = dbCredentials();
$conn = dbConnect($var);

//Fonction de recherche du materiel équipé dans la salle
if(isset($_POST["idens"])){
    $id=$_POST["idens"];
    $sql = "(SELECT SEANCES.NumS, MATIERES.IntituleM AS titre, DATE_FORMAT(SEANCES.DateDebutSeance, '%e/%m/%Y') AS date, DATE_FORMAT(SEANCES.DateDebutSeance, '%H:%i') AS heureD, DATE_FORMAT(SEANCES.DateFinSeance, '%H:%i') AS heureF
            FROM SEANCES, MATIERES, DISPENSE
            WHERE MATIERES.NumM=SEANCES.NumM
            AND DISPENSE.NumS=SEANCES.NumS
            AND DISPENSE.IdENS=$id)
            UNION
            (SELECT SEANCES.NumS, COURS_SPECIAUX.IntituleCSPE AS titre, DATE_FORMAT(SEANCES.DateDebutSeance, '%e/%m/%Y') AS date, DATE_FORMAT(SEANCES.DateDebutSeance, '%H:%i') AS heureD,  DATE_FORMAT(SEANCES.DateFinSeance, '%H:%i') AS heureF
            FROM SEANCES, COURS_SPECIAUX, DISPENSE 
            WHERE COURS_SPECIAUX.IdCSPE=SEANCES.IdCSPE 
            AND DISPENSE.NumS=SEANCES.NumS 
            AND DISPENSE.IdENS=$id)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resMa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION["seance"]=$resMa;
}
if(isset($_SESSION['seance'])){
    print_r(utf8_encode(json_encode($_SESSION['seance'])));
}