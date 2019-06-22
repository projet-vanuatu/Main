<?php
@session_start();

require_once '../Core/Manager.php';

$connect = dbConnect();

//delete.php

if(isset($_POST["id"])){
    $query1 = "DELETE from DISPENSE WHERE NumS=:id";
    $statement = $connect->prepare($query1);
    $statement->execute(
        array(
           ':id' => $_POST['id']
        )
    );  
    $query2 = "DELETE from RESERVER WHERE NumS=:id";
    $statement = $connect->prepare($query2);
    $statement->execute(
        array(
            ':id' => $_POST['id']
        )
    ); 
    $query3 = "DELETE from SEANCES WHERE NumS=:id";
    $statement = $connect->prepare($query3);
    $statement->execute(
        array(
            ':id' => $_POST['id']
        )
    );
}
