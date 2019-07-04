<?php
/*
 * Gestion groupes TD
 */

require_once './Core/Manager.php';

function getFormations(){
    $conn = dbConnect();
    $sql = "SELECT IdF, IntituleF FROM FORMATION;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;
}

function getGroupTD($idFormation){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT IdGTD, NumGroupTD FROM GROUPE_TD WHERE IdF = $idFormation ORDER BY IdGTD ASC;");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;       
}

function getEtudiantGroupTD($idGroupTD){
    $conn = dbConnect();
     $sql = "SELECT e.IdE , e.NomE, e.PrenomE
                 FROM ETUDIANT e , APPARTIENT a
                 WHERE a.IdE=e.IdE
                 And a.IdGTD = $idGroupTD;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);    
    return $res;    
}

function getEtudiantNonaffecté($IdF){
    $conn = dbConnect();
     $sql = "SELECT IdE ,NomE, PrenomE
                 FROM ETUDIANT 
                 WHERE IdF ='$IdF'
                 And IdE NOT IN (SELECT IdE
                                 FROM APPARTIENT)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;   
}

function addEtudiantTD($IdE, $IdGTD){
    $cx = bdConnect();
    $sql = "INSERT INTO APPARTIENT (IdGTD,IdE) Value ('$IdGTD','$IdE')";
    $querysql = mysqli_query($cx,$sql);
}

function deleteEtduaintTD($IdE){
    $cx = bdConnect();
    $sql = "DELETE FROM APPARTIENT WHERE IdE = $IdE;";
    $querysql = mysqli_query($cx,$sql);    
}

/*
 * Réservations
 */

function RecupererEnseignant(){
    $conn = dbConnect();
    $sql = "SELECT IdENS, PrenomENS, NomENS, TypeENS, IdMdp "
            . "FROM ENSEIGNANT ";  
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resENS = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resENS;
}

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

function RecupererMateriel(){
    $conn = dbConnect();
    $sql = "SELECT IdMat, TypeMat "
            . "FROM MATERIELS ";  
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resMateriel = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resMateriel;
}

function RecupererReserver(){
    $conn = dbConnect();
    $sql = "SELECT R.IdRes, R.IdENS, E.PrenomENS, E.NomENS, R.IdA, M.IdMat, M.TypeMat, M.NumSerie, S.NumS, MA.NumM, MA.IntituleM as intitule, "
            . "DATE_FORMAT(S.DateDebutSeance, '%d/%m/%Y') AS date, DATE_FORMAT(S.DateDebutSeance, '%Hh%i') AS debut, DATE_FORMAT(S.DateFinSeance, '%Hh%i') AS fin , "
            . "DATE_FORMAT(R.DateResa, '%d/%m/%Y') AS resa "
            . "FROM RESERVER R,ENSEIGNANT E,SEANCES S, MATERIELS M, MATIERES MA "
            . "WHERE M.IdMat = R.IdMat "
            . "AND R.IdENS = E.IdENS "
            . "AND S.NumS = R.NumS "
            . "AND S.NumM = MA.NumM ";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resCours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sql = "SELECT R.IdRes, R.IdENS, E.PrenomENS, E.NomENS, R.IdA, M.IdMat, M.TypeMat, M.NumSerie, S.NumS, C.IdCSPE, C.IntituleCSPE as intitule,"
            . " DATE_FORMAT(S.DateDebutSeance, '%d/%m/%Y') AS date, DATE_FORMAT(S.DateDebutSeance, '%Hh%i') AS debut, DATE_FORMAT(S.DateFinSeance, '%Hh%i') AS fin ,"
            . " DATE_FORMAT(R.DateResa, '%d/%m/%Y') AS resa "
            . "FROM RESERVER R,ENSEIGNANT E,SEANCES S, MATERIELS M, COURS_SPECIAUX C "
            . "WHERE M.IdMat = R.IdMat "
            . "AND R.IdENS = E.IdENS "
            . "AND S.NumS = R.NumS "
            . "AND S.IdCSPE = C.IdCSPE ";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resCoursSPe = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res = array_merge($resCours, $resCoursSPe);
    return $res;
}

