<?php
if(isset($data['formulaire'])){
    $btnNav = 'Retour';
    $action = 'Modifier';
    $actionForm = 'index.php?action=modifierSalle&id='.$data['formulaire']['IdSITE'];
    $nomSite = $data['formulaire']['NomSITE'];
    $TypeS = ucfirst($data['formulaire']['TypeS']);
}else{
    $btnNav = 'Gérer salles';
    $action = 'Ajouter';
    $actionForm = 'index.php?action=creerSalle';
    $nomSite = "";
    $TypeS = "";
}
?>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2><center>Ajouter salle</center></h2>
        </div>           
        <div class="col-sm-4">
            <a href="index.php?action=gererSalle"><button type="button" class="btn btn-primary" style="margin-top:25px; float:right;"><?php echo $btnNav; ?></button></a>
        </div>
    </div>
</div>
<div class="container" <?php echo ($action == 'Modifier') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="index.php?action=creerSalle">Salles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=creerSite">Site</a>
        </li>
    </ul>
</div>
<br>
<div class="container" id="InsertSalle">
    <div class="row content">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form action="<?php echo $actionForm; ?>" method="POST">                   
                <div class="form-group">
                    <label for="pwd">Nom :</label>
                    <input type="text" class="form-control" id="nomSal" placeholder="Nom de la salle" name="nomSalle"
                           value='<?php echo isset($data['formulaire']['NomS']) ? $data['formulaire']['NomS'] : ""; ?>' required>
                </div>
                <div class="form-group"> 
                    <label for="sel1">Capacité maximale de la salle :</label>
                    <input type="number" maxlength="3" max="200" class="form-control" id="capMaxSalle" placeholder="Nombre entre 1 et 200" name="capaciteMaxSalle" 
                           value ='<?php echo isset($data['formulaire']['CapaciteS']) ? $data['formulaire']['CapaciteS'] : ""; ?>' required>
                </div>
                <div class="form-group"> 
                    <label for="sel1">Type de salle :</label>
                    <select class="form-control" id="typeSalle" name="choixTypeSalle" required>
                    <?php 
                        if($TypeS == 'Cours'){$Cours='Selected'; $Informatique=""; $NoSelect="";}
                        elseif($TypeS =='Informatique'){$Cours=''; $Informatique="Selected"; $NoSelect="";}
                        else{$Cours=''; $Informatique=""; $NoSelect="Selected";}
                    ?>
                        <option value="" <?php echo $NoSelect?>>Choisir un type de salle</option>
                        <option value="cours" <?php echo $Cours?>>Cours</option>
                        <option value="informatique" <?php echo $Informatique?>>Informatique</option>                       
                    </select>
                </div>
                <div class="form-group"> 
                    <label for="sel1">Site :</label>
                    <select class="form-control" name="choixSite" required>
                        <option selected >Choisir un site</option>
                            <?php             
                            for($i=0;$i<=count($data['sites'])-1;$i++){
                            ?>
                            <Option <?php  if($nomSite == $data['sites'][$i]['NomSITE']){echo 'Selected';} ?>
                                value ="<?php echo $data['sites'][$i]['IdSITE'] ?>"><?php echo $data['sites'][$i]['NomSITE'] ?></option>     
                            <?php
                            }
                            ?>
                    </select>
                </div>
                <?php echo isset($data['formulaire']['IdS']) ? "<input type='hidden' value='".$data['formulaire']['IdS']."' name='IdS'>" : ""; ?>
                <button type="submit" class="btn btn-primary btn-block" value="OK"><?php echo $action; ?></button>
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>

<script>
    function afficherEtudiant(){
        document.getElementById("Etudiants").style.display = "block";
        document.getElementById("Enseignants").style.display = "none";
        document.getElementById("Gestionnaires").style.display = "none";
    }
    function afficherEnseignant(){
        document.getElementById("Etudiants").style.display = "none";
        document.getElementById("Enseignants").style.display = "block";
        document.getElementById("Gestionnaires").style.display = "none";
    }
    function afficherGestionnaire(){
        document.getElementById("Etudiants").style.display = "none";
        document.getElementById("Enseignants").style.display = "none";
        document.getElementById("Gestionnaires").style.display = "block";
    }
</script>
