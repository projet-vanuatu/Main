<?php
if($data['active'] == ""){
    $active = "NULL";
}else{
    $active = $data['active'];
}
?>
<div class="container">
    <br>
    <h2><center>Gestion du matériel</center></h2>
    <br>
</div>
<div class="container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link acstive " onclick="afficherMaterielEquipe();">Matériel équipé dans une salle</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  " onclick="afficherMaterielNonEquipe();">Matériel non équipé dans une salle </a>
        </li> 
    </ul>
</div>
<br>
<div class="container" id="materielEquipe" <?php if($active == "NULL"){echo "style='display: block;'";}
        elseif(($active == 'AF')){echo "style='display: block;'";}
        else{echo "style='display: none;'";} ?>>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput" placeholder="Rechercher.." title="">
        </div>
        <div class="col-sm-4">
            <a href = "index.php?action=creerMateriel"><button type="button" class="btn btn-primary" style="float:right;">Créer matériel</button></a>
        </div>
    </div>
    <br>
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-hover">
            <thead class="header">
                <tr>
                    <th>N° série</th>
                    <th>Type</th>
                    <th>Etat de fonctionnement</th>
                    <th>Salle</th>
                    <th>Site</th>
                </tr>
            </thead>
            <tbody id="myTable">    
            <?php             
                for($i=0;$i<=count($data['materielAff'])-1;$i++){
            ?>
                <tr>
                    <td><?php echo $data['materielAff'][$i]['numSerie']; ?></td>
                    <td><?php echo $data['materielAff'][$i]['TypeMat']; ?></td>
                    <td><?php echo $data['materielAff'][$i]['Etat_fonctionnement']; ?></td>
                    <td><?php echo $data['materielAff'][$i]['NomS']; ?></td>
                    <td><?php echo $data['materielAff'][$i]['NomSITE']; ?></td>
                    <td>
                        <p><a href =  "<?php echo "index.php?action=modifierMateriel&id=".$data['materielAff'][$i]['IdMat']."&activeParams=AF"; ?>">
                                <button type="button" class="btn btn-warning">Modifier</button></a>
                        <a href ="<?php echo "index.php?action=supprimerMateriel&id=".$data['materielAff'][$i]['IdMat']."&activeParams=AF"; ?>">
                            <button type="button" class="btn btn-danger" onclick="return confirm('Etes-vous sûr de vouloir supprimer ?');">Supprimer</button></a></p>
                    </td>
                </tr>
            <?php
                }
            ?>  
            </tbody>
        </table>
    </div>
</div>
<div class="container" id="materielNonEquipe" <?php echo ($active !== 'NF') ? "style='display: none;'" : NULL; ?>>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput2" placeholder="Rechercher.." title="">
        </div>
        <div class="col-sm-4">
            <a href = "index.php?action=creerMateriel"><button type="button" class="btn btn-primary" style="float:right;">Créer matériel</button></a>
        </div>
    </div>
    <br>   
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-hover">
            <thead class="header">
                <tr>
                    <th>N° série</th>
                    <th>Type</th>
                    <th>Etat de fonctionnement</th>  
                </tr>
            </thead>
            <tbody id="myTable2">
            <?php             
            for($i=0;$i<=count($data['materielNonAff'])-1;$i++){
            ?>
                <tr>
                    <td><?php echo $data['materielNonAff'][$i]['numSerie']; ?></td>
                    <td><?php echo $data['materielNonAff'][$i]['TypeMat']; ?></td>
                    <td><?php echo $data['materielNonAff'][$i]['Etat_fonctionnement']; ?></td>
                    <td>
                        <p><a href =  "<?php echo "index.php?action=modifierMateriel&id=".$data['materielNonAff'][$i]['IdMat']."&activeParams=NF"; ?>">
                                <button type="button" class="btn btn-warning">Modifier</button></a>
                        <a href ="<?php echo "index.php?action=supprimerMateriel&id=".$data['materielNonAff'][$i]['IdMat']."&activeParams=NF"; ?>">
                            <button type="button" class="btn btn-danger" onclick="return confirm('Etes-vous sûr de vouloir supprimer ?');">Supprimer</button></a></p>
                    </td>
                </tr>
            <?php
                }
            ?>    
            </tbody>
        </table>
    </div>
</div>