function getReservation($id){
    $conn = dbConnect();
    $sql = "SELECT IdRes, IdENS, IdMat, NumS FROM RESERVER WHERE IdRes = $id;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resSeances = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resSeances;    
}

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

function insertReservation($data){
    $conn = dbConnect();
    $IdENS = $data['IdENS'];
    $IdA = $_SESSION['id'];
    $IdMat = $data['IdMat'];
    $NumS = $data['NumS'];     
    $sql = "INSERT INTO RESERVER (IdENS , IdA ,NumS,  IdMat , DateResa) VALUES ($IdENS , $IdA , $NumS  , $IdMat , NOW());";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();    
}

function updateReservation($data){
        $IdRes = $data['IdRes'];
        $IdMat = $data['IdMat'];
        $numS = $data['NumS'];
        $idEns = $data['IdENS'];
        $idA = $_SESSION['id'];
        $conn = dbConnect(); 
        $sql = "UPDATE RESERVER SET IdMat = $IdMat, NumS = $numS, IdEns = $idEns, IdA = $idA, DateResa = NOW() WHERE IdRes = $IdRes;";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();    
}

function deleteReservation($id){
    $cx = bdConnect();
    $sql = "DELETE FROM RESERVER WHERE IdRes = $id;";
    mysqli_query($cx, $sql);     
}

function RecupererReserverHorscours(){
    $conn = dbConnect();
    $sql = "SELECT RHC.IdResHC, RHC.IdENS, E.PrenomENS, E.NomENS, RHC.IdA, M.IdMat, M.TypeMat, M.NumSerie, DATE_FORMAT(RHC.DateResaHC, '%d/%m/%Y') AS dateresa, "
            . "DATE_FORMAT(RHC.DateDebutResaHC, '%d/%m/%Y') AS date, DATE_FORMAT(RHC.DateDebutResaHC, '%Hh%i') AS heureD, DATE_FORMAT(RHC.DateFinResaHC, '%Hh%i') AS heureF, "
            . "RHC.DateResaHC, RHC.DateDebutResaHC, RHC.DateFinResaHC "
            . "FROM RESERVERHORSCOURS RHC, ENSEIGNANT E, MATERIELS M "
            . "WHERE M.IdMat = RHC. IdMat "
            . "AND RHC.IdENS = E.IdENS ";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resResaHC = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resResaHC;
}

function deleteReservationHC($id){
    $cx = bdConnect();
    $sql = "DELETE FROM RESERVERHORSCOURS WHERE IdResHC = $id;";
    mysqli_query($cx, $sql); 
}

function insertReservationHC($data){
    date_default_timezone_set(TIMEZONE);
    $idens = $data['IdENSHC'];
    $date = $data['date'];
    $heureDebut = $data['debut'];
    $heureFin = $data['fin'];
    $idmat = $data['IdMatHC'];
    $IdA = $_SESSION['id'];    
    $debut = date("Y/m/d H:i:s", strtotime("$date $heureDebut"));
    $fin = date("Y/m/d H:i:s", strtotime("$date $heureFin"));   
    $conn = dbConnect();
    $sql = "INSERT INTO RESERVERHORSCOURS (IdENS , IdA , DateDebutResaHC , DateFinResaHC , IdMat , DateResaHC) VALUES ($idens, $IdA, '$debut', '$fin', $idmat, NOW());";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
}

function updateReservationHC($data){
    $idReservation = $data['IdResHC'];
    $idens = $data['IdENSHC'];
    $date = $data['date'];
    $heureDebut = $data['debut'];
    $heureFin = $data['fin'];
    $idmat = $data['IdMatHC'];
    $IdA = $_SESSION['id'];    
    $debut = date("Y/m/d H:i:s", strtotime("$date $heureDebut"));
    $fin = date("Y/m/d H:i:s", strtotime("$date $heureFin"));
    $conn = dbConnect();
    $sql = "UPDATE RESERVERHORSCOURS SET IdEns = $idens, IdA = $IdA, IdMat = $idmat, DateResaHC = NOW(), DateDebutResaHC = '$debut', DateFinResaHC = '$fin' "
            . "WHERE IdResHC = $idReservation;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();    
}

