<?php
if(!empty($_GET['IdResHC'])){
    $IdResHC = $_GET['IdResHC'];
    $IdENSHC = $_GET['IdENSHC'];
    $IdAHC = '21100905';
    $IdMatHC = $_GET['IdMatHC'];
    $DateResaHC = $_GET['DateResaHC'];
    $HeureDebutResaHC = $_GET['HeureDebutResaHC'];
    $DureeResaHC = $_GET['DureeResaHC'];
    $action = 'index.php?action=modifierReservationHC';
    $action2 = 'modifier';
    $btn = 'Modifier';
    $MatSelect = IdMatSelect($IdMatHC);
    $MatDispoHC =  MatDispoHC($HeureDebutResaHC , $DureeResaHC);
}else{ 
    $IdResHC = "";
    $IdENSHC = "";
    $IdAHC ="";
    $IdMatHC="";
    $DateResaHC = "";
    $HeureDebutResaHC = "";
    $DureeResaHC = "";
    $action= 'index.php?action=creerReservationHC';
    $action2 = 'creer';
    $btn = 'Ajouter';
    $MatSelect ="";
}
?>
<script>
    function change_valeurM(){
        var select = document.getElementById('debut').value;
        var select2 = document.getElementById('fin').value;
        var test = AjaxM(select, select2);
        var listeMat = '<select class="form-control" id="mat" name="IdMatHC" required><option value="">Choisir un matériel</option>';
        $.each($.parseJSON(test), function(i, obj) {
            listeMat += '<option value='+obj.IdMat+'>n°: '+obj.numSerie+' '+obj.TypeMat+'</option>';
        }); 
        listeMat += '</select>';
        document.getElementById('Mat').innerHTML = listeMat;
        console.log(test);
    }
    function AjaxM(select, select2){
        return $.ajax({
            url:'Ajax/materielReservationHC.php',
            type:'POST',
            data:{debut:select, fin:select2},
            async: false
        }).responseText;
    }
</script>
<div class="container">
    <br>
    <div class="row content">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <br>
            <h4>Insertion d'une réservation hors séance</h4>
            <br>
            <form action="<?php echo $action; ?>" method="POST">
                    <?php 
                        echo ($action2 == 'modifier') ? "<input type='hidden' name='IdResHC' value='$IdResHC'>" : ""; 
                    ?>
                <div class="form-group">                  
                    <label for="pwd">Enseignant :</label>                        
                    <select <?php if($action2 == 'modifier'){ echo 'disabled';}?> class="form-control" id="typeSalle" name="IdENSHC" required>
                        <option value="">Choisir un enseignant</option>
                            <?php 
                                for ($i=0;$i<=count($data['enseignants'])-1;$i++){
                            ?>
                                    <option <?php if($IdENSHC == $data['enseignants'][$i]['IdENS']){ echo 'Selected';}?> value="<?php echo $data['enseignants'][$i]['IdENS']?>"><?php echo utf8_encode($data['enseignants'][$i]['NomENS']." ".$data['enseignants'][$i]['PrenomENS'])?></option>
                            <?php
                                }
                            ?>
                    </select>
                     <input <?php if($action2 != 'modifier'){ echo 'disabled';}?> hidden value ="<?php echo  $resENS[$i]['IdENS'] ?>" name ='IdENSHC'>
                </div>
                 <div class="form-group">
                    <span>
                        <label for="pwd">Début de réservation :<p style="color: #ff8533; font-style: italic">Format : YYYY-MM-DD hh:mm:ss </p></label>
                        <input   <?php if($action2 == 'modifier'){ echo 'disabled';}?> onkeyup="change_valeurM()" type="text" class="form-control" id="debut" placeholder="ex : 2019-06-14 08:30:00" name="HeureDebutResaHC" value='<?php echo $HeureDebutResaHC?>' required>
                     <input <?php if($action2 != 'modifier'){ echo 'disabled';}?> hidden value ="<?php echo $HeureDebutResaHC?>" name ='HeureDebutResaHC'>
                    </span>

                </div>
                <div class="form-group">
                    <label for="pwd">Fin de réservation :<p style="color: #ff8533; font-style: italic">Format : YYYY-MM-DD hh:mm:ss</p></label>
                    <input   <?php if($action2 == 'modifier'){ echo 'disabled';}?> onkeyup="change_valeurM()" type="text" class="form-control" id="fin" placeholder="ex : 2019-06-14 11:00:00" name="DureeResaHC" value='<?php echo $DureeResaHC?>' required>
                    <input <?php if($action2 != 'modifier'){ echo 'disabled';}?> hidden value ="<?php echo $DureeResaHC?>" name ='DureeResaHC'>
                </div> 
                <div class="form-group" id="Mat">
                    <label for="pwd">Matériel :</label>                        
                    <select class="form-control" id="typeSalle" name="IdMatHC" required>
                        <option value="">Choisir un matériel</option>
                        <?php 
                        if($action2 == 'modifier'){
                            for ($i=0;$i<=count($MatSelect)-1;$i++){
                        ?>
                                <option Selected  value="<?php echo  $IdMatHC?>"><?php echo  utf8_encode($MatSelect[$i]['numSerie'])."  ". utf8_encode( $MatSelect[$i]['TypeMat'])?></option>;
                            <?php
                            }
                            for ($i=0;$i<=count($MatDispoHC)-1;$i++){
                            ?>
                                <option   value="<?php echo $MatDispoHC[$i]['IdMat']?>"><?php echo utf8_encode($MatDispoHC[$i]['numSerie']." ".$MatDispoHC[$i]['TypeMat'])?></option>
                        <?php
                            }                            
                        }
                        ?>
                    </select>
                </div>    
                <button type="submit" class="btn btn-primary btn-block"><?php echo $btn; ?></button>
            </form>      
        </div>
        <div class="col-sm-4"></div>
    </div>
</div> 