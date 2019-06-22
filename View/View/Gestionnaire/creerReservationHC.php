<?php
if(isset($data['formulaire'])){
    date_default_timezone_set(TIMEZONE);
    $IdResHC = $data['formulaire']['IdResHC'];
    $IdENSHC = $data['formulaire']['IdEns'];
    $IdMatHC = $data['formulaire']['IdMat'];
    $IdA = $data['formulaire']['IdA'];
    $dateToChange = $data['formulaire']['DateDebutResaHC'];
    $Date = date("Y-m-d", strtotime($dateToChange));
    $heureDebutCh = $data['formulaire']['DateDebutResaHC'];
    $HeureDebut = date("H:i:s", strtotime($heureDebutCh));
    $dateFinCh = $data['formulaire']['DateFinResaHC'];
    $HeureFin = date("H:i:s", strtotime($dateFinCh));
    $action = 'index.php?action=modifierReservationHC';
    $action2 = 'modifier';
    $btn = 'Modifier';
    $btnNav = 'Retour';
}else{ 
    $IdResHC = "";
    $IdENSHC = "";
    $IdMatHC="";
    $Date = "";
    $HeureDebut = "";
    $HeureFin = "";
    $action= 'index.php?action=creerReservationHC';
    $action2 = 'creer';
    $btn = 'Ajouter';
    $MatSelect ="";
    $btnNav = 'Retour';
}
?>
<script>
    function change_valeurM(){
        var date =  document.getElementById('date').value;
        var dateDebut = document.getElementById('debut').value;
        var dateFin = document.getElementById('fin').value;
        var reservation = document.getElementById('materielReserver').value;
        var ajaxDone = false;
        if(date && dateDebut && dateFin){
            var test = AjaxM(date, dateDebut, dateFin, reservation);         
            var listeMat = '<label for="choixMat">Matériel :</label><select class="form-control" id="choixMat" name="IdMatHC" required><option value="">Choisir un matériel</option>';
            $.each($.parseJSON(test), function(i, obj) {
                listeMat += '<option value='+obj.IdMat+'>n°: '+obj.numSerie+' '+obj.TypeMat+'</option>';
            }); 
            listeMat += '</select>';
            document.getElementById('Mat').innerHTML = listeMat;
            ajaxDone = true;
        }
        return ajaxDone;
    }
    function AjaxM(date, debut, fin, reservation){
        return $.ajax({
            url:'Ajax/materielReservationHC.php',
            type:'POST',
            data:{date:date, debut:debut, fin:fin, reservation:reservation},
            async: false
        }).responseText;
    }
    function preSelection(){
        var valeur = document.getElementById('preValue').value;
        if(valeur !== ''){
            var options = document.getElementById('choixMat').options;
            var longueur = options.length
            for(var i=0;i<=longueur-1;i++){
                if(options[i].value === valeur){
                    options[i].selected= true;
                    break;
                }
            }           
        }
    }
</script>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-10" <?php echo ($action2 == 'modifier') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=creerReservation">Créer réservation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=creerReservationHC">Créer réservation hors séances</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-2">
            <a href="index.php?action=gererReservation&active=hc">
                <button type="button" class="btn btn-primary"><?php echo $btnNav; ?></button>
            </a>            
        </div>
    </div>
</div>
<div class="container">
    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <h2><center><?php echo $btn." une réservation hors séances"; ?></center></h2>
            </div>           
            <div class="col-sm-3">

            </div>
        </div>
    </div>
    <br>
    <div class="row content">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form name="reservationHC" action="<?php echo $action; ?>" method="POST">
                <?php
                    echo ($action2 == 'modifier') ? "<input type='hidden' id='materielReserver' name='IdResHC' value='$IdResHC'>" :
                            "<input type='hidden' id='materielReserver' name='IdResHC' value='-1'>";
                    echo "<input type='hidden' id='preValue' name='preValue' value='$IdMatHC'>";
                ?>
                <div class="form-group">                  
                    <label for="pwd">Enseignant :</label>                        
                    <select class="form-control" id="typeSalle" name="IdENSHC" required>
                        <option value="">Choisir un enseignant</option>
                            <?php 
                            for ($i=0;$i<=count($data['enseignants'])-1;$i++){
                            ?>
                                <option <?php if($IdENSHC == $data['enseignants'][$i]['IdENS']){echo 'Selected';}?> 
                                    value="<?php echo $data['enseignants'][$i]['IdENS']?>">
                                        <?php echo utf8_encode($data['enseignants'][$i]['NomENS']." ".$data['enseignants'][$i]['PrenomENS'])?></option>
                            <?php
                            }
                            ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pwd">Date de réservation :</label>
                    <input class="form-control" name="date" id="date" type="date" name="dateReservation" onchange="change_valeurM();" required
                           value="<?php echo ($Date != "") ? $Date : "" ; ?>">
                </div>              
                <div class="form-group">
                    <label for="pwd">Date de réservation :</label>
                    <input class="form-control" name="debut" id="debut" type="time" name="heureDebut" min="07:00" max="21:00" step="1800" 
                           onchange="change_valeurM();" required value="<?php echo ($HeureDebut != "") ? $HeureDebut : "" ; ?>">
                </div>               
                <div class="form-group">
                    <label for="pwd">Date de réservation :</label>
                    <input class="form-control" name="fin" id="fin" type="time" name="heureFin" min="07:00" max="21:00" step="1800" 
                           onchange="change_valeurM();" required value="<?php echo ($HeureFin != "") ? $HeureFin : "" ; ?>">
                </div>                         
                <div class="form-group" id="Mat">
                    <label for="choixMat">Matériel :</label>                        
                    <select class="form-control" id="choixMat" name="IdMatHC" required>
                        <option value="">Choisir un matériel</option>
                    </select>
                </div>               
                <button type="submit" class="btn btn-primary btn-block"><?php echo $btn; ?></button>
            </form>      
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>
<script type="text/javascript">
    var changer = change_valeurM();
    if(changer){
       preSelection(); 
    } 
</script>