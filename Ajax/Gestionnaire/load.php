<?php

@session_start();

require_once '../../Core/Manager.php';
require_once '../../Core/Model.php';
require_once '../../Config/Config.php';

if(isset($_SESSION['enseignantPlanning']) && $_SESSION['formationPlanning']){
    $idens = $_SESSION['enseignantPlanning'];
    $idf = $_SESSION['formationPlanning'];    
}else{
    $idens = 0;
    $idf = 0;     
}

$var = dbCredentials();
$connect = dbConnect($var);

$NumS = RecupNumS($connect, $idens, $idf);
$res1 = Recupinfo($connect, $NumS);

$data = array();
$result = $res1;

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

function RecupNumS($conn, $idens, $idf){       
    $sql = "(SELECT SEANCES.NumS
        FROM SEANCES 
        WHERE  SEANCES.IdGCM IN (SELECT IdGCM FROM GROUPE_CM WHERE IdF=$idf)
        OR SEANCES.IdGTD IN (SELECT IdGTD FROM GROUPE_TD WHERE IdF=$idf) 
        ORDER BY SEANCES.NumS)    
        UNION(SELECT SEANCES.NumS
        FROM SEANCES , DISPENSE
        WHERE SEANCES.NumS=DISPENSE.NumS
        AND DISPENSE.IdENS = $idens
        ORDER BY SEANCES.NumS)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resNumS = $stmt->fetchAll(PDO::FETCH_ASSOC);    
    return $resNumS;       
}

echo (isset($data)) ? utf8_encode(json_encode($data)) : "";
