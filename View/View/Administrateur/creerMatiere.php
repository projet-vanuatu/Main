<?php
if(isset($data['formulaire'])){
    $btnNav = 'Retour';
    $action = 'Modifier';
    $actionForm = 'index.php?action=modifierMatiere';
    $typeMatiere = $data['formulaire']['TypeM'];
    $idUE = $data['formulaire']['IdUE'];
    $idDomaine = $data['formulaire']['IdDomaine'];
    $couleurMatiere = $data['formulaire']['CouleurM'];
    $idMatiere = $data['formulaire']['NumM'];
}else{
    $btnNav = 'Gerer Matiere';
    $action = 'Ajouter';
    $actionForm = 'index.php?action=creerMatiere';
    $idFormation = "";
    $typeMatiere = "";
    $idUE = "";
    $idDomaine = "";
}
?>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2><center>Ajouter matiere</center></h2>
        </div>           
        <div class="col-sm-4">
            <a href="index.php?action=gererMatiereUE&activeParams=matiere">
                <button type="button" class="btn btn-primary" style="margin-top:25px; float:right;"><?php echo $btnNav; ?></button></a>
        </div>
    </div>
</div>
<div class="container" <?php echo ($action == 'Modifier') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link acstive" href="index.php?action=creerUE">Unités d'enseignement</a>
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
                <?php echo isset($idMatiere) ? "<input type='hidden' name='idMatiere' value='$idMatiere'>" : ""; ?>
                <div class="row">
                    <div class="form-group">
                      <label for="pwd">Intitulé :</label>
                      <input type="text" class="form-control" id="nomMat" placeholder="Nom de la matière" name="intituleM"
                             value='<?php echo isset($data['formulaire']['IntituleM']) ? $data['formulaire']['IntituleM'] : NULL; ?>'
                             <?php echo ($typeMatiere == 'TD') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
                    </div>
                    <div class="form-group"> 
                        <label for="sel1">Type :</label>
                        <select class="form-control" id="typeMatiere" name="choixTypeMatiere" onchange="ajouterTD(value);"
                                <?php echo ($action == 'Modifier') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
                            <option value="" selected>Choisir le type de matière</option>
                            <option value="CM" <?php if ($typeMatiere == 'CM'){ echo "selected";}?>>CM</option>
                            <option value="TD" <?php if ($typeMatiere == 'TD'){ echo "selected";}?> >TD</option>
                            <option value="Autres" <?php if ($typeMatiere == 'Autres'){ echo "selected";}?> >Autres</option>
                        </select> 
                    </div>
                    <div class="form-group"> 
                      <label for="sel1">Unité d'enseignement :</label>
                      <select class="form-control" id="nomUE" name="choixUE" <?php echo ($typeMatiere == 'TD') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
                        <option selected >Choisir une U.E.</option>
                            <?php             
                                for($i=0;$i<=count($data['ues'])-1;$i++){
                            ?>
                                <Option <?php if ($data['ues'][$i]['IdUE'] == $idUE){ echo "selected";}?> 
                                    value ="<?php echo $data['ues'][$i]['IdUE'] ?>"><?php echo $data['ues'][$i]['IntituleF'].' - '.$data['ues'][$i]['IntituleUE']; ?></option>     
                            <?php
                                }
                            ?>
                      </select>
                    </div>
                    <div class="form-group"> 
                        <label for="sel1">Domaine :</label>
                        <select class="form-control" id="nomDom" name="choixDomaine" <?php echo ($typeMatiere == 'TD') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
                          <option selected >Choisir un domaine </option>
                              <?php             
                                  for($i=0;$i<=count($data['domaines'])-1;$i++){
                              ?>
                                  <Option <?php if ($data['domaines'][$i]['IdDomaine'] == $idDomaine){ echo "selected";}?> 
                                      value ="<?php echo $data['domaines'][$i]['IdDomaine'] ?>"><?php echo $data['domaines'][$i]['Intitule_domaine'] ?></option>     
                              <?php
                                  }
                              ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Nombre d'heures <?php echo $typeMatiere.' :'; ?></label>
                        <input type="number" maxlength="3" class="form-control" id="nbHeuresMat" placeholder="Nombre entre 1 et 200.." name="nbHeuresM"
                               value='<?php echo isset($data['formulaire']['NbHeuresFixees']) ? $data['formulaire']['NbHeuresFixees'] : NULL; ?>'>
                    </div>
                    <div class="form-group">
                        <label for="color">Choirir une couleur :</label>
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="color" id="color" name="color" class="form-control" 
                                       value="<?php echo isset($data['formulaire']['CouleurM']) ? $data['formulaire']['CouleurM'] : "#f6b73c"; ?>"
                                       <?php echo ($typeMatiere == 'TD') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <?php
                if($action == 'Ajouter'){
                ?>      
                    <div class="row">
                        <div class="checkbox">
                          <label><input type="checkbox" value="" id="addTD" onchange='ajouterNbHeureTD();' disabled>Creer un TD</label>
                        </div>
                        <div class="form-group">
                            <label for="pwd">Nombre d'heures TD :</label>
                            <input type="number" maxlength="3" class="form-control" id="nbHeuresMatTD" 
                                   placeholder="Nombre entre 1 et 200.." name="nbHeuresMTD" value='' disabled>
                        </div>
                    </div>
                <?php
                }
                ?>
                <button type="submit" class="btn btn-primary btn-block"><?php echo $action; ?></button>
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>

<script>
    function ajouterTD(type){
        if(type == "CM"){
            document.getElementById('addTD').disabled = false;
            document.getElementById('nbHeuresMatTD').disabled = true;
        }else{
            document.getElementById('addTD').disabled = true;
            document.getElementById('nbHeuresMatTD').disabled = true;
        }
    }    
    function ajouterNbHeureTD(){
        if(document.getElementById('addTD').checked){
            document.getElementById('nbHeuresMatTD').disabled = false;            
        }else{
            document.getElementById('nbHeuresMatTD').disabled = true; 
        }
    }
</script>
