<?php
@session_start();
require_once './Core/Manager.php';
require 'fonctionsUtiles.php';
$connect = dbConnect();
$idens = $_SESSION['idens'];
$idf=$_SESSION['idf'];
$start = $_GET['start'];
$end = $_GET['end'];

if($_GET['Typec']=='CM'){
    $conn = dbConnect();
    if($_GET['Typem']=='ST'){
        $conn = dbConnect();
        $ids = $_GET['ids'];
        $CM = $_GET['grpcm'];
        $Matiere = $_GET['titleCM']; 
        $query = "INSERT INTO SEANCES 
                  (NumM, IdS, IdGCM, DateDebutSeance, DateFinSeance) 
                   VALUES ($Matiere, $ids, $CM, '$start','$end')";
        $stmt = $conn->prepare($query); 
        $stmt->execute();
        $NumS= NewNumS1($ids,$CM,$Matiere,$start);
        $sql = "INSERT INTO DISPENSE (IdENS , NumS) value ($idens,$NumS)";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();       
    }elseif ($_GET['Typem']=='SP'){
        $conn = dbConnect();
        $ids = $_GET['ids'];
        $CM = $_GET['grpcm'];
        $Spe = $_GET['titleCS'];
        $query1 = "INSERT INTO SEANCES 
                 (IdCSPE, IdS, IdGCM, DateDebutSeance, DateFinSeance) 
                  VALUES ($Spe, $ids, $CM, '$start','$end')";
        $stmt = $conn->prepare($query1); 
        $stmt->execute();
        $NumS= NewNumS2($ids,$CM,$Spe,$start);
        $sql1 = "INSERT INTO DISPENSE (IdENS , NumS) value ($idens,$NumS)";
        $stmt = $conn->prepare($sql1); 
        $stmt->execute(); ;
    }
}elseif($_GET['Typec']=='TD'){
    $conn = dbConnect();
    if($_GET['Typem']=='ST'){
        $conn = dbConnect();
        $ids = $_GET['ids'];
        $TD = $_GET['grptd'];
        $Matiere = $_GET['titleTD'];
        $query2 = "INSERT INTO SEANCES 
                 (NumM, IdS, IdGTD, DateDebutSeance, DateFinSeance) 
                  VALUES ($Matiere, $ids, $TD, '$start','$end')";
        $stmt = $conn->prepare($query2); 
        $stmt->execute();
        $NumS= NewNumS3($ids,$TD,$Matiere,$start);
        $sql2 = "INSERT INTO DISPENSE (IdENS , NumS) value ($idens,$NumS)";
        $stmt = $conn->prepare($sql2); 
        $stmt->execute();  
    }elseif ($_GET['Typem']=='SP'){
        $conn = dbConnect();
        $ids = $_GET['ids'];
        $TD = $_GET['grptd'];
        $Spe = $_GET['titleCS'];
        $query3 = "INSERT INTO SEANCES 
                 (IdCSPE, IdS, IdGTD, DateDebutSeance, DateFinSeance) 
                  VALUES ($Spe, $ids, $TD, '$start','$end')";
        $stmt = $conn->prepare($query3); 
        $stmt->execute();
        $NumS= NewNumS4($ids,$TD,$Spe,$start);
        $sql3 = "INSERT INTO DISPENSE (IdENS , NumS) value ($idens,$NumS)";
        $stmt = $conn->prepare($sql3); 
        $stmt->execute();  
    }
    
}
if ($_GET['Valider']=='Valider'){
header("Location:GestionPlanning.php?idens=$idens&idf=$idf");
}else{
header("Location:creationReserver.php?idens=$idens&nums=$NumS&action=ResC");  
}
function NewNumS1($ids,$CM,$Matiere,$start){
    $conn = dbConnect();
    $sql = "SELECT NumS
            FROM SEANCES 
            WHERE NumM=$Matiere
            AND IdS = $ids
            AND IdGCM = $CM
            AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];
}

function NewNumS2($ids,$CM,$Spe,$start){
    $conn = dbConnect();
    $sql1 = "SELECT NumS
    FROM SEANCES 
    WHERE IdCSPE=$Spe
    AND IdS = $ids
    AND IdGCM = $CM
    AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];
}

function NewNumS3($ids,$TD,$Matiere,$start){
    $conn = dbConnect();
    $sql2 = "SELECT NumS
    FROM SEANCES 
    WHERE NumM=$Matiere
    AND IdS = $ids
    AND IdGTD = $TD
    AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];
}


function NewNumS4($ids,$TD,$Spe,$start){
    $conn = dbConnect();
    $sql3 = "SELECT NumS
    FROM SEANCES 
    WHERE IdCSPE=$Spe
    AND IdS = $ids
    AND IdGTD = $TD
    AND DateDebutSeance = '$start'";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['NumS'];            
}


            
        
           












