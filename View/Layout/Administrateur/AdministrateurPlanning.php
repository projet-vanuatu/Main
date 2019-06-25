<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">           
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="Public/JavaScript/jquery.js"></script>
        <script src="Public/JavaScript/fullCalendar.js"></script>
        <script src="Public/JavaScript/myJavaScript.js" type="text/javascript"></script>
        <link rel="stylesheet" href="Public/Css/style.css" rel="stylesheet" type="text/css"/> 
        <title><?php echo $title; ?></title>
    </head>
        
    <body class="bg-global">
        <div class="myNavbar">
            <div class="navbar-header" style="width:52px; float:left;">
                <img src="Public/Images/logo.JPG" style="width:52px;" alt=""/>
            </div>
            <a href="index.php?action=index">Accueil</a>
            <div class="subnav" id="subnavCreation">
                <button class="subnavbtn" id="btnCreation">Création &nbsp;<i class="fa fa-caret-down"></i></button>
                <div class="subnav-content" id="navCreation">
                    <a href="index.php?action=creerUtilisateur">Utilisateurs</a>
                    <a href="index.php?action=creerFormation">Formations</a>
                    <a href="index.php?action=creerUE">Matières/UE</a>
                    <a href="index.php?action=creerSalle">Salles/Sites</a>
                    <a href="index.php?action=creerMateriel">Matériels</a>
                </div>
            </div> 
            <div class="subnav" id="subnavGestion">
                <button class="subnavbtn" id="btnGestion">Gestion &nbsp;<i class="fa fa-caret-down"></i></button>
                <div class="subnav-content" id="navGestion">
                    <a href="index.php?action=gererUtilisateur">Utilisateurs</a>
                    <a href="index.php?action=gererFormation">Formations</a>
                    <a href="index.php?action=gererMatiereUE">Matières/UE</a>
                    <a href="index.php?action=gererSalle">Salles/Sites</a>
                    <a href="index.php?action=gererMateriel">Matériels</a>
                </div>
            </div>
            <a href="index.php?action=administration">Gestion des CSV</a>
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