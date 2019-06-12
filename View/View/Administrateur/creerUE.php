<?php
if(isset($data['formulaire'])){
    $btnNav = 'Retour';
    $action = 'Modifier';
    $actionForm = 'index.php?action=modifierUE';
    $idFormation = $data['formulaire']['IdF'];
}else{
    $btnNav = "Gerer UE";
    $action = 'Ajouter';
    $actionForm = 'index.php?action=creerUE';
    $idFormation = "";
}
?>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <h2><center>Ajouter unité d'enseignement</center></h2>
        </div>           
        <div class="col-sm-3">
            <a href="index.php?action=gererMatiereUE&activeParams=ue"><button type="button" class="btn btn-primary" style="margin-top:25px; float:right;"><?php echo $btnNav; ?></button></a>
        </div>
    </div>
</div>
<div class="container" <?php echo ($action == 'Modifier') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="index.php?action=creerUE">Unités d'enseignement</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=creerMatiere">Matières</a>
        </li>
    </ul>
</div>
<br>
 <div class="container" id="Etudiants" style="display:block;">
    <div class="row content">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form action="<?php echo $actionForm; ?>" method="POST">
                <div class="form-group">
                  <label for="pwd">Intitulé :</label>
                  <input type="text" class="form-control" id="nomUE" placeholder="Nom de l'U.E.." name="intituleUE"
                         value='<?php echo isset($data['formulaire']['IntituleUE']) ? $data['formulaire']['IntituleUE'] : NULL; ?>'>
                </div>
                <div class="form-group"> 
                  <label for="sel1">Formation :</label>
                  <select class="form-control" id="sel1" name="formationUE">
                    <option>Choisir une formation </option>
                        <?php             
                            for($i=0;$i<=count($data['formations'])-1;$i++){
                        ?>
                            <Option <?php if($data['formations'][$i]['IdF'] == $idFormation){echo "selected";} ?> value ="<?php echo $data['formations'][$i]['IdF'] ?>">
                                <?php echo $data['formations'][$i]['IntituleF'] ?></option>     
                        <?php
                            }
                        ?>

                  </select>
                  <?php echo isset($data['formulaire']['IdUE']) ? "<input type='hidden' value='".$data['formulaire']['IdUE']."' name='idue'>" : NULL; ?>                 
                </div> 
                <button type="submit" class="btn btn-primary btn-block"><?php echo $action; ?></button>
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
</body>
