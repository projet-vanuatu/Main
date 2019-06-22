<pre><?php // var_dump($data); ?></pre>
<?php
    $idens = $data['idEnseignant'];
    $idf = $data['idFormation'];
    $cspe = $data['coursSpe'];
    $nummcm = $data['matiereCM'];
    $nummtd = $data['matiereTD'];
    $numgtd = $data['groupeTD'];
    $numgcm = $data['groupeCM'];
    $datedebut = $data['dateDebut'];
    $datefin = $data['dateFin'];
    $Salle = $data['salles'];
?>
<script>
    function cacher(){
        if(document.getElementById('CM').checked) {
            if(document.getElementById('Standard').checked) {
                document.getElementById('afficher').style.display = 'none';
                document.getElementById('afficher2').style.display = 'none';
                document.getElementById('afficher1').style.display = 'block';
                document.getElementById('afficher3').style.display = 'none';
                document.getElementById('afficher').disabled = true;
                document.getElementById('afficher2').disabled = true;
                document.getElementById('afficher1').disabled = false;
                document.getElementById('afficher3').disabled = true; 
                document.getElementById('grpCM').disabled = false;
            }else if(document.getElementById('Special').checked) {
                document.getElementById('afficher').style.display = 'none';
                document.getElementById('afficher2').style.display = 'none';
                document.getElementById('afficher1').style.display = 'none'; 
                document.getElementById('afficher3').style.display = 'block';
                document.getElementById('afficher').disabled = true;
                document.getElementById('afficher2').disabled = true;
                document.getElementById('afficher1').disabled = true;
                document.getElementById('afficher3').disabled = false;
                document.getElementById('grpCM').disabled = false;
            }
        }else if(document.getElementById('TD').checked) {
            if(document.getElementById('Standard').checked) {
                document.getElementById('afficher').style.display = 'block';
                document.getElementById('afficher2').style.display = 'block';
                document.getElementById('afficher1').style.display = 'none';
                document.getElementById('afficher3').style.display = 'none'; 
                document.getElementById('afficher').disabled = false;
                document.getElementById('afficher2').disabled = false;
                document.getElementById('afficher1').disabled = true;
                document.getElementById('afficher3').disabled = true;
                document.getElementById('grpCM').disabled = true;
            }else if(document.getElementById('Special').checked) {
                document.getElementById('afficher').style.display = 'block';
                document.getElementById('afficher2').style.display = 'none';
                document.getElementById('afficher1').style.display = 'none'; 
                document.getElementById('afficher3').style.display = 'block'; 
                document.getElementById('afficher').disabled = false;
                document.getElementById('afficher2').disabled = true;
                document.getElementById('afficher1').disabled = true;
                document.getElementById('afficher3').disabled = false; 
                document.getElementById('grpCM').disabled = true;
            }   
        }
    }  
    function change_valeurS(){
        var select = document.getElementById('ids').value;
        var test = AjaxS(select);
        var listeMateriel = '<center><b>Matériel(s) présent(s) dans la salle :</b><br><table><thead><tr><th>Matériels :</th><th>Etat de fonctionnement :</th></tr></thead></br>';
        $.each($.parseJSON(test), function(i, obj) {
            listeMateriel += '<tbody><tr><td>' + obj.TypeMat + "</td><td> " + obj.Etat_fonctionnement + '</td></tr></tbody>';
        }); 
        listeMateriel += '</table></center>';
        document.getElementById('listeMat').innerHTML = listeMateriel;   
    }
    function AjaxS(select){
        return $.ajax({
            url:'Ajax/MaterielSalle.php',
            type:'POST',
            data:{ids:select},
            async: false
        }).responseText;
    }
    function change_valeurCM(){
        var sel = document.getElementById('numm').value;
        var test2 = AjaxCM(sel);
        var heuresMat = '<center><b>Heures :</b><br><table><thead><tr><th>Nombre dheures à effectuer :</th><th>Heures déjà affectées :</th></tr></thead></br>';
        $.each($.parseJSON(test2), function(i, obj){
            if(obj.HRest){
                var HRest = obj.HRest;
            }else{
                var HRest = '00:00';
            }
            heuresMat += '<tbody><tr><td>' + obj.NbHeuresFixees + ' h 00 </td><td>' +  HRest + '</td></tr></tbody>' ;
            console.log(obj.NbHeuresFixees);
        }); 
        heuresMat += '</table></center>';
        document.getElementById('heure').innerHTML = heuresMat;   
    }
    function AjaxCM(sel) {
        return $.ajax({
            url:'Ajax/heuresMatCM.php',
            type:'POST',
            data:{numm:sel},
            async: false
        }).responseText;
    }     
    function change_valeurTD(){
        var sel = document.getElementById('num').value;
        var grp = document.getElementById('grptd').value;
        var test2 = AjaxTD(sel,grp);
        var heuresMat = '<center><b>Heures :</b><br><table><thead><tr><th>Nombre dheures à effectuer :</th><th>Heures déjà affectées :</th></tr></thead></br>';
        $.each($.parseJSON(test2), function(i, obj) {
            if(obj.HRest){
                var HRest = obj.HRest;
            }else{
                var HRest = '00:00';
            }
            heuresMat += '<tbody><tr><td>' + obj.NbHeuresFixees + ' h 00 </td><td>' +  HRest + '</td></tr></tbody>' ;
            console.log(obj.NbHeuresFixees);
        }); 
        heuresMat += '</table></center>';
        document.getElementById('heure').innerHTML = heuresMat;   
    }
    function AjaxTD(sel,grp) {
        return $.ajax({
            url:'Ajax/heuresMatTD.php',
            type:'POST',
            data:{numm:sel,grp:grp},
            async: false
        }).responseText;
    }
