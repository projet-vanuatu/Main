<?php
$btnNav = 'Retour';
if(isset($data['formulaire'])){
    $formAction = 'index.php?action=modifierReservation';
    $action = 'modifier';
    $btn = 'Modifier';
    $IdRes = $data['formulaire']['IdRes'];
    $idens = $data['formulaire']['IdENS'];
    $ids = $data['formulaire']['NumS'];
    $idMat = $data['formulaire']['IdMat'];
}else if(isset($data['preSelection'])){
    $formAction= 'index.php?action=creerReservation';
    $action = 'modifier';
    $btn = 'Ajouter';
    $IdRes = "";
    $idens = $data['preSelection']['idEnseignant'];
    $ids = $data['preSelection']['idSeance'];
    $idMat = "";   
}else{
    $idens = "";
    $IdRes = "";
    $ids = "";
    $idMat = "";
    $formAction= 'index.php?action=creerReservation';
    $action = 'creer';
    $btn = 'Ajouter';
}
?>
<script>
    function change_valeurS(){
        var select = document.getElementById('idens').value;
        var test = AjaxS(select);
        var seance = document.getElementById('ids').value;
        var listeSeance = '<label for="pwd">Séance :</label><select class="form-control" name="NumS" id="ids" onchange="change_valeurM()" required><option>Sélectionner une séance</option>';
        $.each($.parseJSON(test), function(i, obj){
            if(obj.NumS === seance){
                listeSeance += '<option selected value='+obj.NumS+'>'+obj.titre+' le '+obj.date+' de '+obj.heureD+' à '+obj.heureF+'</option>';    
            }else{
                listeSeance += '<option value='+obj.NumS+'>'+obj.titre+' le '+obj.date+' de '+obj.heureD+' à '+obj.heureF+'</option>';
            }
        }); 
        listeSeance += '</select>';
        document.getElementById('seanceDispo').innerHTML = listeSeance;   
    }
    function AjaxS(select) {
        return $.ajax({
            url:'Ajax/Gestionnaire/seanceEnseignant.php',
            type:'POST',
            data:{idens:select},
            async: false
        }).responseText;
    }   
    function change_valeurM(){
        var select = document.getElementById('ids').value;
        var idMat = document.getElementById('idMateriel').value;
        var test = AjaxM(select, idMat);
        var listeMat = '<label for="pwd">Matériel :</label><select class="form-control" id="choixMateriel" name="IdMat" required><option value="">Choisir un matériel</option>';
        $.each($.parseJSON(test), function(i, obj){
            listeMat += '<option value='+obj.IdMat+'>n°: '+obj.numSerie+' '+obj.TypeMat+'</option>';   
        }); 
        listeMat += '</select>';
        document.getElementById('MatDispo').innerHTML = listeMat;   
    }
    function AjaxM(select, idMat) {
        return $.ajax({
            url:'Ajax/Gestionnaire/materielReservationDisponible.php',
            type:'POST',
            data:{ids:select, idMat:idMat},
            async: false
        }).responseText;
    }
    function materielPreselection(){
        var valeur = document.getElementById('idMateriel').value;
        if(valeur !== ''){
            var options = document.getElementById('choixMateriel').options;
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
        <div class="col-sm-10" <?php echo ($action == 'modifier') ? "style='pointer-events:none; opacity:0.7;'" : NULL; ?>>
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
            <a href="index.php?action=gererReservation">
                <button type="button" class="btn btn-primary"><?php echo $btnNav; ?></button>
            </a>            
        </div>
    </div>
</div>
<div class="container">
    <div class="row content">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <br>
            <h4>Insertion d'une réservation pour une séance</h4>
            <br>
            <form action="<?php echo $formAction; ?>" method="POST">
                <?php
                    //modification
                    if($action == 'modifier' && $btn == 'Modifier'){
                        echo "<input type='hidden' id='idReservation' name='IdRes' value='$IdRes'>";
                        echo "<input type='hidden' id='ids' name='oldSeance' value='$ids'>";
                        echo "<input type='hidden' id='idMateriel' name='oldMat' value='$idMat'>";
                    //création après séance
                    }else if($action == 'modifier' && $btn == 'Ajouter'){
                        echo "<input type='hidden' id='idReservation' name='IdRes' value='1'>";
                        echo "<input type='hidden' id='ids' name='oldSeance' value='$ids'>";
                        echo "<input type='hidden' id='idMateriel' name='oldMat' value='-1'>";
                    //création seule
                    }else{
                        echo "<input type='hidden' id='idReservation' name='IdRes' value=''>";
                        echo "<input type='hidden' id='idMateriel' name='oldMat' value='-1'>";
                    }
                ?>
                <div class="form-group">
                    <label for="pwd">Enseignant :</label>                        
                    <select class="form-control" name="IdENS" id="idens" onchange="change_valeurS();" required>
                        <option value="">Choisir un enseignant</option>
                            <?php 
                                for ($i=0;$i<=count($data['enseignants'])-1;$i++){
                            ?>
                                <option <?php if($idens == $data['enseignants'][$i]['IdENS']){ echo 'Selected';}?>
                                    value="<?php echo $data['enseignants'][$i]['IdENS']; ?>"><?php echo utf8_encode($data['enseignants'][$i]['NomENS']." ".$data['enseignants'][$i]['PrenomENS']); ?></option>
                            <?php
                                }
                            ?>
                    </select>
                </div>
                <div class="form-group" id="seanceDispo">
                    <label for="pwd">Séance :</label>
                    <select class="form-control" name="NumS" id="ids" required>
                        <option>Sélectionner une séance</option>
                    </select>
                </div>
                <div class="form-group" id="MatDispo">
                    <label for="pwd">Matériel :</label>                        
                    <select class="form-control" id='choixMateriel' name="IdMat" required>
                        <option value="">Choisir un matériel</option>
                    </select>
                </div> 
                <button type="submit" class="btn btn-primary btn-block" ><?php echo $btn; ?></button>
            </form>      
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>
<script type="text/javascript">
    var idReservation = document.getElementById('idReservation').value;
    if(idReservation){
        change_valeurS();
        change_valeurM();
        materielPreselection();       
    }
</script>