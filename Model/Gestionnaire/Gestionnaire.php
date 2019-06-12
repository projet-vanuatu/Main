<?php

require_once FPUBLIC.DS.'Core/Manager.php';

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

