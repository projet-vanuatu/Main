<?php
@session_start();

require_once '../../Core/Model.php';
require_once '../../Core/Manager.php';
require_once '../../Config/Config.php';

$var = dbCredentials();
$connect = dbConnect($var);

$IdE=$_SESSION['id'];

$NumS = RecupCours($connect, $IdE);
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

echo utf8_encode (json_encode($data));

function RecupCours($conn, $IdE){       
    $sql = "    (SELECT DISTINCT SEANCES.NumS
                FROM SEANCES, ETUDIANT
                WHERE SEANCES.IdGCM=ETUDIANT.IdGCM
                AND ETUDIANT.IdE = $IdE)
                UNION 
                (SELECT DISTINCT SEANCES.NumS
                FROM SEANCES, APPARTIENT, GROUPE_TD
                WHERE SEANCES.IdGTD=APPARTIENT.IdGTD
                AND APPARTIENT.IdE = $IdE)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resNumS = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resNumS;        
}
