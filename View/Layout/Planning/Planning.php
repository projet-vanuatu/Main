<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">           
        <link rel="stylesheet" href="Public/Css/boostrap4.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="Public/Css/fullCalendar.css" rel="stylesheet" type="text/css"/>
        <script src="Public/JavaScript/jquery-min.js"></script>
        <script src="Public/JavaScript/jquery-ui-min.js"></script>
        <script src="Public/JavaScript/moment-min.js"></script>
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
            <div class="subnav">
                <button class="subnavbtn">Consulter planning <span><img src="Public/Glyphicons/fleche.png"  style="height: 8px;" alt=""/></span></button>               
                <div class="subnav-content">
                    <a href="index.php?action=consulterPlanning&type=planningFormation">Par formation</a>
                    <a href="index.php?action=consulterPlanning&type=planningSalle">Par salle</a>
                    <a href="index.php?action=consulterPlanning&type=planningEnseingnant">Par enseignant</a>
                </div>
            </div>
            <div class="subnav2">
                <a href = "index.php?action=connnection" class="subnavbtn2">Connexion&nbsp;<span><img src="Public/Glyphicons/connexion.png"  style="height: 15px;" alt=""/></span></a>
            </div>
        </div>
    	<?php 
            echo $content;
        ?>
    </body>
</html>
