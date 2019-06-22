<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="Public/Css/style.css" rel="stylesheet" type="text/css"/>      
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="Public/JavaScript/jquery.js"></script>
        <script src="Public/JavaScript/fullCalendar.js"></script>
        <script src="Public/JavaScript/myJavaScript.js" type="text/javascript"></script>
        <title><?php echo $title; ?></title>
    </head>
        
    <body>
        <div class="myNavbar">
            <div class="navbar-header" style="width:52px; float:left;">
                <img src="Public/Images/logo.JPG" style="width:52px;" alt=""/>
            </div>
            <a href="index.php?action=index">Accueil</a>
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
                    <a href="#company">Par formation</a>
                    <a href="#company">Par salle</a>
                    <a href="#company">Par enseignant</a>
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