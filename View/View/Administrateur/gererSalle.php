<div class="container">
    <br>
    <h2><center>Gestion des sites & salles</center></h2>
</div>
<div class="container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link acstive" href="index.php?action=gererSalle">Salles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=gererSite">Site</a>
        </li>
    </ul>
</div>
<br>
<div class="container">
    <div class="container" style="padding: 15px;">
        <div class="row">
            <div class="col-sm-8">
                <input type="text" class="form-control" id="myInput" placeholder="Rechercher..">
            </div>
            <div class="col-sm-4">
                <a href="index.php?action=creerSalle"><button type="button" class="btn btn-primary" style="float:right;">Creer salle</button></a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-hover bg-global-gris">
                        <thead>
                            <th>Nom salle</th><th>Capacité</th><th>Type</th><th>Site</th>
                        </thead>
                        <tbody id="myTable">
                            <?php
                                for($i=0;$i<=count($data['salles'])-1;$i++){
                            ?>
                                <tr>
                                    <td><?php echo $data['salles'][$i]['NomS']; ?></td>
                                    <td><?php echo $data['salles'][$i]['CapaciteS']; ?></td>
                                    <td><?php echo $data['salles'][$i]['TypeS']; ?></td>  
                                    <td><?php echo $data['salles'][$i]['NomSITE']; ?></td>  
                                    <?php
                                    $IdS = $data['salles'][$i]['IdS'];
                                    $IdSITE= $data['salles'][$i]['NomSITE'];
                                    ?>
                                    <td>
                                        <p>
                                            <a href="<?php echo "index.php?action=modifierSalle&id=".$data['salles'][$i]['IdS']; ?>">
                                                <button type="button" class="btn btn-warning">Modifier</button></a>
                                            <a href="<?php echo "index.php?action=supprimerSalle&id=".$data['salles'][$i]['IdS']; ?>">
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
        </div>
    </div>
</div>