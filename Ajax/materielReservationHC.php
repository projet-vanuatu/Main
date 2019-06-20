<?php

require_once FPUBLIC.DS.'Core/Manager.php';

if(isset($_POST["debut"]) && !empty($_POST["debut"])){
    if(isset($_POST["fin"]) && !empty($_POST["fin"])){
        $debut = $_POST["debut"];
        $fin = $_POST["fin"];
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
        $resultat=$res1;    
        $_SESSION['matdispoRHC']=$resultat;
//        $_SESSION['matdispoRHC'] = 'aa';
        if(isset($_SESSION['matdispoRHC'])){
            print_r(utf8_encode(json_encode($_SESSION['matdispoRHC'])));
            
        }
        
//        print_r(utf8_encode(json_encode($resultat)));
    }
}

//print_r(utf8_encode(json_encode($_SESSION['matdispoRHC'])));