</script>
<br>
<div class="row" style="height:7%;width:100%;">
    <div class="col-sm-4"></div>
    <div class="col-sm-4"><h2><center>Création d'une séance :</center></h2></div>
    <div class="col-sm-4"></div>
</div>
<div class="container-fuild" style="margin:60px;">
    <div class="row "style="width:100%;">
        <div class="col-sm-3 jumbotron" id='listeMat'><center><b> Matériel(s) présent(s) dans la salle :</b></center><br></div>
        <div class="col-sm-1"></div>    
        <div class="col-sm-4 jumbotron">            
            <form action ='index.php?action=creerSeance' method="POST">
                <input type="hidden" name="IdENS" value="<?php echo $idens; ?>">
                <label for="sel1">Enseignant : <?php echo $data['enseignants'][0]['NomENS']." ".$data['enseignants'][0]['PrenomENS']; ?></label><br>
                <label for="sel1">Formation : <?php echo $data['formations'][0]['IntituleF']; ?></label><br>
                <label for="sel1">Début: <?php echo $datedebut; ?></label>
                <input hidden name='start' value='<?php echo $datedebut; ?>'>
                <label for="sel1"> Fin : <?php echo $datefin ?></label>
                <input hidden name='end' value='<?php echo $datefin; ?>'>
                <br>
                <label for="sel1"> Type de cours :</label><br>
                <label for="sel1"> CM :</label>
                <input checked="checked" onclick = "cacher();" type ='radio'  name ='Typec' id="CM" value='CM'>
                <label for="sel1" > TD :</label>
                <input onclick = "cacher();" type ='radio' name ='Typec' id="TD" value ='TD'><br>
                <input id="grp" name="grpcm" type="hidden" value="<?php echo $numgcm[0]['IdGCM']?>">
                <div id ='afficher' style='display:none;'>
                    <label  for="sel1"> Groupe de TD :</label>
                    <select class="form-control"  name="grptd" id='grptd' onchange="change_valeurTD();">
                        <option value ='' >Groupe de TD  </option>
                        <?php             
                        for($i=0;$i<=count($numgtd)-1;$i++){
                        ?>
                            <Option value ="<?php echo $numgtd[$i]['IdGTD'] ?>"><?php echo $numgtd[$i]['NumGroupTD']?></option>     
                        <?php
                        }
                        ?>
                    </select>
                </div>   
                <br>
                <label for="sel1"> Type de matière :</label><br>
                <label for="sel1"> Standard :</label>
                <input checked="checked" onclick = "cacher();" type ='radio'  name ='Typem' id="Standard" value='ST'>
                <label for="sel1" > Spécial :</label>
                <input onclick = "cacher();" type ='radio' name ='Typem' id="Special" value='SP'><br>
                <div id ='afficher1' style='display:block;'>
                <label for="sel1"> Matière CM :</label>
                <select id='numm' class="form-control"  name="titleCM" onchange="change_valeurCM();" >
                    <option value ='' >Selectionnez une matière</option>
                    <?php             
                    for($i=0;$i<=count($nummcm)-1;$i++){
                    ?>
                        <Option value ="<?php echo $nummcm[$i]['NumM'] ?>"><?php echo utf8_encode($nummcm[$i]['IntituleM'])?></option>     
                    <?php
                    }
                    ?>
                </select>
                </div>
                <div id ='afficher2' style='display:none;'>
                    <label  for="sel1"> Matière TD :</label>
                    <select class="form-control"  name="titleTD" id="num" onchange="change_valeurTD();">
                        <option value ='' >Selectionnez une matière</option>
                        <?php             
                        for($i=0;$i<=count($nummtd)-1;$i++){
                        ?>
                            <Option  value ="<?php echo $nummtd[$i]['NumM'] ?>"><?php echo utf8_encode($nummtd[$i]['IntituleM'])?></option>     
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div id ='afficher3' style='display:none;'>
                    <label  for="sel1"> Cours spécial :</label>
                    <select class="form-control"  name="titleCS">
                        <option value ='' >Selectionnez un cours spécial</option>
                        <?php             
                        for($i=0;$i<=count($cspe)-1;$i++){
                        ?>
                            <Option value ="<?php echo $cspe[$i]['IdCSPE'] ?>"><?php echo $cspe[$i]['IntituleCSPE']?></option>     
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <br>
                <label  for="sel1"> Salle disponibles à cet horaire :</label>
                <select id='ids' class="form-control"  name="ids" onchange="change_valeurS();">
                        <option value ='' >Selectionnez une salle</option>
                        <?php             
                        for($i=0;$i<=count($Salle)-1;$i++){
                        ?>
                            <Option value ="<?php echo $Salle[$i]['IdS'] ?>">
                                <?php echo $Salle[$i]['NomS']." ".$Salle[$i]['NomSITE']." - Capacité : ".$Salle[$i]['CapaciteS'];?></option>     
                        <?php
                        }
                        ?>
                </select>
                <br>
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-7">
                        <input class="btn btn-primary" type='submit' value ='Valider' onClick="" name='Valider' style="float:left;">
                        <input class="btn btn-primary" type='submit'  value ='Valider et reserver' name='Valider' style="float:right;">     
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </form>
            <hr>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4"><a href="index.php?action=gererPlanning"><button class="btn btn-danger btn-block">Annuler</button></a></div>
                <div class="col-sm-4"></div>
            </div>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-3 jumbotron" id="heure">
            <center><b>Heures :</b></center>
        </div>
    </div>
</div>

<pre>
<?php
    var_dump($data);
?>
</pre>