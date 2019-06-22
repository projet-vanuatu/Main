<?php

require_once '../Core/Manager.php';
require_once '../Core/Define.php';

if(!empty($_POST["date"]) && !empty($_POST["debut"]) && !empty($_POST["fin"])){
        date_default_timezone_set(TIMEZONE);
        $date = $_POST["date"];
        $debut = $_POST["debut"];
        $fin = $_POST["fin"];
        $debut = date("Y/m/d H:i:s", strtotime("$date $debut"));
        $fin = date("Y/m/d H:i:s", strtotime("$date $fin"));
       
        $conn = dbConnect();  
        $sql="SELECT DISTINCT MATERIELS.IdMat, MATERIELS.numSerie , MATERIELS.TypeMat
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
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $resultat = $res1;
        
        if($_POST["reservation"] != -1){
            $id = $_POST["reservation"];
            $sql = "SELECT MATERIELS.IdMat, MATERIELS.numSerie , MATERIELS.TypeMat FROM MATERIELS, RESERVERHORSCOURS "
                    . "WHERE MATERIELS.IdMat = RESERVERHORSCOURS.IdMat AND RESERVERHORSCOURS.IdResHC = $id;";
            $stmt = $conn->prepare($sql); 
            $stmt->execute();
            $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $resultat = array_merge($resultat, $res2);
        }
        
        $resultatReturn = $resultat;
        $_SESSION['matdispoRHC'] = $resultatReturn;
        if(isset($_SESSION['matdispoRHC'])){
            print_r(utf8_encode(json_encode($_SESSION['matdispoRHC'])));            
        }
}