function getReservationHC($id){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT IdResHC, IdEns, IdA, IdMat, DateDebutResaHC, DateFinResaHC FROM RESERVERHORSCOURS WHERE IdResHC = $id;"); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res;
}

/*
 * Gestion planning
 */

 function RecupMCM($IdF){
    $conn = dbConnect();
    $sql = "SELECT MATIERES.NumM, MATIERES.IntituleM FROM MATIERES, UNITE_ENSEIGNEMENT "
            . "WHERE MATIERES.IdUE=UNITE_ENSEIGNEMENT.IdUE AND UNITE_ENSEIGNEMENT.IdF=$IdF AND MATIERES.TypeM='CM'";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resM = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resM;
}

function RecupMTD($IdF){
    $conn = dbConnect();
    $sql = "SELECT MATIERES.NumM, MATIERES.IntituleM FROM MATIERES, UNITE_ENSEIGNEMENT "
            . "WHERE MATIERES.IdUE=UNITE_ENSEIGNEMENT.IdUE AND UNITE_ENSEIGNEMENT.IdF=$IdF AND MATIERES.TypeM='TD'";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resM = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resM;
}
    
 function RecupCSPE(){
    $conn = dbConnect();
    $sql = "SELECT IdCSPE, IntituleCSPE FROM COURS_SPECIAUX";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resCSPE = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resCSPE;
}

//Fonction d'affichage des groupes TD
 function RecupGroupeTD($IdF){
    $conn = dbConnect();
    $sql = "SELECT IdGTD, NumGroupTD FROM GROUPE_TD WHERE IdF=$IdF";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resG;
}

//Fonction d'affichage des groupes CM
 function RecupGroupeCM($IdF){
    $conn = dbConnect();
    $sql = "SELECT IdGCM, NumGroupCM FROM GROUPE_CM WHERE IdF=$IdF";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resG;
}

function horaireSalleDispo($DateDebut,$DateFin){    
    $conn = dbConnect(); 
    $sql1="SELECT distinct(SALLE.IdS) , SALLE.NomS , SITE.NomSITE , SALLE.CapaciteS 
            FROM  SALLE , SITE
            WHERE SALLE.IdSITE=SITE.IdSITE
            AND SALLE.IdS NOT IN (
                                SELECT SEANCES.IdS
                                FROM SEANCES
                                WHERE SEANCES.DateDebutSeance >= '$DateDebut' AND SEANCES.DateDebutSeance < '$DateFin')
            AND SALLE.IdS NOT IN (
                                SELECT SEANCES.IdS
                                FROM SEANCES
                                WHERE SEANCES.DateFinSeance > '$DateDebut' AND SEANCES.DateFinSeance <= '$DateFin')
             AND SALLE.IdS NOT IN (
                                SELECT SEANCES.IdS
                                FROM SEANCES
                                WHERE SEANCES.DateDebutSeance < '$DateDebut' 
                                AND SEANCES.DateFinSeance > '$DateFin')";        
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetchAll();
    return $res1;
}

function RecupNomEns($idens){
    $conn = dbConnect();
    $sql = "SELECT NomENS, PrenomENS FROM ENSEIGNANT WHERE ENSEIGNANT.IdENS = $idens;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resE = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resE;
}

 function RecupNomFormation($idf){
    $conn = dbConnect();
    $sql = "SELECT IntituleF FROM FORMATION WHERE FORMATION.IdF= $idf;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resF = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resF;
}

