<?php    
require_once 'fonctionReservation.php';
$Nom=$_SESSION['nom'];
$Prenom = $_SESSION['prenom'];
$resENS = RecupererEnseignant();
$resMateriel= RecupererMateriel();
$resMatiere = RecupererMatieres();

// CONDITION ACTION
if(!empty($_GET['action'])=='action'){
    $action =$_GET['action'];
}else{
    $action="";
}
// CONDITION SELECTION ACTION
if(!empty($_GET['IdRes'])){
    $IdRes = $_GET['IdRes'];
    $IdENS = $_GET['IdENS'];
    $IdA = $_SESSION['ID'];
    $IdMat = $_GET['IdMat'];
    $NumS = $_GET['NumS'];
    $action =$_GET['action'];

    $btn = 'Modifier';
    $NumsSelect =  NumsSelect ($NumS);
    $MatSelect = IdMatSelect($IdMat);
    $matdispo = matdispo($NumS);
}else{ 
    $IdRes = "";
    $IdENS = "";
    $IdA ="";
    $IdMat="";
    $NumS="";
    $action ='InsererReservation';
    $btn = 'Ajouter';
    $matdispo = "";
    $MatSelect = "";
    $NumsSelect = "";
}

if(!empty($_GET["idens"])&&!empty($_GET['nums'])){
    $idens = $_GET['idens'];
    $nums = $_GET['nums'];
    $action = $_GET['action'];
    $NumsSelect =  NumsSelect ($nums);
    $Matdispo  =  matdispo($nums);
}else{
    $idens="";
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
        <title>Insertion pour Séance</title>
    </head>
    <body>
        <script>
            function change_valeurS(){
                var select = document.getElementById('idens').value;
                var test = AjaxS(select);
                var seance = document.getElementById('numseance').value;
                var listeSeance = '<label for="pwd">Séance :</label><select class="form-control" name="NumS" id="ids" onchange="change_valeurM()" required><option>Sélectionner une séance</option>';
                $.each($.parseJSON(test), function(i, obj) {
                    if(obj.NumS === seance){
                    listeSeance += '<option selected value='+obj.NumS+'>'+obj.titre+' le '+obj.date+' de '+obj.heureD+' à '+obj.heureF+'</option>';    
                    }else{
                    listeSeance += '<option value='+obj.NumS+'>'+obj.titre+' le '+obj.date+' de '+obj.heureD+' à '+obj.heureF+'</option>';
                    }}); 
                listeSeance += '</select>';
                document.getElementById('seanceDispo').innerHTML = listeSeance;   
            }
            function AjaxS(select) {
                return $.ajax({
                    url:'seanceDispo.php',
                    type:'POST',
                    data:{idens:select},
                    async: false
                }).responseText;
            }   
            function change_valeurM(){
                var select = document.getElementById('ids').value;
                var test = AjaxM(select);
                var listeMat = '<label for="pwd">Matériel :</label><select class="form-control" name="IdMat" required><option value="">Choisir un matériel</option>';
                $.each($.parseJSON(test), function(i, obj) {
                    listeMat += '<option value='+obj.IdMat+'>n°: '+obj.numSerie+' '+obj.TypeMat+'</option>';
                }); 
                listeMat += '</select>';
                document.getElementById('MatDispo').innerHTML = listeMat;   
            }
            function AjaxM(select) {
                return $.ajax({
                    url:'matDispo.php',
                    type:'POST',
                    data:{ids:select},
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
                    <h4>Insertion d'une réservation pour une séance</h4>
                    <br>
                    <form action="actionReservation.php" method = 'GET'>
                        <input type="hidden" name="action" value="<?php echo $action ?>">
                            <?php 
                                echo ($action == 'modifier') ? "<input type='hidden' name='IdRes' value='$IdRes'>" : ""; 
                            ?>
                            <div class="form-group">
                                <label for="pwd">Enseignant :</label>                        
                                <select <?php if($action == 'modifier'||$action == 'ResC'){ echo 'disabled';}?> class="form-control" name="IdENS" id="idens" onchange="change_valeurS();" required>
                                    <option value="">Choisir un enseignant</option>
                                        <?php 
                                        
                                            for ($i=0;$i<=count($resENS)-1;$i++){
                                        ?>
                                    <option <?php if($idens == $resENS[$i]['IdENS'] || $IdENS == $resENS[$i]['IdENS']){ echo 'Selected';}?> value="<?php echo $resENS[$i]['IdENS']?>"><?php echo utf8_encode($resENS[$i]['NomENS']." ".$resENS[$i]['PrenomENS'])?></option>
                                        
                                            <?php
                                        
                                            }                                           
                                        ?>
                                </select>
                                <input <?php if($action != 'ResC'){ echo 'disabled';}?> hidden value ="<?php   if($action =='ResC'){echo $idens;}?>" name ='IdENS'>
                            </div>
                        <input hidden id="numseance" value="<?php echo $NumS ?>"></input>  
                            <div class="form-group" id="seanceDispo">
                                <label for="pwd">Séance :</label>
                                <select  <?php if($action == 'modifier'||$action == 'ResC'){ echo 'disabled';} ?>  class="form-control" name="NumS" id="ids" required>
                                    <option>Sélectionner une séance</option>
                                     <?php 
                                    if($action == 'modifier'||$action == 'ResC'){
                                        for ($i=0;$i<=count( $NumsSelect)-1;$i++){
                                        ?>
                                    <option Selected  value="<?php echo  $NumS?>"><?php echo  utf8_encode($NumsSelect[$i]['titre'])." Le : ". utf8_encode( $NumsSelect[$i]['date']." de ". $NumsSelect[$i]['heureD'])." à ".utf8_encode($NumsSelect[$i]['heureF'])?></option>;
                                        
                                        <?php
                                    }}
                                    ?>
                                </select>
                                <input <?php if($action != 'ResC'){ echo 'disabled';}?> hidden value ="<?php if($action =='ResC'){echo $nums;}?>" name ='NumS'>                              
                            </div>
                        <div class="form-group" id="MatDispo">
                                <label for="pwd">Matériel :</label>                        
                                <select class="form-control" name="IdMat" required>
                                    <option value="">Choisir un matériel</option>                                   
                                    <?php 
                                    if($action == 'modifier'){
                                        for ($i=0;$i<=count($MatSelect)-1;$i++){
                                    ?>
                                            <option  Selected  value="<?php echo $MatSelect[$i]['IdMat']?>">
                                                <?php echo utf8_encode($MatSelect[$i]['numSerie']." ".$MatSelect[$i]['TypeMat'])?></option>
                                    <?php
                                        }                                       
                                        for ($i=0;$i<=count($matdispo)-1;$i++){
                                    ?>
                                            <option   value="<?php echo $matdispo[$i]['IdMat']?>"><?php echo utf8_encode($matdispo[$i]['numSerie']." ".$matdispo[$i]['TypeMat'])?></option>
                                        <?php
                                        }                                       
                                    }elseif($action == 'ResC'){
                                        for ($i=0;$i<=count($Matdispo)-1;$i++){
                                        ?>
                                    <option  value="<?php echo $Matdispo[$i]['IdMat']?>"><?php echo utf8_encode($Matdispo[$i]['numSerie']." ".$Matdispo[$i]['TypeMat'])?></option>
                                    <?php
                                         }
                                    }
                                    ?>                                                                            
                                </select>
                            </div> 
                        <button type="submit" class="btn btn-primary btn-block" ><?php echo $btn; ?></button>
                    </form>      
                    <a href='gestionReservation.php' style="text-decoration: none"><button type="submit" class="btn btn-primary btn-block" style="background-color: grey; border-color: grey" name = 'action'>Retour</button></a>
                    <br>
                    <a href="creationReserverHC.php" style="font-weight: bold; color: blue; text-decoration: none;">>>> Ou créer une réservation <strong style="color: green">hors séance..</strong></a>
                </div>
                <div class="col-sm-4"></div>
            </div>
        </div>
    </body>
</html>