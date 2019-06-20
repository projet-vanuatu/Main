<?php
    @session_start();
    require_once './Core/Manager.php';

    //==========================================================================
    //RECUPERER infos d'un enseignant
    //==========================================================================
    function RecupererEnseignant(){
        $conn = dbConnect();
        $sql = "SELECT IdENS, PrenomENS, NomENS, TypeENS, IdMdp "
                . "FROM ENSEIGNANT ";  
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resENS = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resENS;
    }

    //==========================================================================
    //RECUPERER infos d'une matière
    //==========================================================================
    function RecupererMatieres(){
        $conn = dbConnect();
        $sql = "SELECT S.NumM, M.IntituleM "
                . "FROM SEANCES S, MATIERES M "
                . "WHERE S.NumM = M.NumM ";  
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resMatiere = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resMatiere;
    }

    //==========================================================================
    //RECUPERER infos d'un matériel
    //==========================================================================
    function RecupererMateriel(){
        $conn = dbConnect();
        $sql = "SELECT IdMat, TypeMat "
                . "FROM MATERIELS ";  
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resMateriel = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resMateriel;
    }

    //==========================================================================
    //RECUPERER infos d'une réservation 
    //==========================================================================
    function RecupererReserver(){
        $conn = dbConnect();
        $sql = "SELECT R.IdRes, R.IdENS, E.PrenomENS, E.NomENS, R.IdA, M.IdMat, M.TypeMat, M.NumSerie, S.NumS, MA.NumM, MA.IntituleM, DATE_FORMAT(S.DateDebutSeance, '%d/%m/%Y') AS date, DATE_FORMAT(S.DateDebutSeance, '%Hh%i') AS debut, DATE_FORMAT(S.DateFinSeance, '%Hh%i') AS fin , DATE_FORMAT(R.DateResa, '%d/%m/%Y') AS resa "
                . "FROM RESERVER R,ENSEIGNANT E,SEANCES S, MATERIELS M, MATIERES MA "
                . "WHERE M.IdMat = R.IdMat "
                . "AND R.IdENS = E.IdENS "
                . "AND S.NumS = R.NumS "
                . "AND S.NumM = MA.NumM ";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resResa = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resResa;
    }

    //==========================================================================
    //FORMAT des heures de réservation
    //==========================================================================
//    function sumOfDiffrentTime($times = array())
//    {
//        $minutes = '';
//        // loop through all the times array
//        foreach ($times as $time) {
//            list($hour, $minute) = explode(':', $time);
//            $minutes += $hour * 60;
//            $minutes += $minute;
//        }
//        $hours = floor($minutes / 60);
//        $minutes -= $hours * 60;
//        // returns the  formatted time
//        return sprintf('%02d:%02d', $hours, $minutes);
//    }

    //==========================================================================
    //RECUPERER infos d'un matériel non affecté à une salle
    //==========================================================================
//    function RecupererMaterielHC(){
//        $conn = ConnectPDO();
//        $sql = "SELECT IdMat, TypeMat "
//                . "FROM MATERIELS "
//                . "WHERE IdS IS NULL ";  
//        $stmt = $conn->prepare($sql); 
//        $stmt->execute();
//        $resMateriel = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        return $resMateriel;
//    }    

    
    //####################################################################################################################################################
    //************************************************************** RESERVATION COURS *******************************************************************
    //####################################################################################################################################################

    
    //==========================================================================
    //RECUPERER SEPAREMMENT Date & Heure d'une séance
    //==========================================================================
