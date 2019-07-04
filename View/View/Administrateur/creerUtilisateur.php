<?php
if(isset($data['formulaire'])){
    $btnNav = "Retour";
    if(isset($data['formulaire']['valider'])){
        $formAction = "creerUtilisateur";
        $action = "Ajouter";
    }else{
        $formAction = "modifierUtilisateur";    
        $action = "Modifier";
    }
    if($data['active'] == 'etudiants'){
        $idEtudiant = $data['formulaire']['IdE'];
        $nomEtudiant = $data['formulaire']['NomE'];
        $prenomEtudiant = $data['formulaire']['PrenomE'];
        $mdp = $data['formulaire']['MdpE'];
        $formationEtudiant = $data['formulaire']['IdF'];
    }else if($data['active'] == 'enseignants'){
        $IdEnseignant =  $data['formulaire']['IdEns'];
        $nomEnseignant =  $data['formulaire']['NomEns'];
        $prenomEnseignant =  $data['formulaire']['PrenomEns'];
        $mdpEnseignant =  $data['formulaire']['MdpEns'];
        $idDomaineEnseignant = $data['formulaire']['IdDomaine'];
        $typeEnseignant = $data['formulaire']['TypeEns'];
    }else if($data['active'] == 'gestionnaires'){
        $idAdmin = $data['formulaire']['IdA'];
        $nomAdmin = $data['formulaire']['NomA'];
        $prenomAdmin = $data['formulaire']['PrenomA'];
        $mdpAdmin = $data['formulaire']['MdpA'];
        $statutAdmin = $data['formulaire']['StatutA'];    
    }
}else{
    $formAction = "creerUtilisateur";
    $action = "Ajouter";
}
?>
<div class="container">
    <br>
    <h2><center>Création des utilisateurs</center></h2>
</div>
<div class="container" <?php echo ($action == 'Modifier') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
    <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" onclick="afficherEtudiant();">Etudiants</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onclick="afficherEnseignant();">Enseignants</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onclick="afficherGestionnaire();">Administrateurs/Gestionnaires</a>
            </li>
    </ul>
</div>

