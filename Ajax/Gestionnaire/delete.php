<?php
@session_start();

require_once '../../Core/Manager.php';
require_once '../../Core/Model.php';
require_once '../../Core/Define.php';
require_once '../../Config/Config.php';

$var = dbCredentials();
$connect = dbConnect($var);

//delete.php

if(isset($_POST["id"])){
    
    date_default_timezone_set(TIMEZONE);
    
    $id = $_POST["id"];
    
    $infoSeance = getSeanceInfoLog($connect, $id);
    $formation = $infoSeance[0]['IntituleF'];
    $date = date("d/m/Y", strtotime($infoSeance[0]['DateDebutSeance']));
    $debut = date("H:i", strtotime($infoSeance[0]['DateDebutSeance']));
    $fin = date("H:i", strtotime($infoSeance[0]['DateFinSeance']));
    $enseignant = $infoSeance[0]['NomENS']." ".$infoSeance[0]['PrenomENS'];
    $matiere = $infoSeance[0]['title'];
    $td = $infoSeance[0]['groupe'];
    $salle = $infoSeance[0]['NomS'];
    $action = $formation." - ".$td." - ".$matiere." - ".$enseignant." le : ".$date." de : "
        .$debut." à ".$fin." dans la salle : ".$salle;
    writeLog('Supprimer la séance : ', $action, '../../Logs/');
    
    $query1 = "DELETE from DISPENSE WHERE NumS=:id";
    $statement = $connect->prepare($query1);
    $statement->execute(
        array(
           ':id' => $id
        )
    );  
    $query2 = "DELETE from RESERVER WHERE NumS=:id";
    $statement = $connect->prepare($query2);
    $statement->execute(
        array(
            ':id' => $id
        )
    ); 
    $query3 = "DELETE from SEANCES WHERE NumS=:id";
    $statement = $connect->prepare($query3);
    $statement->execute(
        array(
            ':id' => $id
        )
    );
}