//    function recupererInfoSeance($NumS){
//        $conn = ConnectPDO();
//        date_default_timezone_set('Europe/Paris');
//        $sql = "SELECT DateDebutSeance, DateFinSeance "
//                . "FROM SEANCES "
//                . "WHERE NumS = $NumS;";
//        $stmt = $conn->prepare($sql); 
//        $stmt->execute();
//        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $arrayTimes = array();
//        $arrayDates = array();
//        for($i=0;$i<=count($res)-1;$i++){
//            $arrayTimes[$i]['DateDebutSeance'] = date('H:i', strtotime($res[$i]['DateDebutSeance']));
//            $arrayTimes[$i]['DateFinSeance'] = date('H:i', strtotime($res[$i]['DateFinSeance']));
//            $arrayDates[$i]['Date'] = date("Y/m/d", strtotime($res[$i]['DateFinSeance']));
//        }
//        return(array('time' => $arrayTimes, 'date' => $arrayDates)); 
//    }
    
    //==========================================================================
    //RECUPERER l'heure de fin d'une séance
    //==========================================================================
//    Function RecupererHFinSeance(){
//        $conn = ConnectPDO();
//        date_default_timezone_set("Europe/Paris");
//        $sql = "SELECT DateFinSeance "
//                . "FROM SEANCES";
//        $stmt = $conn->prepare($sql); 
//        $stmt->execute();
//        $resHFinSeance = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $arrayTimesHFinResa = array();
//        for($i=0;$i<=count($resHFinSeance)-1;$i++){
//            $arrayTimesHFinResa[$i]['DateFinSeance'] = date('H:i', strtotime($resHFinSeance[$i]['DateFinSeance']));
//        }
//        return($arrayTimesHFinResa);
//    }

    //==========================================================================
    //RECUPERER infos d'une séance
    //==========================================================================
    Function RecupererSeance(){
        $conn = dbConnect();
        $sql = "SELECT S.NumS, S.DateDebutSeance, S.IdGTD, S.NumM, M.IntituleM, S.DateFinSeance, S.IdGCM "
                . "FROM SEANCES S, MATIERES M"
                . "WHERE S.NumM = M.NumM "
                . "ORDER BY M.IntituleM ASC ";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resSeances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resSeances;
    }
    
    //==========================================================================
    //RECUPERER SEPAREMMENT Date & Heure d'une réservation pour une séance  
    //==========================================================================
//    function recupererdateheureS($IdMat, $Date){
//        $conn = ConnectPDO();
//        date_default_timezone_set('Europe/Paris');
//        $sql = "SELECT S.DateDebutSeance, S.DateFinSeance "
//                . "FROM RESERVER R, SEANCES S "
//                . "WHERE R.NumS = S.NumS "
//                . "AND R.IdMat = $IdMat "
//                . "AND S.DateDebutSeance = '$Date'; ";
//        $stmt = $conn->prepare($sql); 
//        $stmt->execute();
//        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $arrayTimes = array();
//        $arrayDates = array();
//        for($i=0;$i<=count($res)-1;$i++){
//            $arrayTimes[$i]['DateDebutSeance'] = date('H:i', strtotime($res[$i]['DateDebutSeance']));
//            $arrayTimes[$i]['DateFinSeance'] = date('H:i', strtotime($res[$i]['DateFinSeance']));
//            $arrayDates[$i]['Date'] = date("Y/m/d", strtotime($res[$i]['DateFinSeance']));
//        }
//        return(array('time' => $arrayTimes, 'date' => $arrayDates));
//    }
    

    //==========================================================================
    //RECUPERER l'heure de début d'une séance
    //==========================================================================
//    Function RecupererHDebutSeance(){
//        $conn = ConnectPDO();
//        date_default_timezone_set("Europe/Paris");
//        $sql = "SELECT DateDebutSeance "
//                . "FROM SEANCES";
//        $stmt = $conn->prepare($sql); 
//        $stmt->execute();
//        $resHDebutSeance = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $arrayTimesHDebutResa = array();
//        for($i=0;$i<=count($resHDebutSeance)-1;$i++){
//            $arrayTimesHDebutResa[$i]['DateDebutSeance'] = date('H:i', strtotime($resHDebutSeance[$i]['DateDebutSeance']));
//        }
//        return($arrayTimesHDebutResa);
//    }
    
