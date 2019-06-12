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