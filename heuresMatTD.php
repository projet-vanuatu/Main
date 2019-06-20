<?php
@session_start();
require_once './Core/Manager.php';
//Fonction de recherche du materiel équipé dans la salle
if(isset($_POST["numm"]) && !empty($_POST["numm"])){
    $numm=$_POST["numm"];
     $grp=$_POST["grp"];
    $conn= dbConnect();
    $sql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(SEANCES.DateFinSeance, SEANCES.DateDebutSeance)))) AS HRest, MATIERES.NbHeuresFixees
            FROM SEANCES, GROUPE_TD, MATIERES 
            WHERE MATIERES.NumM=$numm
            AND SEANCES.IdGTD=GROUPE_TD.IdGTD 
            AND GROUPE_TD.IdGTD=$grp
            AND SEANCES.NumM=MATIERES.NumM 
            GROUP BY MATIERES.NbHeuresFixees";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resMa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(count($resMa)!=0){
    $_SESSION["Matiere"]=$resMa;
    }else{
       $sql1 = "SELECT MATIERES.NbHeuresFixees
            FROM MATIERES 
            WHERE MATIERES.NumM=$numm";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $resMa1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION["Matiere"]=$resMa1;
    }
}
if(isset($_SESSION['Matiere'])){
    print_r(utf8_encode(json_encode($_SESSION['Matiere'])));
}