//    //==========================================================================
//    // INSERER une réservation pour une séance
//    //==========================================================================
//    function InsererReservation($IdENS, $IdA, $IdMat, $NumS){
//        $cx = ConnectDB();
//        $InsertReserver = "INSERT INTO RESERVER(IdENS, IdA, IdMat, NumS, DateResa) "
//                . "VALUES ('$IdENS', '$IdA', '$IdMat', '$NumS', '".date("Y-m-d")."')";
//        mysqli_query($cx, $InsertReserver);
//        return 'gestionReservation.php';
//    }
//    
    //==========================================================================
    //MODIFIER une réservation d'une séance
    //==========================================================================
//    function ModifierReservationS($IdMat, $IdENS, $IdA, $NumS, $IdRes){
//        $cx = ConnectDB();
//        $sql = "UPDATE RESERVER SET IdMat= $IdMat, IdENS = '$IdENS', IdA = '$IdA',NumS = $NumS WHERE IdRes = $IdRes";
//        $querysql = mysqli_query($cx, $sql);
//        return "gestionReservation.php";
//    }
    
    //==========================================================================
    //SUPPRIMER  une réservation d'une séance
    //==========================================================================
    function SupprimerReserver($IdRes){
        $cx = bdConnect();
        $sql = "DELETE FROM RESERVER "
                . "WHERE IdRes = $IdRes ";
        mysqli_query($cx, $sql); 
        return "gestionReservation.php";
        }
    
    //==========================================================================
    //LIMITER l'heure de fin d'une réservation (RESERVER)
    //==========================================================================
//    function GetHeureFinSeance($arrayTimesHFinResa){
//        date_default_timezone_set('Europe/Paris');
//        $time = array($arrayTimesHFinResa);
//        $timeMax = '19:00';
//        If($end > date('H:i', $timeMax)){
//            //echo utf8_encode("Réservation trop longue !");
//            return 'creationReserverHC.php';
//        }else{
//            return $end;
//
//        }
//    }
    //==========================================================================
    //COMPARER les réservations de séances
    //==========================================================================
//    Function ComparaisonS($IdENS, $IdMat, $NumS, $DateResa){
//        $end = GetHeureFinSeance($arrayTimesHFinResa);
//        $res = recupererdateheureS($IdMat, $DateResa);
//        $bool = false;
//        if(!empty($res['time'])){
//            $cmp = 0;
//            while($cmp <= count($res['time'])-1 && !$bool){
//                if($res['time'][$cmp]['DateFinSeance'] > $Heure || $end > $res['time'][$cmp]['DateDebutSeance']){
//                    $bool = true;
//                }
//                $cmp++;
//            }
//        }
//        return $bool;
//    }

    //==========================================================================
    //COMPARER les réservations de séances et heure de fin
    //==========================================================================
//    Function ComparaisonSSeance($IdMat, $Date, $Heure, $Fin){
//        $res = recupererdateheureS($IdMat, $Date);
//        $bool = false;
//        if(!empty($res['time'])){
//            $cmp = 0;
//            while($cmp <= count($res['time'])-1 && !$bool){
//                if($res['time'][$cmp]['DateFinSeance'] > $Heure || $Fin > $res['time'][$cmp]['DateDebutSeance']){
//                    $bool = true;
//                }
//                $cmp++;
//            }
//        }
//        return $bool;
//    }

    
    //####################################################################################################################################################
    //************************************************************ RESERVATION HORS COURS ****************************************************************
    //####################################################################################################################################################

    
    //==========================================================================
    //RECUPERER infos d'une réservation hors cours
    //==========================================================================
    function RecupererReserverHorscours(){
        $conn = dbConnect();
        $sql = "SELECT RHC.IdResHC, RHC.IdENS, E.PrenomENS, E.NomENS, RHC.IdA, M.IdMat, M.TypeMat, M.NumSerie, DATE_FORMAT(RHC.DateResaHC, '%d/%m/%Y') AS dateresa, DATE_FORMAT(RHC.DateDebutResaHC, '%d/%m/%Y') AS date, DATE_FORMAT(RHC.DateDebutResaHC, '%Hh%i') AS heureD, DATE_FORMAT(RHC.DateFinResaHC, '%Hh%i') AS heureF, RHC.DateResaHC, RHC.DateDebutResaHC, RHC.DateFinResaHC "
                . "FROM RESERVERHORSCOURS RHC, ENSEIGNANT E, MATERIELS M "
                . "WHERE M.IdMat = RHC. IdMat "
                . "AND RHC.IdENS = E.IdENS ";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resResaHC = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resResaHC;
    }
    //==========================================================================
    //CALCULER l'heure de fin d'une réservation hors cours
    //==========================================================================
