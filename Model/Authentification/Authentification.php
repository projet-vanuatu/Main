<?php

/* Model */

require_once FPUBLIC.DS.'Core/Manager.php';

function getConnexion($data){
    $db = dbConnect();
    $id = $data['id'];
    $mdp = $data['pwd'];
    $stmt = $db->prepare("SELECT A.idA, A.StatutA, A.NomA, A.PrenomA FROM ADMINISTRATION A, CODES C WHERE C.idMdp = A.idMdp AND A.idA = $id AND C.Mdp = '$mdp';");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;  
}

function getConnexionEnseignant($data){
    $db = dbConnect();
    $id = $data['id'];
    $mdp = $data['pwd'];
    $stmt = $db->prepare("SELECT e.idENS, e.NomENS, e.PrenomENS FROM ENSEIGNANT e, CODES C WHERE C.idMdp = e.idMdp AND e.idENS = $id AND C.Mdp = '$mdp';");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;      
}

function getConnexionEtudiant($data){
    $db = dbConnect();
    $id = $data['id'];
    $mdp = $data['pwd'];
    $stmt = $db->prepare("SELECT e.idE, e.NomE, e.PrenomE FROM ETUDIANT e, CODES C WHERE C.idMdp = e.idMdp AND e.idE = $id AND C.Mdp = '$mdp';");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;      
}