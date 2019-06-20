<?php
@session_start();
require_once './Core/Manager.php';
require_once 'fonctionsUtiles.php';
//Fonction de recherche du materiel disponible en fonction de la date
if(isset($_POST["ids"]) && !empty($_POST["ids"])){
    $conn= dbConnect();
    $id=$_POST["ids"];
    $sql = "SELECT DateDebutSeance AS debut, DateFinSeance AS fin FROM SEANCES WHERE NumS=$id";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resH = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    for($i=0;$i<=count($resH)-1;$i++){
        $debut=$resH[$i]['debut'];
        $fin=$resH[$i]['fin'];
    }
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
    $_SESSION['matdispo']=$resultat;
}

if(isset($_SESSION['matdispo'])){
    print_r(utf8_encode(json_encode($_SESSION['matdispo'])));
}