//    function recupererdateheureHC($IdMat, $Date){
//        $conn = ConnectPDO();
//        date_default_timezone_set('Europe/Paris');
//        $sqlHC = "SELECT DateDebutResaHC, DateFinResaHC "
//                . "FROM RESERVERHORSCOURS "
//                . "WHERE IdMat = $IdMat "
//                . "AND DateResaHC = '$Date'; ";
//        $stmt = $conn->prepare($sqlHC); 
//        $stmt->execute();
//        $resHC = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $arrayTimes = array();
//        for($i=0;$i<=count($resHC)-1;$i++){
//            $arrayTimes[$i]['DateDebutResaHC'] = date('H:i', strtotime($resHC[$i]['DateDebutResaHC']));  
//            $timeHC = array($resHC[$i]['DateDebutResaHC'],  date('H:i', strtotime($resHC[$i]['DateFinResaHC'])));
//            $arrayTimes[$i]['DateFinResaHC'] = sumOfDiffrentTime($timeHC);  
//        }
//        return($arrayTimes);
//    }

    //==========================================================================
    //LIMITER l'heure de fin d'une réservation (RESERVERHORSCOURS)
    //==========================================================================
//    function GetHeureFin($Heure, $Duree){
//        date_default_timezone_set('Europe/Paris');
//        $time = array($Heure, $Duree);
//        $end = sumOfDiffrentTime($time);
//        $timeMax = '19:00';
//        If($end > date('H:i', strtotime($timeMax))){
//            //echo utf8_encode("Réservation trop longue !");
//            return 'creationReserverHC.php';
//        }else{
//            return $end;
//
//        }
//    }

    //==========================================================================
    //COMPARER les réservations hors cours
    //==========================================================================
//    Function ComparaisonHC($IdMatHC, $DateResaHC, $Heure, $Duree){
//        $end = GetHeureFin($Heure, $Duree);
//        $resHC = recupererdateheureHC($IdMatHC, $DateResaHC);
//        $boolean = false;
//        if(!empty($resHC)){ 
//            $cmp = 0;
//            while($cmp <= count($resHC)-1 && !$boolean){
//                if($resHC[$cmp]['HeureFinResaHC'] >= $Heure || $end >= $resHC[$cmp]['HeureDebutResaHC']){
//                    $boolean = true;
//                }
//                $cmp++;
//            }
//        }
//        return $boolean;
//    }

    //==========================================================================
    //COMPARER les réservations hors cours et heure de fin
    //==========================================================================
//    Function ComparaisonHCSeance($IdMatHC, $DateResaHC, $Heure, $Fin){
//        $resHC = recupererdateheureHC($IdMatHC, $DateResaHC);
//        $boolean = false;
//        if(!empty($resHC)){ 
//            $cmp = 0;
//            while($cmp <= count($resHC)-1 && !$boolean){
//                if($resHC[$cmp]['HeureFinResaHC'] >= $Heure || $Fin >= $resHC[$cmp]['HeureDebutResaHC']){
//                    $boolean = true;
//                }
//                $cmp++;
//            }
//        }
//        return $boolean;
//    }

    //==========================================================================
    //INSERER une réservation hors cours
    //==========================================================================