function insertSeanceCM($params){
    $conn = dbConnect();
    $ids = $params['ids'];
    $CM = $params['grpcm'];
    $Matiere = $params['titleCM'];
    $start = $params['start'];
    $end = $params['end'];
    $idens = $params['IdENS'];
    $query = "INSERT INTO SEANCES (NumM, IdS, IdGCM, DateDebutSeance, DateFinSeance) VALUES ($Matiere, $ids, $CM, '$start','$end')";
    $stmt = $conn->prepare($query); 
    $stmt->execute();
    $NumS= NewNumS1($ids,$CM,$Matiere,$start);
    $sql = "INSERT INTO DISPENSE (IdENS , NumS) VALUES ($idens,$NumS)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();         
}

function insertSeanceCMspe($params){
    $conn = dbConnect();
    $ids = $params['ids'];
    $CM = $params['grpcm'];
    $Spe = $params['titleCS'];
    $start = $params['start'];
    $end = $params['end'];
    $idens = $params['IdENS'];
    $query1 = "INSERT INTO SEANCES (IdCSPE, IdS, IdGCM, DateDebutSeance, DateFinSeance) VALUES ($Spe, $ids, $CM, '$start','$end')";
    $stmt = $conn->prepare($query1); 
    $stmt->execute();
    $NumS= NewNumS2($ids,$CM,$Spe,$start);
    $sql1 = "INSERT INTO DISPENSE (IdENS , NumS) VALUES ($idens,$NumS)";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();  
}

function insertSeanceTD($params){
    $conn = dbConnect();
    $ids = $params['ids'];
    $TD = $params['grptd'];
    $Matiere = $params['titleTD'];
    $start = $params['start'];
    $end = $params['end'];
    $idens = $params['IdENS'];
    $query2 = "INSERT INTO SEANCES 
             (NumM, IdS, IdGTD, DateDebutSeance, DateFinSeance) 
              VALUES ($Matiere, $ids, $TD, '$start','$end')";
    $stmt = $conn->prepare($query2); 
    $stmt->execute();
    $NumS= NewNumS3($ids,$TD,$Matiere,$start);
    $sql2 = "INSERT INTO DISPENSE (IdENS , NumS) VALUES ($idens,$NumS)";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();     
}

function insertSeanceTDspe($params){
    $conn = dbConnect();
    $ids = $params['ids'];
    $TD = $params['grptd'];
    $Spe = $params['titleCS'];
    $start = $params['start'];
    $end = $params['end'];
    $idens = $params['IdENS'];
    $query3 = "INSERT INTO SEANCES 
             (IdCSPE, IdS, IdGTD, DateDebutSeance, DateFinSeance) 
              VALUES ($Spe, $ids, $TD, '$start','$end')";
    $stmt = $conn->prepare($query3); 
    $stmt->execute();
    $NumS= NewNumS4($ids,$TD,$Spe,$start);
    $sql3 = "INSERT INTO DISPENSE (IdENS , NumS) VALUES ($idens,$NumS)";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();      
}

function NewNumS1($ids,$CM,$Matiere,$start){
    $conn = dbConnect();
    $sql = "SELECT NumS
            FROM SEANCES 
            WHERE NumM=$Matiere
            AND IdS = $ids
            AND IdGCM = $CM
            AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];
}

function NewNumS2($ids,$CM,$Spe,$start){
    $conn = dbConnect();
    $sql1 = "SELECT NumS FROM SEANCES WHERE IdCSPE=$Spe AND IdS = $ids AND IdGCM = $CM AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];
}

function NewNumS3($ids,$TD,$Matiere,$start){
    $conn = dbConnect();
    $sql2 = "SELECT NumS
    FROM SEANCES 
    WHERE NumM=$Matiere
    AND IdS = $ids
    AND IdGTD = $TD
    AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];
}

function NewNumS4($ids,$TD,$Spe,$start){
    $conn = dbConnect();
    $sql3 = "SELECT NumS
    FROM SEANCES 
    WHERE IdCSPE=$Spe
    AND IdS = $ids
    AND IdGTD = $TD
    AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];           
}