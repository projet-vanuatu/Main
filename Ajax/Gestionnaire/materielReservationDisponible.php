<?php

require_once '../../Core/Manager.php';
require_once '../../Core/Define.php';

//Fonction de recherche du materiel disponible en fonction de la date
if(isset($_POST["ids"]) && !empty($_POST["ids"])){
    $conn= dbConnect();
    $id=$_POST["ids"];
    $sql = "SELECT DateDebutSeance AS debut, DateFinSeance AS fin FROM SEANCES WHERE NumS=$id";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resH = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $debut=$resH['debut'];
    $fin=$resH['fin'];
    
    $conn = dbConnect(); 
    $sql1="SELECT DISTINCT MATERIELS.IdMat, MATERIELS.numSerie , MATERIELS.TypeMat
            FROM  MATERIELS
            WHERE MATERIELS.IdS IS NULL
            AND MATERIELS.Etat_fonctionnement='En marche'
            AND MATERIELS.IdMat NOT IN (
                    SELECT RESERVER.IdMat
                    FROM SEANCES, RESERVER
                    WHERE SEANCES.NumS=RESERVER.NumS 
                    AND SEANCES.DateDebutSeance >= '$debut' AND SEANCES.DateDebutSeance < '$fin')
            AND MATERIELS.IdMat NOT IN (
                    SELECT RESERVER.IdMat
                    FROM SEANCES, RESERVER
                    WHERE SEANCES.NumS=RESERVER.NumS 
                    AND SEANCES.DateFinSeance >'$debut' AND SEANCES.DateFinSeance <= '$fin')
            AND MATERIELS.IdMat NOT IN (
                    SELECT RESERVER.IdMat
                    FROM SEANCES, RESERVER
                    WHERE SEANCES.NumS=RESERVER.NumS 
                    AND SEANCES.DateDebutSeance < '$debut'
                    AND SEANCES.DateFinSeance > '$fin')
                    AND MATERIELS.IdMat IN (SELECT MATERIELS.IdMat
            FROM  MATERIELS
            WHERE MATERIELS.IdS IS NULL
            AND MATERIELS.Etat_fonctionnement='En marche'
            AND MATERIELS.IdMat NOT IN (
                    SELECT RESERVERHORSCOURS.IdMat
                    FROM RESERVERHORSCOURS
                    WHERE RESERVERHORSCOURS.DateDebutResaHC >= '$debut' AND RESERVERHORSCOURS.DateDebutResaHC < '$fin')
            AND MATERIELS.IdMat NOT IN (
                    SELECT RESERVERHORSCOURS.IdMat
                    FROM RESERVERHORSCOURS
                    WHERE  RESERVERHORSCOURS.DateFinResaHC >'$debut' AND RESERVERHORSCOURS.DateFinResaHC <= '$fin')
            AND MATERIELS.IdMat NOT IN (
                    SELECT RESERVERHORSCOURS.IdMat
                    FROM RESERVERHORSCOURS
                    WHERE  RESERVERHORSCOURS.DateDebutResaHC < '$debut'
                    AND RESERVERHORSCOURS.DateFinResaHC > '$fin'))";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $resultat = $res1;
    
    if($_POST["idMat"] != -1){
        $id = $_POST["idMat"];
        $sql = "SELECT MATERIELS.IdMat, MATERIELS.numSerie , MATERIELS.TypeMat FROM MATERIELS WHERE IdMat = $id";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $resultat = array_merge($resultat, $res2);
    }
    $_SESSION['matdispo']=$resultat;
}

if(isset($_SESSION['matdispo'])){
    print_r(utf8_encode(json_encode($_SESSION['matdispo'])));
}


