<?php
    require_once 'fonctionReservation.php';
    
    //==========================================================================
    //INSERER une réservation pour une séance
    //==========================================================================
    if($_GET['action'] == 'InsererReservation'||$_GET['action'] == 'ResC'){
        $IdENS = $_GET['IdENS'];
        $IdA = '21100905';
        $IdMat = $_GET['IdMat'];
        $NumS = $_GET['NumS'];     
        $conn =  dbConnect(); 
        $sql = "INSERT INTO RESERVER (IdENS , IdA ,NumS,  IdMat , DateResa) VALUES ($IdENS , $IdA , $NumS  , $IdMat , NOW() )";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        header('location:gestionReservation.php');
            
   

    }

    //==========================================================================
    //MODIFIER une réservation d'une séance
    //==========================================================================
    if($_GET['action']=='modifier'){
        $IdRes = $_GET['IdRes'];
        $IdMat = $_GET['IdMat'];
        $conn = dbConnect(); 
        $sql = "UPDATE RESERVER SET  IdMat = $IdMat WHERE IdRes =$IdRes";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        header('location:gestionReservation.php');
        
    }

    //==========================================================================
    //SUPPRIMER une réservation d'une séance
    //==========================================================================
    if($_GET['action'] == 'supprimer'){
        $IdRes = $_GET['IdRes'];
        $Sup=SupprimerReserver($IdRes); 
        header("Location:".$Sup);
    }

    //==========================================================================
    //INSERER une réservation hors cours
    //==========================================================================
    
  If($_GET['action']== 'InsererReservationHC'){
      $idens = $_GET['IdENSHC'];
      $debut = $_GET['HeureDebutResaHC'];
      $fin = $_GET['DureeResaHC'];
      $idmat = $_GET['IdMatHC'];
      $IdA = '21100905';
  
    $conn = dbConnect(); 
    $sql = "INSERT INTO RESERVERHORSCOURS (IdENS , IdA , DateDebutResaHC , DateFinResaHC , IdMat , DateResaHC) VALUES ($idens , $IdA , '$debut' , '$fin' , $idmat , NOW() )";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    header('location:gestionReservation.php');
  }
    //==========================================================================
    //MODIFIER une réservation hors cours
    //==========================================================================
    if($_GET['action']=='modifierHC'){
          $IdRes = $_GET['IdResHC'];
        $IdMat = $_GET['IdMatHC'];
        $conn = dbConnect(); 
        $sql = "UPDATE RESERVERHORSCOURS SET  IdMat = $IdMat WHERE IdResHC =$IdRes";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        header('location:gestionReservation.php');
    }

    //==========================================================================
    //SUPPRIMER une réservation hors cours
    //==========================================================================
    if(filter_input(INPUT_GET, 'action') == 'supprimerHC'){
        $IdENSHC = $_GET['IdENSHC']; 
        $IdAHC = filter_input(INPUT_GET, 'IdAHC');
        $IdMatHC = filter_input(INPUT_GET, 'IdMatHC');
        $Sup=SupprimerReserverHC($IdENSHC,$IdAHC,$IdMatHC); 
        header("Location:".$Sup); 
    }
?>