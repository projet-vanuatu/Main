<?php
if(isset($data['formulaire'])){
    $btnNav = 'Retour';
    $action = 'Modifier';
    $actionForm = 'index.php?action=modifierMateriel&activeParams='.$data['active'];
    $TypeMat = $data['formulaire']['TypeMat'];
    $Etat_fonctionnement = $data['formulaire']['Etat_fonctionnement'];
    if($data['active'] != 'NF'){
        $idS = $data['formulaire']['IdS'];        
    }else{
        $idS = "";
    }   
}else{
    $btnNav = 'Gerer matériel';
    $action = 'Ajouter';
    $actionForm = 'index.php?action=creerMateriel';
    $idS = "";
}
?>
<br><br>
<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2><center>Ajouter matériel</center></h2>
        </div>           
        <div class="col-sm-4">
            <a href="index.php?action=gererMateriel"><button type="button" class="btn btn-primary" style="margin-top:25px; float:right;"><?php echo $btnNav; ?></button></a>
        </div>
    </div>
    <hr>
</div>
<div class="container">
    <div class="row content">           
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form  action="<?php echo $actionForm; ?>" method="POST">
                <div class="form-group">
                    <label for="sel1">Type de matériel:</label>
                    <input type="text" name="numSerie" class="form-control" placeholder="XVE4038AE.." 
                           value="<?php echo isset($data['formulaire']['numSerie']) ? $data['formulaire']['numSerie'] : ""; ?>" required>
                </div>
                <div class="form-group">
                    <label for="sel1">Type de matériel:</label>
                    <select class="form-control" id="sel1" name ='Type' required>
                        <?php 
                        if($TypeMat == "Micro"){
                            $selectMicro= "Selected"; $selectVP ="";$selectNo =""; $selectOrdi ="";                    
                        }else if($TypeMat == "Video-projecteur"){
                                $selectMicro= ""; $selectVP ="Selected";$selectNo =""; $selectOrdi ="";                       
                        }else if($TypeMat == "Ordinateur"){
                            $selectMicro= ""; $selectVP ="";$selectNo =""; $selectOrdi ="Selected";               
                        }else{
                            $selectMicro= ""; $selectVP ="";$selectNo ="Selected"; $selectOrdi ="";                
                        }
                        ?> 
                        <option  <?php echo $selectNo ?>  >Choisir une catégorie </option>
                        <option <?php echo $selectMicro ?> value ='Micro'>Micro </option>
                        <option <?php echo $selectVP ?>  value ='Video-projecteur'>Video-projecteur </option>
                        <option <?php echo $selectOrdi ?> value ='Ordinateur'>Ordinateur </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sel1">Etat de fonctionnement:</label>
                    <select class="form-control" id="sel1" name ='Etat' required>
                        <?php 
                            if($Etat_fonctionnement == "En marche"){
                                $selectM= "Selected"; $selectP ="";$Noselect ="";                        
                            }else if($Etat_fonctionnement == "En panne"){
                                $selectP= "Selected"; $selectM ="";$Noselect ="";                       
                            }else{
                                $selectM="" ;$selectP=""; $Noselect ="Selected";                        
                            }
                        ?> 
                        <option <?php echo $Noselect ?> >Choisir l'état de fonctionnement </option>
                        <option <?php echo $selectM ?> value ='En marche'>En marche </option>
                        <option <?php echo $selectP ?> value ='En panne'>En panne </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sel1"> Equipé dans la salle (optionnel):</label>
                    <select class="form-control" id="sel1" name ='Salle'>
                        <option value='NULL'>Choisir la salle </option>
                        <?php             
                        for($i=0;$i<=count($data['salles'])-1;$i++){
                        ?>
                            <Option  <?php  if($data['salles'][$i]['IdS'] == $idS){ echo "Selected"; } ?> value ="<?php echo $data['salles'][$i]['IdS']; ?>">
                                <?php echo "Salle : ".$data['salles'][$i]['NomS']." Site : ".$data['salles'][$i]['NomSITE']?></option>     
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <?php echo isset($data['formulaire']['IdMat']) ? "<input type='hidden' value='".$data['formulaire']['IdMat']."' name='IdMat'>" : NULL; ?>
                <button type="submit" class="btn btn-primary btn-block" value="OK" name="action"><?php echo $action; ?></button>
            </form>
        </div>
    </div>
</div>