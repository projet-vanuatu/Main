<?php
    $nomForm = $data[0][0]['IntituleF'];
    $idForm = $data[0][0]['IdF'];
    $capaciteForm = $data[0][0]['Capacite_max_GCM'];
    $nbGroupeTD = count($data[0][0]['TD']);
    $modification = true;
    $action = "index.php?action=modifierFormation";
?>
<div class="container">
    <br><br>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2>Modifier une formation</h2>
        </div>
        <div class="col-sm-4">
            <a href="index.php?action=gererFormation"><button type="button" class="btn btn-primary" style="margin-top:25px; float:right;">Retour</button></a>
        </div>
    </div>
    <hr>
    <br>
    <div class="row" style="background-color: white; padding: 20px;">     
        <form action="<?php echo $action; ?>" method="POST" name="updateForm">
            <div class="col-sm-2"></div>
            <div class="col-sm-4">                    
                <div class="form-group">
                  <label for="nomForm">Nom de la formation:</label>
                  <input type="text" class="form-control" id="nomForm" placeholder="L1 AES..." name="nomForm" value="<?php echo $nomForm; ?>" required>
                </div>
                <div class="form-group">
                  <label for="nbCmForm">Nombre de groupe de CM:</label>
                  <input type="number" class="form-control" id="nbCmForm" placeholder="1" name="nbCmForm" disabled>
                </div>
                <div class="form-group">
                  <label for="capaciteForm">Capacité:</label>
                  <input type="text" class="form-control" id="capaciteForm" placeholder="200..." name="capaciteForm"  value="<?php echo $capaciteForm; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nbTdForm">Nombre de groupe de TD:</label>
                    <select name="totalTdForm" class="form-control" onchange="afficherGroupeTD();" id="nbTD" disabled>
                        <?php
                            for($i=0;$i<=6;$i++){
                                if($i == 0){
                                    echo "<option value='0'>Choisir le nombre de groupes de TD</option>";
                                }else if($nbGroupeTD == $i){
                                    echo "<option value='$i' selected>$i</option>";
                                }else{
                                    echo "<option value='$i'>$i</option>";
                                }                          
                            }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="idForm" value="<?php echo $idForm; ?>">
                <input type="submit" value="Mettre à jour" class="btn btn-primary btn-block">
            </div>
            <div class="col-sm-3" id="displayTD">
            <?php
                for($i=1;$i<=6;$i++){
                    if($i <= $nbGroupeTD && $modification){
                        echo "<div class='form-group' style='display: block;' id='con$i'>";
                    }else{
                        echo "<div class='form-group' style='display: none;' id='con$i'>";
                    }                   
            ?>
                            <div class="row">
                                <label for=<?php echo "nbTdForm".$i; ?>><?php echo "capacité groupe de TD ".$i; ?></label>
                            <?php
                                if($i <= $nbGroupeTD){
                                    $capaciteTD = $data[0][0]['TD'][$i-1]['Capacite_max_GTD'];
                                    $idGroupTD = $data[0][0]['TD'][$i-1]['IdGTD'];
                                    echo "<input type='text' name='nbTdForm$i' class='form-control' onchange='afficherGroupeTD();' "
                                            . "id='nbTD$i' value='$capaciteTD' required>";
                                }else{
                                    echo "<input type='text' name='nbTdForm$i' class='form-control' onchange='afficherGroupeTD();' "
                                            . "id='nbTD$i' disabled>";                                
                                }
                            ?>
                            </div>
                    <?php
                        echo '</div>';                             
                    ?>                            

            <?php
                }
            ?>
            </div>
        </form>
        <div class="col-sm-3">
        <?php
            for($i=1;$i<=6;$i++){               
                if($i <= $nbGroupeTD){
                    $idGroupTD = $data[0][0]['TD'][$i-1]['IdGTD'];
                    if($i == 1){
                        echo "<div class='row' style='display: block; height:49px; margin-top:25px;' id='conn$i'>"; 
                    }else{
                        echo "<div class='row' style='display: block; height:49px; margin-top:25px;' id='conn$i'>";
                    }                                        
                }else{
                    echo "<div class='row' style='display: none; height:49px;' id='conn$i'>";
                }
                echo "<a href='index.php?action=supprimerTD&idT=$idGroupTD&idF=$idForm'><button class='btn btn-danger'>Supprimer</button></a>"; 
                echo '</div>';
            }
        ?>
        </div>        
    </div>
    <hr>
    <br>
    <div class="row">
        <form action="index.php?action=ajouterTD" method="POST">
            <div class="col-sm-6"></div>
            <div class="col-sm-3">
                <div class="form-group">
                  <label for="nomForm">Ajouter un nouveau groupe de TD:</label>
                  <input type="number" name="nbTDajout" class="form-control" placeholder="Capacité du nouveau groupe de TD" required>
                </div>
                <input type="hidden" name="idFormTD" value="<?php echo $idForm; ?>">
            </div>
            <div class="col-sm-3">
                <br>
                <input type="submit" value="Ajouter" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>



