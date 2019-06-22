<?php
@session_start();

require_once '../Core/Manager.php';

$connect = dbConnect();

if(isset($_POST["id"])){
    if(horaireSalle($connect, $_POST["id"],$_POST["start"],$_POST["end"])){
        if(horaireEns($connect, $_POST["id"],$_POST["start"],$_POST["end"])){    
            $query = "UPDATE SEANCES SET DateDebutSeance=:start_event, DateFinSeance=:end_event WHERE NumS=:id";
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                  ':start_event' => $_POST['start'],
                  ':end_event' => $_POST['end'],
                  ':id'   => $_POST['id']
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
    $IdS = IdSalle($NumS);
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
    $IdEns = IdEns($NumS);
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

function IdSalle($NumS){
    $conn = dbConnect(); 
    $sql="SELECT SEANCES.IdS FROM SEANCES WHERE SEANCES.NumS = $NumS;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch();
    return $res[0];
}

function IdEns($NumS){
    $conn = dbConnect(); 
    $sql1="SELECT DISPENSE.IdENS FROM DISPENSE WHERE DISPENSE.NumS = $NumS;";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetch();
    return $res1[0];
}

echo isset($_SESSION['error']) ? utf8_encode(json_encode($_SESSION['error'])) : "";