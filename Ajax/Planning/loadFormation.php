<?php
@session_start();

require_once '../../Core/Model.php';
require_once '../../Core/Manager.php';
require_once '../../Config/Config.php';

$var = dbCredentials();
$conn = dbConnect($var);

if(isset($_SESSION['criteria'])){
    $IdF = $_SESSION['criteria'];
}else{
   $IdF = ""; 
}


if($IdF != ""){
    $NumS = recupSeanceForm($conn, $IdF);
    $res1 = Recupinfo($conn, $NumS);
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
    echo utf8_encode (json_encode($data));
}

function recupSeanceForm($conn, $IdF){
    $sql = "(SELECT NumS "
            . "FROM SEANCES, GROUPE_TD "
            . "WHERE GROUPE_TD.IdGTD=SEANCES.IdGTD "
            . "AND GROUPE_TD.IdF=$IdF)"
            . "UNION"
            . "(SELECT NumS "
            . "FROM SEANCES, GROUPE_CM "
            . "WHERE GROUPE_CM.IdGCM=SEANCES.IdGCM "
            . "AND GROUPE_CM.IdF=$IdF)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resG;
}
