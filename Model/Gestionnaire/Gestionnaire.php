<?php

require_once FPUBLIC.DS.'Core/Manager.php';

/*
 * Gestion groupes TD
 */

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

//####################################################################################################################################################
//************************************************************** RESERVATION COURS *******************************************************************
//####################################################################################################################################################

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
//SUPPRIMER  une réservation d'une séance
//==========================================================================
function SupprimerReserver($IdRes){
    $cx = ConnectDB();
    $sql = "DELETE FROM RESERVER "
            . "WHERE IdRes = $IdRes ";
    mysqli_query($cx, $sql); 
    return "gestionReservation.php";
    }

//####################################################################################################################################################
//************************************************************ RESERVATION HORS COURS ****************************************************************
//####################################################################################################################################################

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
