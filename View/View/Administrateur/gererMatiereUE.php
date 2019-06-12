<?php
if(isset($data['active'])){
    $active = $data['active'];
}else{
    $active = "nothing";
}
?>
<div class="container">
    <br>
    <h2><center>Gestion des UE et matières</center></h2>
</div>
<div class="container">
    <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link acstive" onclick="afficherUE();">Unités d'enseignements</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onclick="afficherMatieres()">Matières</a>
            </li>               
    </ul>
</div>
<br>
<!-- UE -->
<div class="container" id="UE" <?php if($active == 'nothing'){echo "style='display: block;'";}elseif(($active == 'ue')){echo "style='display: block;'";}else{echo "style='display: none;'";} ?>>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput" placeholder="Rechercher..">
        </div>
        <div class="col-sm-4">
            <a href="index.php?action=creerUE"<button class="btn btn-primary" style="float:right;">Créer une UE</button></a>
        </div>
    </div>
    <br>            
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-hover">
            <thead class="header">
                <tr>
                    <th>Nom UE</th>
                    <th>Formation</th>
                </tr>
            </thead>
            <tbody id="myTable">
                <?php
                for($i=0;$i<=count($data['ues'])-1;$i++){
                ?>
                <tr>
                    <td><?php echo $data['ues'][$i]['IntituleUE']; ?></td>
                    <td><?php echo utf8_encode($data['ues'][$i]['IntituleF']); ?></td>
                    <td>
                        <p>
                            <a href="<?php echo "index.php?action=modifierUE&id=".$data['ues'][$i]['IdUE']; ?>">
                                <button class="btn btn-warning">Modifier</button></a>
                            <a href="<?php echo "index.php?action=supprimerUE&id=".$data['ues'][$i]['IdUE']; ?>">
                                <button class="btn btn-danger" onclick="return confirm('Etes-vous sûr de vouloir supprimer ?');">Supprimer</button></a>
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
<!-- Matières -->
<div class="container" id="Matieres" <?php echo ($active !== 'matiere') ? "style='display: none;'" : NULL; ?>>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput2" placeholder="Rechercher.." title="">
        </div>
        <div class="col-sm-4">
            <a href="index.php?action=creerMatiere"<button class="btn btn-primary" style="float:right;">Créer une matière</button></a>
        </div>
    </div>
    <br>        
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-hover">
            <thead class="header">
                <tr>
                    <th>Intitulé</th>
                    <th>Type</th>
                    <th>Heures fixées</th>
                    <th>UE</th>                  
                </tr>
            </thead>
            <tbody id="myTable2">
                <?php
                for($i=0;$i<=count($data['matieres'])-1;$i++){
                ?>
                <tr>
                    <td><?php echo ($data['matieres'][$i]['IntituleM']); ?></td>
                    <td><?php echo ($data['matieres'][$i]['TypeM']); ?></td>
                    <td><?php echo ($data['matieres'][$i]['NbHeuresFixees']); ?></td>
                    <td><?php echo ($data['matieres'][$i]['IntituleUE']); ?></td> 
                    <td>
                        <p>
                            <a href="<?php echo "index.php?action=modifierMatiere&id=".$data['matieres'][$i]['NumM']; ?>">
                                <button class="btn btn-warning">Modifier</button></a>
                            <a href="<?php echo "index.php?action=supprimerMatiere&id=".$data['matieres'][$i]['NumM']; ?>">
                                <button class="btn btn-danger" onclick="return confirm('Etes-vous sûr de vouloir supprimer ?');">Supprimer</button></a>
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