//    function InsererHC($IdE, $IdA, $IdM, $Date, $HeureDeb, $Duree){
//        $cx = ConnectDB();
//        $InsertHC = "INSERT INTO RESERVERHORSCOURS(IdENS, IdA, IdMat, DateResaHC, DateDebutResaHC, DateFinResaHC) "
//                . "VALUES ('$IdE', '$IdA', '$IdM', '$Date', '$HeureDeb','$Duree') ";
//        mysqli_query($cx, $InsertHC);
//        return 'gestionReservation.php';
//    }

    //==========================================================================
    //MODIFIER une réservation hors cours
    //==========================================================================
//    function ModifierReservationHC($IdENSHC,$IdAHC,$IdMatHC,$DateResaHC,$HeureDebutResaHC,$DureeResaHC,$IdResHC){
//        $cx = ConnectDB();
//        $sql = "UPDATE RESERVERHORSCOURS SET IdENS = '$IdENSHC', IdA = '$IdAHC', IdMat= '$IdMatHC', DateResaHC = '$DateResaHC', DateDebutResaHC = '$HeureDebutResaHC', DateFinResaHC = '$DureeResaHC' WHERE IdResHC = $IdResHC ;";
//        $querysql = mysqli_query($cx,$sql);
//        return "gestionReservation.php";
//    }
        
    //==========================================================================
    //SUPPRIMER  une réservation hors cours
    //==========================================================================
    function SupprimerReserverHC($IdENS, $IdA, $IdMat){
        $cx = bdConnect();
        $sql = "DELETE FROM RESERVERHORSCOURS "
                . "WHERE IdENS = $IdENS "
                . "AND IdA = $IdA "
                . "AND IdMat = $IdMat ";
        mysqli_query($cx, $sql); 
        return  "gestionReservation.php" ;
    }
    
    
    // Rechercher les materiels disponibles 
function matdispo($NumS){
       $conn= dbConnect();
    
    $sql = "SELECT DateDebutSeance AS debut, DateFinSeance AS fin FROM SEANCES WHERE NumS=$NumS";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resH = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

   for($i=0;$i<=count($resH)-1;$i++){
       $debut=$resH[$i]['debut'];
       $fin=$resH[$i]['fin'];
   }
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
return $res1 ;
}


function IdMatSelect($IdMat){
     $conn = dbConnect(); 
    $sql1="SELECT MATERIELS.IdMat, MATERIELS.numSerie , MATERIELS.TypeMat
            FROM  MATERIELS
            WHERE MATERIELS.IdMat=$IdMat";
     $stmt = $conn->prepare($sql1); 
     $stmt->execute();
     $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
     return $res1 ;
}

function NumsSelect ($NumS){
     $conn= dbConnect();
    
    $sql = "(SELECT SEANCES.NumS, MATIERES.IntituleM AS titre, DATE_FORMAT(SEANCES.DateDebutSeance, '%d/%m/%Y') AS date, DATE_FORMAT(SEANCES.DateDebutSeance, '%H:%i') AS heureD, DATE_FORMAT(SEANCES.DateFinSeance, '%H:%i') AS heureF
            FROM SEANCES, MATIERES
            WHERE MATIERES.NumM=SEANCES.NumM
            AND SEANCES.NumS=$NumS)
            UNION
            (SELECT SEANCES.NumS, COURS_SPECIAUX.IntituleCSPE AS titre, DATE_FORMAT(SEANCES.DateDebutSeance, '%d/%m/%Y') AS date, DATE_FORMAT(SEANCES.DateDebutSeance, '%H:%i') AS heureD,  DATE_FORMAT(SEANCES.DateFinSeance, '%H:%i') AS heureF
            FROM SEANCES, COURS_SPECIAUX
            WHERE COURS_SPECIAUX.IdCSPE=SEANCES.IdCSPE 
            AND SEANCES.NumS=$NumS)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resH = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resH ;
}

//Rechercher le matériel dispo Reserver HC modifier
function MatDispoHC ($debut , $fin){
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
return $res1;
}