<div class="container" id="etudiants" <?php if($data['active'] == 'nothing'){echo "style='display: block;'";}
    elseif(($data['active'] == 'etudiants')){echo "style='display: block;'";}
    else{echo "style='display: none;'";} ?>>
    <div class="row content">
        <div class="row" style="margin-top:25px;">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <h2><center>Insertion Etudiant</center></h2>
            </div>
            <div class="col-sm-4">
            <a href="index.php?action=gererUtilisateur&activeParams=etudiants"><button type="button" class="btn btn-primary" style="float:right;">
                <?php echo ($action == 'Modifier') ? $btnNav : "Gérer étudiants"; ?></button></a>
            </div>
        </div>
        <br>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form action="<?php echo "index.php?action=$formAction&type=etudiant"; ?>" method="POST">
                <div class="form-group">
                    <label for="email">Numéro étudiant:</label>
                    <input type="text" class="form-control"  placeholder="21575407..." name="IdE" required value ='<?php echo isset($idEtudiant) ? $idEtudiant : ""; ?>'>
                    <?php echo ($formAction !== "creerUtilisateur" && $action == 'Modifier') ? "<input type='hidden' value='$idEtudiant' name='oldIdE'>" : NULL; ?>
                </div>
                <?php 
                    if(isset($data['error']) && isset($idEtudiant)){
                        $msg = $data['error'];
                        echo "<div class='alert alert-danger'><strong>Attention ! </strong>$msg !</div>";
                    }
                ?>
                <div class="form-group">
                    <label for="pwd">Nom:</label>
                    <input type="text" class="form-control" id="pwd" placeholder="Nom" name="NomE" required value ='<?php echo isset($nomEtudiant) ? $nomEtudiant : ""; ?>'>
                </div>
                <div class="form-group">
                    <label for="pwd">Prénom:</label>
                    <input type="text" class="form-control" id="pwd" placeholder="Prénom" name="PrenomE" required value ='<?php echo isset($prenomEtudiant) ? $prenomEtudiant : ""; ?>'>
                </div>
                <div class="form-group">
                    <label for="sel1">Formation:</label>
                    <select class="form-control" id="sel1" name ='IdF'>
                        <option value="">Choisir une formation</option>
                        <?php             
                        for($i=0;$i<=count($data['formations'])-1;$i++){
                        ?>
                            <Option 
                                <?php  
                                if(isset($formationEtudiant)){
                                    if($data['formations'][$i]['IdF'] == $formationEtudiant){
                                        echo 'Selected';                                            
                                    }
                                }
                                ?>
                                    value="<?php echo $data['formations'][$i]['IdF']; ?>">
                                <?php echo $data['formations'][$i]['IntituleF']; ?>
                            </option>     
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pwd">Mot de passe:</label>
                    <input type="text" class="form-control" id="pwd" placeholder="Mot de passe" name="MdpE" value='<?php echo isset($mdp) ? $mdp : NULL; ?>'>
                </div>
                <button type="submit" class="btn btn-primary btn-block" value="ok" name="valider"><?php echo $action; ?></button>
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>

<div class="container" id="enseignants" <?php echo ($data['active'] !== 'enseignants') ? "style='display: none;'" : NULL; ?>>
    <div class="row content">
        <div class="row" style="margin-top:25px;">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <h2><center>Insertion Enseignant</center></h2>
            </div>
            <div class="col-sm-4">
                <a href="index.php?action=gererUtilisateur&activeParams=enseignants"><button type="button" class="btn btn-primary" style="float:right;">
                <?php echo ($action == 'Modifier') ? $btnNav : "Gérer enseignants"; ?></button></a> 
            </div>           
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <form action="<?php echo "index.php?action=$formAction&type=enseignant"; ?>" method="POST">
                    <div class="form-group">
                        <label for="email">Numéro enseignants</label>
                        <input type="text" class="form-control" id="email" placeholder="21575407..." name="IdEns" required value ='<?php echo isset($IdEnseignant) ? $IdEnseignant : NULL; ?>'
                               <?php echo ($formAction !== "creerUtilisateur") ? "disabled" : NULL; ?>>
                        <?php echo ($formAction !== "creerUtilisateur" && $action == 'Modifier' && isset($IdEnseignant)) ? "<input type='hidden' value='$IdEnseignant' name='IdEns'>" : NULL; ?>
                    </div>
                    <?php 
                        if(isset($data['error']) && isset($IdEnseignant)){
                            $msg = $data['error'];
                            echo "<div class='alert alert-danger'><strong>Attention ! </strong> $msg !</div>";
                        }
                    ?>
                    <div class="form-group">
                        <label for="email">Nom</label>
                        <input type="text" class="form-control" id="email" placeholder="Nom..." name="NomEns" required value ='<?php echo isset($nomEnseignant) ? $nomEnseignant : NULL; ?>'>
                    </div>
                    <div class="form-group">
                        <label for="email">Prénom</label>
                        <input type="text" class="form-control" id="email" placeholder="Prénom..." name="PrenomEns" required value ='<?php echo isset($prenomEnseignant) ? $prenomEnseignant : NULL; ?>'>
                    </div>
                    <div class="form-group">
                        <label for="email">Mot de passe</label>
                        <input type="text" class="form-control" id="email" placeholder="mot de passe..." name="MdpEns" required value ='<?php echo isset($mdpEnseignant) ? $mdpEnseignant : NULL; ?>'>
                    </div> 
                    <div class="form-group">
                        <label for="email">Statut</label>
                        <select class="form-control" id="sel1" name="TypeEns" required>
                            <?php
                            if(isset($typeEnseignant)){                               
                                if($typeEnseignant == "Enseignant"){ 
                                    $selectEns= "Selected";
                                    $selectInt ="";
                                    $Noselect ="";                            
                                }else if($typeEnseignant == "Intervenant exterieur"){
                                    $selectInt= "Selected"; 
                                    $selectEns ="";
                                    $Noselect ="";                                   
                                }else{
                                    $selectEns="";
                                    $selectInt="";
                                    $Noselect ="Selected";                                   
                                }                               
                            }else{
                                $selectEns="";
                                $selectInt="";
                                $Noselect ="Selected";   
                            }
                            ?> 
                                <option <?php echo $Noselect; ?>>Choisir Statut..</option>
                                <option value ="Enseignant" <?php echo $selectEns; ?>> Enseignant</option>
                                <option value ="Intervenant exterieur" <?php echo $selectInt; ?>>Intervenant exterieur</option>
                          </select>
                    </div>
                    <div class="form-group">
                        <label for="email" >Domaine</label>
                        <select class="form-control" id="sel1" name="IdDomaine" required>
                            <option>Choisir un domaine de compétence</option>
                            <?php 
                            for($i=0;$i<=count($data['domaines'])-1;$i++){
                            ?>
                                 <Option 
                                    <?php
                                    if(isset($idDomaineEnseignant)){
                                        if($data['domaines'][$i]['IdDomaine'] == $idDomaineEnseignant){
                                            echo "Selected";                                           
                                        }
                                    }
                                    ?>
                                    value="<?php echo $data['domaines'][$i]['IdDomaine']; ?>">
                                    <?php echo $data['domaines'][$i]['Intitule_domaine']; ?>
                                 </option>     
                            <?php
                            }
                            ?>
                          </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" value="ok" name="valider"><?php echo $action; ?></button>                  
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</div>

<div class="container" id="admin" <?php echo ($data['active'] !== 'gestionnaires') ? "style='display: none;'" : NULL; ?>>
    <div class="row content">
        <div class="row" style="margin-top:25px;">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <h2><center>Insertion Adminsitrateur/Gestionnaire</center></h2>
            </div>
            <div class="col-sm-4">
                <a href="index.php?action=gererUtilisateur&activeParams=gestionnaires"><button type="button" class="btn btn-primary" style="float:right;">
            <?php echo ($action == 'Modifier') ? $btnNav : "Gérer gestionnaires"; ?></button></a>
            </div>           
        </div>
        <br>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <br>
            <form action="<?php echo "index.php?action=$formAction&type=admin"; ?>" method="POST">
                <div class="form-group">
                    <label for="email">Numéro superUser</label>
                    <input type="text" class="form-control" id="email" placeholder="21575407..." name="IdA" value='<?php echo isset($idAdmin) ? $idAdmin : NULL; ?>'
                           <?php echo ($formAction !== "creerUtilisateur") ? "disabled" : NULL; ?>>
                    <?php echo ($formAction !== "creerUtilisateur" && $action == 'Modifier' && isset($idAdmin)) ? "<input type='hidden' value='$idAdmin' name='IdA'>" : NULL; ?>
                </div>
                <?php 
                    if(isset($data['error']) && isset($idAdmin)){
                        $msg = $data['error'];
                        echo "<div class='alert alert-danger'><strong>Attention !</strong> $msg !</div>";
                    }
                ?>
                <div class="form-group">
                    <label for="email">Nom</label>
                    <input type="text" class="form-control" id="email" placeholder="Nom..." name="NomA" value='<?php echo isset($nomAdmin) ? $nomAdmin : NULL; ?>'>
                </div>
                <div class="form-group">
                    <label for="email">Prénom</label>
                    <input type="text" class="form-control" id="email" placeholder="Prénom..." name="PrenomA" value='<?php echo isset($prenomAdmin) ? $prenomAdmin : NULL; ?>'>
                </div>
                <div class="form-group">
                    <label for="email">Mot de passe</label>
                    <input type="text" class="form-control" id="email" placeholder="mot de passe..." name="MdpA" value='<?php echo isset($mdpAdmin) ? $mdpAdmin : NULL; ?>'>
                </div> 
                <div class="form-group">
                    <label for="email">Statut</label>
                    <select class="form-control" id="sel1" name ='StatutA' required >
                        <?php 
                        if(isset($statutAdmin)){
                            if($statutAdmin == "Administrateur"){
                                $selectAd= "Selected";
                                $selectGe ="";
                                $Noselect ="";                     
                            }else if ($statutAdmin == "Gestionnaire"){
                                $selectGe= "Selected";
                                $selectAd ="";
                                $Noselect ="";                           
                            }else{
                                $selectAd="";
                                $selectGe="";
                                $Noselect ="Selected";                           
                            }                        
                        }else{
                            $selectAd="";
                            $selectGe="";
                            $Noselect ="";                            
                        }

                        ?> 
                        <option<?php echo $Noselect; ?> >Choisir Statut..</option>
                        <option <?php echo $selectGe; ?> value ='Gestionnaire'>Gestionnaire</option>
                        <option <?php echo $selectAd; ?> value ='Administrateur'>Administrateur</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block" value="ok" name="valider"><?php echo $action; ?></button>          
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>
