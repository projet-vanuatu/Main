<?php
@session_start();
require_once './Core/Manager.php';
require_once 'fonctionsUtiles.php';
$connect = dbConnect();

if(isset($_POST["id"])){
    if(horaireSalle($_POST["id"],$_POST["start"],$_POST["end"])){
        if(horaireEns($_POST["id"],$_POST["start"],$_POST["end"])){    
            //if(horaireCM($_POST["id"],$_POST["start"],$_POST["end"])){
            $query = "UPDATE SEANCES SET DateDebutSeance=:start_event, DateFinSeance=:end_event WHERE NumS=:id;";
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                    ':start_event' => $_POST['start'],
                    ':end_event' => $_POST['end'],
                    ':id'   => $_POST['id']
                )
            );            
            $rep=array('msg' => 'Séance modifiée');
        }else{
            $rep=array('msg' => "L'enseignant a déja cours à cet horaire");
        }
    }else{        
        $rep=array('msg' => "La salle n'est pas disponible à cet horaire");
    }
    $_SESSION['error'] = $rep;
}

echo isset($_SESSION['error']) ? utf8_encode(json_encode($_SESSION['error'])) : "";