<?php
    @session_start();
    require_once 'fonctionReservation.php';
    $resResa = RecupererReserver();
    $resResaHC = RecupererReserverHorscours();
    $Nom=$_SESSION['nom'];
    $Prenom = $_SESSION['prenom'];
?>
<html lang="fr">
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="Public/Css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="Public/Css/bootstrap.css" type="text/css">
        <script src="Public/JavaScript/jquery.js"></script>
        <script src="Public/JavaScript/myJavaScript.js" type="text/javascript"></script>
        <title>Gestion Réservation</title>
    </head>
    <body>  
        <div class="myNavbar">
            <div class="navbar-header">
                <img src="Public/Images/logo.JPG" style="width:52px;" alt=""/>
            </div>
            <a href="index.php?action=index">Accueil</a>
            <a href="GestionPlanning.php">Gestion du planning</a>
            <a href="index.php?action=gererAffectationTD">Gestion groupes TD</a>
            <div class="subnav">
                <button class="subnavbtn">Réservations &nbsp;<i class="fa fa-caret-down"></i></button>
                <div class="subnav-content">
                    <a href="gestionReservation.php">Gérer réservations</a>
                    <a href="creationReserver.php">Créer réservation</a>
                    <a href="creationReserverHC.php">Créer réservation hors cours</a>
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
                <a href = "index.php?action=deconnection" class="subnavbtn2">Deconnection&nbsp;<span class="glyphicon glyphicon-log-in"></span></a>
            </div>
            <div class="subnav2">
                <button class="subnavbtn3"><span class="glyphicon glyphicon-user"></span>&nbsp<?php echo $Nom;?>&nbsp<?php echo $Prenom?></button>
            </div>
        </div>
        <div class="container">
            <br>
            <br>
        <br>
        <div class="container">
            <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link acstive" onclick="afficherR();">Réservations des séances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="afficherRHC()">Réservations hors séances</a>
                    </li>               
            </ul>
        </div>
        <br>
            <div class="row"></div>
            <div class="container">
                <br>
                <br>
                <div class="row" style="display:block;" id="searchR">
                        <div class="col-sm-6" >
                            <input type="text" class="form-control" id="inputR" onkeyup="myFunction();" placeholder="Rechercher.." title="">
                        </div>
                        <script>
                            $(document).ready(
                            function(){
                                $("#inputR").on("keyup", function(){
                                var value = $(this).val().toLowerCase();
                                    $("#tableR tr").filter(function(){
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                            });
                            function afficherR(){
                                document.getElementById("searchR").style.display = "block";
                                document.getElementById("searchRHC").style.display = "none";
                            }
                            function afficherRHC(){
                                document.getElementById("searchR").style.display = "none";
                                document.getElementById("searchRHC").style.display = "block";
                            }
                        </script>                    
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                        <h3>Réservations de séances &nbsp;&nbsp;<a href="creationReserver.php"><button type="button" class="btn btn-success">Créer</button></a></h3>
                        <table class="table table-hover" id="tableR" >
                            <thead>
                                 <th>Nom</th>
                                 <th>Prénom</th>
                                 <th>Matériel</th>
                                 <th>Numéro de série</th>
                                 <th>Matière</th>
                                 <th>Reservé le :</th>
                                 <th>Date de la séance</th>
                                 <th>Début</th>
                                 <th>Fin</th>
                            </thead>
                            <tbody>
                                <?php
                                    for($i=0;$i<=count($resResa)-1;$i++){
                                ?>
                                    <tr>               
                                        <td><?php echo utf8_encode($resResa[$i]['NomENS']); ?></td>
                                        <td><?php echo utf8_encode($resResa[$i]['PrenomENS']); ?></td>
                                        <td><?php echo utf8_encode($resResa[$i]['TypeMat']); ?></td> 
                                        <td><?php echo $resResa[$i]['NumSerie']; ?></td>
                                        <td><?php echo utf8_encode($resResa[$i]['IntituleM']); ?></td>
                                        <td><?php echo $resResa[$i]['resa']; ?></td>
                                        <td><?php echo $resResa[$i]['date']; ?></td>
                                        <td><?php echo $resResa[$i]['debut']; ?></td>
                                        <td><?php echo $resResa[$i]['fin']; ?></td> 
                                        <?php
                                            $IdRes = $resResa[$i]['IdRes'];
                                            $IdENS = $resResa[$i]['IdENS'];
                                            $IdA = $resResa[$i]['IdA'];
                                            $IdMat = $resResa[$i]['IdMat'];   
                                            $NumS = $resResa[$i]['NumS']; 
                                        ?>
                                        <td>
                                            <p> 
                                                <a href="creationReserver.php?action=modifier&IdRes=<?php echo $IdRes; ?>&IdENS=<?php echo $IdENS; ?>&IdA=<?php echo $IdA?>&IdMat=<?php echo $IdMat ?>&NumS=<?php echo $NumS ?>">
                                                <button type="button" class="btn btn-warning">Modifier</button>
                                                </a>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <a href="actionReservation.php?action=supprimer&IdRes=<?php echo $IdRes; ?>&IdENS=<?php echo $IdENS; ?>&IdA=<?php echo $IdA?>&IdMat=<?php echo $IdMat ?>&NumS=<?php echo $NumS ?>">
                                                <button type="button" class="btn btn-danger" >Supprimer</button>
                                                </a>
                                            </p>

                                        </td>
                                    </tr>                   
                                <?php
                                    }                                     
                                ?>   
                            </tbody>
                        </table>
                    </div>   
                </div>
                <div class="row" style="display:none" id="searchRHC">
                        <div class="col-sm-6" >
                            <input type="text" class="form-control" id="inputRHC" onkeyup="myFunction();" placeholder="Rechercher.." title="">
                        </div>
                        <script>
                            $(document).ready(
                            function(){
                                $("#inputRHC").on("keyup", function(){
                                var value = $(this).val().toLowerCase();
                                    $("#tableRHC tr").filter(function(){
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                            });
                        </script>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                        <h3>Réservations hors séances &nbsp;&nbsp;<a href="creationReserverHC.php"><button type="button" class="btn btn-success">Créer</button></a></h3>
                        <table class="table table-hover"  id="tableRHC" >
                            <thead>
                                 <th>Nom</th>
                                 <th>Prénom</th>
                                 <th>Matériel</th>
                                 <th>Numéro de série</th>
                                 <th>Reservé le :</th>
                                 <th>Date</th>
                                 <th>Debut</th>
                                 <th>Fin</th>
                            </thead>
                            <tbody>
                                <?php
                                    for($i=0;$i<=count($resResaHC)-1;$i++){
                                ?>
                                    <tr>
                                        <!-- AFFICHAGE DONNEES -->
                                        <td><?php echo  utf8_encode($resResaHC[$i]['NomENS']); ?></td>
                                        <td><?php echo  utf8_encode($resResaHC[$i]['PrenomENS']); ?></td>
                                        <td><?php echo  utf8_encode($resResaHC[$i]['TypeMat']); ?></td> 
                                        <td><?php echo  utf8_encode($resResaHC[$i]['NumSerie']); ?></td> 
                                        <td><?php echo utf8_encode($resResaHC[$i]['dateresa']); ?></td>
                                        <td><?php echo $resResaHC[$i]['date']; ?></td>
                                        <td><?php echo $resResaHC[$i]['heureD']; ?></td>
                                        <td><?php echo $resResaHC[$i]['heureF']; ?></td>
                                        <!-- RECUPERER DONNEES -->
                                        <?php
                                        $IdENSHC = $resResaHC[$i]['IdENS'];
                                        $IdAHC = $resResaHC[$i]['IdA'];
                                        $IdMatHC = $resResaHC[$i]['IdMat'];   
                                        $IdResHC = $resResaHC[$i]['IdResHC'];   
                                        $DateResaHC = utf8_encode($resResaHC[$i]['DateResaHC']);
                                        $HeureDebutResaHC = $resResaHC[$i]['DateDebutResaHC'];
                                        $DureeResaHC = $resResaHC[$i]['DateFinResaHC'];
                                        ?>
                                        <td>
                                            <p>
                                                <a href="creationReserverHC.php?action=modifierHC&IdResHC=<?php echo $IdResHC;?>&IdENSHC=<?php echo $IdENSHC; ?>&IdAHC=<?php echo $IdAHC?>&IdMatHC=<?php echo $IdMatHC ?>&DateResaHC=<?php echo $DateResaHC ?>&HeureDebutResaHC=<?php echo $HeureDebutResaHC ?>&DureeResaHC=<?php echo $DureeResaHC ?>">
                                                    <button type="button" class="btn btn-warning">Modifier</button>
                                                </a>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <a href="actionReservation.php?action=supprimerHC&IdResHC=<?php echo $IdResHC;?>&IdENSHC=<?php echo $IdENSHC; ?>&IdAHC=<?php echo $IdAHC?>&IdMatHC=<?php echo $IdMatHC ?>">
                                                    <button type="button" class="btn btn-danger" >Supprimer</button>
                                                </a>
                                            </p>
                                        </td>
                                    </tr>                   
                                <?php
                                    }                                     
                                ?>
                            </tbody>
                        </table>
                    </div>  
                </div>
            </div>
        </div>
    </body>
</html>