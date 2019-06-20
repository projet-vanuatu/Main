<?php
    @session_start();
    require_once 'fonctionReservation.php';
    $resENS = RecupererEnseignant();
    $Nom=$_SESSION['nom'];
    $Prenom = $_SESSION['prenom'];   
    // CONDITION ACTION
    if(!empty($_GET['action'])=='action'){
        $action =$_GET['action'];
    }else{
        $action="";
    }
    // CONDITION SELECTION ACTION
    if(!empty($_GET['IdResHC'])){
        $IdResHC = $_GET['IdResHC'];
        $IdENSHC = $_GET['IdENSHC'];
        $IdAHC = '21100905';
        $IdMatHC = $_GET['IdMatHC'];
        $DateResaHC = $_GET['DateResaHC'];
        $HeureDebutResaHC = $_GET['HeureDebutResaHC'];
        $DureeResaHC = $_GET['DureeResaHC'];
        $action = 'modifierHC';
        $btn = 'Modifier';
        $MatSelect = IdMatSelect($IdMatHC);
        $MatDispoHC =  MatDispoHC ($HeureDebutResaHC , $DureeResaHC);
    }else{ 
        $IdResHC = "";
        $IdENSHC = "";
        $IdAHC ="";
        $IdMatHC="";
        $DateResaHC = "";
        $HeureDebutResaHC = "";
        $DureeResaHC = "";
        $action= 'InsererReservationHC';
        $btn = 'Ajouter';
        $MatSelect ="";
    }
?>
<html>
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="Public/Css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="Public/Css/bootstrap.css" type="text/css">
        <script src="Public/JavaScript/jquery.js"></script>
        <script src="Public/JavaScript/myJavaScript.js" type="text/javascript"></script>
        <title>Insertion Hors séance</title>
    </head>
    <body>
        <script>
            function change_valeurM(){
                var select = document.getElementById('debut').value;
                var select2 = document.getElementById('fin').value;
                var test = AjaxM(select, select2);
                var listeMat = '<select class="form-control" id="mat" name="IdMatHC" required><option value="">Choisir un matériel</option>';
                $.each($.parseJSON(test), function(i, obj) {
                    listeMat += '<option value='+obj.IdMat+'>n°: '+obj.numSerie+' '+obj.TypeMat+'</option>';
                }); 
                listeMat += '</select>';
                document.getElementById('Mat').innerHTML = listeMat;   
            }
            function AjaxM(select, select2) {
                return $.ajax({
                    url:'MatRHC.php',
                    type:'POST',
                    data:{debut:select, fin:select2},
                    async: false
                }).responseText;
            }
        </script>
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
                <button class="subnavbtn3"><span class="glyphicon glyphicon-user"></span>&nbsp<?php echo $Nom;?>&nbsp<?php echo $Prenom?> </button>
            </div>
        </div>
        <div class="container">
            <br>
            <div class="row content">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <br>
                    <h4>Insertion d'une réservation hors séance</h4>
                    <br>
                    <form action="actionReservation.php" method = 'GET'>
                        <input type="hidden" name="action" value="<?php echo $action ?>">
                            <?php 
                                echo ($action == 'modifierHC') ? "<input type='hidden' name='IdResHC' value='$IdResHC'>" : ""; 
                            ?>
                        <div class="form-group">                  
                            <label for="pwd">Enseignant :</label>                        
                            <select <?php if($action == 'modifierHC'){ echo 'disabled';}?> class="form-control" id="typeSalle" name="IdENSHC" required>
                                <option value="">Choisir un enseignant</option>
                                    <?php 
                                        for ($i=0;$i<=count($resENS)-1;$i++){
                                    ?>
                                <option <?php if($IdENSHC == $resENS[$i]['IdENS']){ echo 'Selected';}?> value="<?php echo $resENS[$i]['IdENS']?>"><?php echo utf8_encode($resENS[$i]['NomENS']." ".$resENS[$i]['PrenomENS'])?></option>
                                    <?php
                                        }
                                    ?>
                            </select>
                             <input <?php if($action != 'modifierHC'){ echo 'disabled';}?> hidden value ="<?php echo  $resENS[$i]['IdENS'] ?>" name ='IdENSHC'>
                        </div>
                         <div class="form-group">
                            <span>
                                <label for="pwd">Début de réservation :<p style="color: #ff8533; font-style: italic">Format : YYYY-MM-DD hh:mm:ss </p></label>
                                <input   <?php if($action == 'modifierHC'){ echo 'disabled';}?> onkeyup="change_valeurM()" type="text" class="form-control" id="debut" placeholder="ex : 2019-06-14 08:30:00" name="HeureDebutResaHC" value='<?php echo $HeureDebutResaHC?>' required>
                             <input <?php if($action != 'modifierHC'){ echo 'disabled';}?> hidden value ="<?php echo $HeureDebutResaHC?>" name ='HeureDebutResaHC'>
                            </span>

                        </div>
                        <div class="form-group">
                            <label for="pwd">Fin de réservation :<p style="color: #ff8533; font-style: italic">Format : YYYY-MM-DD hh:mm:ss</p></label>
                            <input   <?php if($action == 'modifierHC'){ echo 'disabled';}?> onkeyup="change_valeurM()" type="text" class="form-control" id="fin" placeholder="ex : 2019-06-14 11:00:00" name="DureeResaHC" value='<?php echo $DureeResaHC?>' required>
                            <input <?php if($action != 'modifierHC'){ echo 'disabled';}?> hidden value ="<?php echo $DureeResaHC?>" name ='DureeResaHC'>
                        </div> 
                        <div class="form-group" id="Mat">
                            <label for="pwd">Matériel :</label>                        
                            <select class="form-control" id="typeSalle" name="IdMatHC" required>
                                <option value="">Choisir un matériel</option>
                                <?php 
                                    if($action == 'modifierHC'){
                                        for ($i=0;$i<=count( $MatSelect)-1;$i++){
                                ?>
                                            <option Selected  value="<?php echo  $IdMatHC?>">
                                                <?php echo  utf8_encode($MatSelect[$i]['numSerie'])."  ". utf8_encode( $MatSelect[$i]['TypeMat'])?></option>;
                                <?php
                                        }
                                        for ($i=0;$i<=count($MatDispoHC)-1;$i++){
                                ?>
                                            <option   value="<?php echo $MatDispoHC[$i]['IdMat']?>">
                                                <?php echo utf8_encode($MatDispoHC[$i]['numSerie']." ".$MatDispoHC[$i]['TypeMat'])?></option>
                                <?php
                                        }                                       
                                    }
                                ?>
                            </select>
                        </div>    
                        <button type="submit" class="btn btn-primary btn-block"><?php echo $btn; ?></button>
                    </form>      
                    <a href='gestionReservation.php' style="text-decoration: none"><button type="submit" class="btn btn-primary btn-block" style="background-color: grey; border-color: grey">Retour</button></a>
                    <br>
                    <a href="creationReserver.php" style="font-weight: bold; color: blue; text-decoration: none">>>> Ou créer une réservation <strong style="color: green">pour une séance..</strong></a>
                </div>
                <div class="col-sm-4"></div>
            </div>
        <div class="col-sm-1"></div>
      </div> 
    </body>
</html>

