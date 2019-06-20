<!DOCTYPE html>
<html>
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
            <a href="index.php?action=administration">Administration</a>
            <div class="subnav" id="subnavConsultation">
                <button class="subnavbtn" id="btnConsultation">Consulter planning &nbsp;<i class="fa fa-caret-down"></i></button>
                <div class="subnav-content" id="navConsult">
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