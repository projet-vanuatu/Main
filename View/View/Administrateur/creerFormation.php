<!--page-->
<div class="container">
    <br><br>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2>Ajouter une formation</h2>
        </div>           
        <div class="col-sm-4">
            <a href="index.php?action=gererFormation"><button type="button" class="btn btn-primary" style="margin-top:25px; float:right;">Gérer les formations</button></a>
        </div>
    </div>
    <hr>
    <div class="row" style="padding: 20px;">
        <form action="index.php?action=creerFormation" method="POST">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="nomForm">Nom de la formation:</label>
                      <input type="text" class="form-control" id="nomForm" placeholder="L1 AES..." name="nomForm" required>
                    </div>
                    <div class="form-group">
                      <label for="nbCmForm">Nombre de groupe de CM:</label>
                      <input type="number" class="form-control" id="nbCmForm" placeholder="1" name="nbCmForm" disabled>
                    </div>
                    <div class="form-group">
                      <label for="capaciteForm">Capacité:</label>
                      <input type="number" class="form-control" id="capaciteForm" placeholder="200..." name="capaciteForm" required>
                    </div>
                    <div class="form-group">
                        <label for="nbTdForm">Nombre de groupe de TD:</label>
                        <select name="nbTdForm" class="form-control" onchange="afficherGroupeTD();" id="nbTD">
                        <?php
                            for($i=0;$i<=6;$i++){
                                if($i == 0){
                                    echo "<option value='0'>Choisir le nombre de groupes de TD</option>";
                                }else{
                                    echo "<option value='$i'>$i</option>";
                                }                          
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4" id="displayTD">
                    <?php
                    for($i=1;$i<=6;$i++){
                    ?>
                        <div class="form-group" style="display: none;" id="<?php echo 'con'.$i; ?>">
                            <label for=<?php echo "nbTdForm".$i; ?>><?php echo "capacité groupe de TD ".$i; ?></label>
                            <select name="<?php echo "nbTdForm".$i; ?>" class="form-control" onchange="afficherGroupeTD();" id="<?php echo "nbTD".$i; ?>">
                            <?php
                                for($j=1;$j<=50;$j++){
                                    echo "<option value='$j'>$j</option>";
                                }
                            ?>
                            </select>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
               <div class="col-sm-4"><input type="submit" value="Valider" class="btn btn-primary btn-block"></div>
               <div class="col-sm-4"></div>
            </div>
        </form>
    </div>
</div>