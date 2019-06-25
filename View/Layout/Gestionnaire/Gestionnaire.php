<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="Public/Css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="Public/Css/bootstrap.css" type="text/css">
        <script src="Public/JavaScript/jquery.js"></script>
        <script src="Public/JavaScript/myJavaScript.js" type="text/javascript"></script>      
        <title><?php echo $title; ?></title>
    </head>
        
    <body class="bg-global">
        <div class="myNavbar">
            <div class="navbar-header">
                <img src="Public/Images/logo.JPG" style="width:52px;" alt=""/>
            </div>
            <a href="index.php?action=index">Accueil</a>
            <a href="index.php?action=gererEtudiant">Liste étudiant</a>
            <a href="index.php?action=gererPlanning">Gestion du planning</a>
            <a href="index.php?action=gererAffectationTD">Gestion groupes TD</a>
            <div class="subnav">
                <button class="subnavbtn">Réservations &nbsp;<i class="fa fa-caret-down"></i></button>
                <div class="subnav-content">
                    <a href="index.php?action=gererReservation">Gérer réservations</a>
                    <a href="index.php?action=creerReservation">Créer réservation</a>
                    <a href="index.php?action=creerReservationHC">Créer réservation hors cours</a>
                </div>
            </div>
            <div class="subnav">
                <button class="subnavbtn">Consulter planning &nbsp;<i class="fa fa-caret-down"></i></button>
                <div class="subnav-content">
                    <a href="index.php?action=consulterPlanning&type=planningFormation">Par formation</a>
                    <a href="index.php?action=consulterPlanning&type=planningSalle">Par salle</a>
                    <a href="index.php?action=consulterPlanning&type=planningEnseingnant">Par enseignant</a>
                </div>
            </div>
            <div class="subnav2">
                    <a href = "index.php?action=deconnection" class="subnavbtn2">Deconnexion&nbsp;<span class="glyphicon glyphicon-log-in"></span></a>
            </div>
            <div class="subnav2">
                <button class="subnavbtn3"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?></button>
            </div>
        </div>
    	<?php 
            echo $content;
        ?>
</html>