<?php
@session_start();

require_once '../../Core/Manager.php';
require_once '../../Core/Model.php';
require_once '../../Core/Define.php';
require_once '../../Config/Config.php';

$var = dbCredentials();
$connect = dbConnect($var);

if(isset($_POST["id"])){
    if(horaireSalle($connect, $_POST["id"],$_POST["start"],$_POST["end"])){
        if(horaireEns($connect, $_POST["id"],$_POST["start"],$_POST["end"])){
            
            date_default_timezone_set(TIMEZONE);
            
            $id = $_POST["id"];
            $start = $_POST["start"];
            $end = $_POST["end"];
            
            $infoSeance = getSeanceInfoLog($connect, $id);
            $formation = $infoSeance['IntituleF'];
            $date = date("d/m/Y", strtotime($infoSeance['DateDebutSeance']));
            $debut = date("H:i", strtotime($infoSeance['DateDebutSeance']));
            $fin = date("H:i", strtotime($infoSeance['DateFinSeance']));
            $enseignant = $infoSeance['NomENS']." ".$infoSeance['PrenomENS'];
            $matiere = $infoSeance['title'];
            $td = $infoSeance['groupe'];
            $salle = $infoSeance['NomS'];
            
            $newDate = date("d/m/Y", strtotime($start));
            $newDebut = date("H:i", strtotime($start));
            $newFin = date("H:i", strtotime($end));
            
            $action = $formation." - ".$td." - ".$matiere." - ".$enseignant." le : ".$date." de : "
                .$debut." à ".$fin." dans la salle : ".$salle. " - Déplacé le : ".$newDate." de ".$newDebut." à ".$newFin;
            writeLog('Déplacé la séance : ', $action, '../../Logs/');

            $query = "UPDATE SEANCES SET DateDebutSeance=:start_event, DateFinSeance=:end_event WHERE NumS=:id";
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                  ':start_event' => $start,
                  ':end_event' => $end,
                  ':id'   => $id
                )
            ); 
            
            $rep = array('msg' => NULL);
        }else{
            $rep=array('msg' => "L'enseignant a déja cours à cet horaire");
        }
    }else{        
        $rep=array('msg' => "La salle n'est pas disponible à cet horaire");
    } 
    $_SESSION['error'] = $rep;
}

function horaireSalle($conn, $NumS,$DateDebut,$DateFin){
    $IdS = IdSalle($conn, $NumS);
    $sql1="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES
            WHERE SEANCES.NumS <> $NumS
            AND SEANCES.IdS= $IdS
            AND SEANCES.DateDebutSeance >= '$DateDebut' AND SEANCES.DateDebutSeance < '$DateFin'";         
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetchAll();
    
    $sql2="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES
            WHERE SEANCES.NumS <> $NumS
            AND SEANCES.IdS= $IdS
            AND SEANCES.DateFinSeance > '$DateDebut' AND SEANCES.DateFinSeance <= '$DateFin'";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res2 = $stmt->fetchAll();
                

    $sql3="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES
            WHERE SEANCES.NumS <> $NumS
            AND SEANCES.IdS= $IdS
            AND SEANCES.DateDebutSeance < '$DateDebut' 
            AND SEANCES.DateFinSeance > '$DateFin'";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();
    $res3 = $stmt->fetchAll();
    
    if(count($res1)!=0||count($res2)!=0||count($res3)!=0){
        return false;
    }else{
        return true;
    }
}

function horaireEns($conn, $NumS,$DateDebut,$DateFin){
    $IdEns = IdEns($conn, $NumS);
    $sql2="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES, DISPENSE
            WHERE DISPENSE.NumS=SEANCES.NumS 
            AND DISPENSE.NumS <> $NumS
            AND DISPENSE.IdEns= $IdEns
            AND SEANCES.DateDebutSeance >= '$DateDebut' AND SEANCES.DateDebutSeance < '$DateFin'";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res2 = $stmt->fetchAll();
    
    $sql3="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES, DISPENSE
            WHERE DISPENSE.NumS=SEANCES.NumS 
            AND DISPENSE.NumS <> $NumS
            AND DISPENSE.IdEns= $IdEns
             AND SEANCES.DateFinSeance > '$DateDebut' AND SEANCES.DateFinSeance <= '$DateFin'";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();
    $res3 = $stmt->fetchAll();

    $sql4="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES, DISPENSE
            WHERE DISPENSE.NumS=SEANCES.NumS 
            AND DISPENSE.NumS <> $NumS
            AND DISPENSE.IdEns= $IdEns
            AND SEANCES.DateDebutSeance < '$DateDebut' 
            AND SEANCES.DateFinSeance > '$DateFin'"; 
    $stmt = $conn->prepare($sql4); 
    $stmt->execute();
    $res4 = $stmt->fetchAll();
    
    if(count($res2)!=0||count($res3)!=0||count($res4)!=0){
        return false;
    }else{
        return true;
    }
}

function IdSalle($conn, $NumS){
    $sql="SELECT SEANCES.IdS FROM SEANCES WHERE SEANCES.NumS = $NumS;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch();
    return $res[0];
}

function IdEns($conn, $NumS){
    $conn = dbConnect(); 
    $sql1="SELECT DISPENSE.IdENS FROM DISPENSE WHERE DISPENSE.NumS = $NumS;";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetch();
    return $res1[0];
}

echo isset($_SESSION['error']) ? utf8_encode(json_encode($_SESSION['error'])) : "";