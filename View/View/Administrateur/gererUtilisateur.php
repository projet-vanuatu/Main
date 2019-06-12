<?php
if(isset($data['active'])){
    $pageActive = $data['active'];
}else{
    $pageActive = 'nothing';
}
?>
<div class="container">
    <br>
    <h2><center>Gestion des utilisateurs</center></h2>
</div>
<div class="container">
    <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="btnEtu" onclick="afficherEtudiant();">Etudiants</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="btnEns" onclick="afficherEnseignant()">Enseignants</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="btnGest" onclick="afficherGestionnaire();">Administrateurs/Gestionnaires</a>
            </li>
    </ul>
</div>
<br>
<!-- Sub nav -->
<div class="container" id="etudiants" <?php if($pageActive == 'nothing'){echo "style='display: block;'";}elseif(($pageActive == 'etudiants')){echo "style='display: block;'";}
            else{echo "style='display: none;'";} ?>>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput" placeholder="Rechercher..">
        </div>
        <div class="col-sm-4">
            <a href = "index?action=creerUtilisateur&type=etudiants&activeParams=etudiants"><button type="button" class="btn btn-primary" style="float:right;">Créer étudiant</button></a>
        </div>
    </div>
    <br>            
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-hover">
            <thead class="header">
                <tr>
                    <th>Identifiant</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Formation</th>
                    <th>Groupe TD</th>
                    <th>Edition</th>
                </tr>
            </thead>
            <tbody id="myTable">                       
            <?php             
            for($i=0;$i<=count($data['etudiants'])-1;$i++){
            ?>
                <tr>
                    <td><?php echo $data['etudiants'][$i]['IdE']; ?></td>
                    <td><?php echo $data['etudiants'][$i]['NomE']; ?></td>
                    <td><?php echo $data['etudiants'][$i]['PrenomE']; ?></td>
                    <td><?php echo (isset($data['etudiants'][$i]['IntituleF'])) ? $data['etudiants'][$i]['IntituleF'] : 'Non affecté'; ?></td>
                    <td><?php echo (isset($data['etudiants'][$i]['NumGroupTD'])) ? $data['etudiants'][$i]['NumGroupTD'] : 'Non affecté'; ?></td>
                    <td>
                        <p>
                            <a href =  "<?php echo "index.php?action=modifierUtilisateur&type=etudiant&id=".$data['etudiants'][$i]['IdE']; ?>">
                                <button type="button" class="btn btn-warning">Modifier</button></a>
                            <a href ="<?php echo "index.php?action=supprimerUtilisateur&type=etudiant&idEtudiant=".$data['etudiants'][$i]['IdE']."&idMdp=".$data['etudiants'][$i]['IdMdp']; ?>">
                                <button type="button" class="btn btn-danger" onclick="return confirm('Etes-vous sûr de vouloir supprimer ?');">Supprimer</button></a>
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
<div class="container" id="enseignants" <?php echo ($pageActive !== 'enseignants') ? "style='display: none;'" : NULL; ?>>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput2" placeholder="Rechercher..">
        </div>
        <div class="col-sm-4">
            <a href="index?action=creerUtilisateur&type=enseignants&activeParams=enseignants"><button  type="button" class="btn btn-primary" style="float:right;">Créer enseignant</button></a>
        </div>
    </div> 
    <br>                
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-hover">
            <thead class="header">
                <tr>
                    <th>Identifiant</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Statut</th>
                    <!--<th>Domaine</th>-->
                    <th>Edition</th>
                </tr>
            </thead>
            <tbody id="myTable2">
            <?php             
            for($i=0;$i<=count($data['enseignants'])-1;$i++){
            ?>  
                <tr>
                    <td><?php echo $data['enseignants'][$i]['IdEns']; ?></td>
                    <td><?php echo $data['enseignants'][$i]['NomEns']; ?></td>
                    <td><?php echo $data['enseignants'][$i]['PrenomEns']; ?></td>
                    <td><?php echo $data['enseignants'][$i]['TypeEns']; ?></td>
                    <!--<td><?php //echo  $data['enseignants'][$i]['Intitule_domaine'] ?></td>-->
                    <td>
                        <p>
                            <a href = "<?php echo "index.php?action=modifierUtilisateur&type=enseignant&id=".$data['enseignants'][$i]['IdEns']; ?>">
                                <button type="button" class="btn btn-warning" value ='<?php echo  $data['enseignants'][$i]['IdEns'] ?>'>Modifier</button></a>
                            <a href ="<?php echo "index.php?action=supprimerUtilisateur&type=enseignant&idEnseignant=".$data['enseignants'][$i]['IdEns']."&idMdp=".$data['enseignants'][$i]['IdMdp']; ?>">
                                <button type="button" class="btn btn-danger" value ='<?php echo  $data['enseignants'][$i]['IdEns'] ?>' onclick="return confirm('Etes-vous sûr de vouloir supprimer ?');">Supprimer</button></a>
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

<div class="container" id="admin" <?php echo ($pageActive !== 'gestionnaires') ? "style='display: none;'" : NULL; ?>>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput3" placeholder="Rechercher..">
        </div>
        <div class="col-sm-4">
            <a href="index?action=creerUtilisateur&type=gestionnaires&activeParams=gestionnaires"><button type="button" class="btn btn-primary" style="float:right;">Créer super utilisateur</button></a>
        </div>
    </div>
    <br>        
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-hover">
            <thead class="header">
                <tr>
                    <th>Identifiant</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Statut</th>
                    <th>Edition</th>
                </tr>
            </thead>
            <tbody id="myTable3">
            <?php             
                for($i=0;$i<=count($data['admins'])-1;$i++){
            ?>
                <tr>
                    <td><?php echo $data['admins'][$i]['IdA']; ?></td>
                    <td><?php echo $data['admins'][$i]['NomA']; ?></td>
                    <td><?php echo $data['admins'][$i]['PrenomA']; ?></td>
                    <td><?php echo $data['admins'][$i]['StatutA']; ?></td>
                    <td>
                        <p>
                            <a href = <?php echo "index.php?action=modifierUtilisateur&type=admin&id=".$data['admins'][$i]['IdA']; ?>>
                                <button type="button" class="btn btn-warning" value ='<?php echo  $data['admins'][$i]['IdA'] ?>'>Modifier</button></a>
                            <a href ="<?php echo "index.php?action=supprimerUtilisateur&type=admin&idAdmin=".$data['admins'][$i]['IdA'].
                                    "&idMdp=".$data['admins'][$i]['IdMdp']; ?>">
                                <button type="button" class="btn btn-danger" value ='<?php echo  $data['admins'][$i]['IdA'] ?>' onclick="return confirm('Etes-vous sûr de vouloir supprimer ?');">Supprimer</button></a>
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