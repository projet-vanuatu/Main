<div class="container">
    <br>
    <div class="row">
        <h2><center>Gestion des formations</center></h2>
    </div>
    <div class="container" style="padding: 15px;">
        <div class="row">
            <div class="col-sm-8">
                <input type="text" class="form-control" id="myInput" placeholder="Rechercher.." title="">
            </div>
            <div class="col-sm-4">
                <a href="index.php?action=creerFormation"><button type="button" class="btn btn-primary" style="float:right;">Créer formation</button></a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-hover">
                        <thead>
                            <th>Formation</th><th>Capacite</th><th>Groupe TD (numéro/capacité)</th><th>Edition</th>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            for($i=0;$i<=count($data)-1;$i++){
                            ?>
                            <tr>
                                <td><?php echo $data[$i]['IntituleF']; ?></td>
                                <td><?php echo $data[$i]['Capacite_max_GCM']; ?></td>
                                <td>
                                    <ul>
                                        <?php
                                        for($j=0;$j<=count($data[$i]['TD'])-1;$j++){
                                            echo "<li>".$data[$i]['TD'][$j]['NumGroupTD']." - ".$data[$i]['TD'][$j]['Capacite_max_GTD']."</li>";
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <?php
                                $id = $data[$i]['IdF'];
                                ?>
                                <td>
                                    <p>
                                        <a href="<?php echo "index.php?action=modifierFormation&idF=$id"; ?>">
                                            <button type="button" class="btn btn-warning">Modifier</button></a>
                                        <a href="<?php echo "index.php?action=supprimerFormation&idF=$id"; ?>">
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