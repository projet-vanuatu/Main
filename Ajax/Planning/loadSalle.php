<?php
@session_start();

require_once '../../Core/Model.php';
require_once '../../Core/Manager.php';

$connect = dbConnect();

$IdS = $_SESSION['criteria'];

$NumS = recupSeanceSalle($IdS);
$res1 = Recupinfo($NumS);
$data = array();
$result = $res1;

if($IdS != ""){
    if(!empty($result)){
        foreach($result as $row){
            $data[] = array(
            'id'   => $row[0]["NumS"],
            'start'   => $row[0]["DateDebutSeance"],
            'groupe'  => $row[0]["groupe"],
            'title'   => utf8_encode($row[0]["title"]),
            'nomS'   => utf8_encode($row[0]["NomS"]),
            'end'   => $row[0]["DateFinSeance"],
            'nomens'=> utf8_encode($row[0]['NomENS']),
            'prenomens'=> utf8_encode($row[0]['PrenomENS']),
            'site'=> utf8_encode($row[0]['NomSITE']),
            'NomF'=> utf8_encode($row[0]['IntituleF']),
            'color'=> $row[0]['couleur'],
            );
    }
    }else{
        $data = array();
    }
    echo utf8_encode (json_encode($data));
}

function recupSeanceSalle($IdS){
    $conn = dbConnect();
    $sql = "SELECT NumS "
            . "FROM SEANCES "
            . "WHERE SEANCES.IdS=$IdS";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resG;
}

