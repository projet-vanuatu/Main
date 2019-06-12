<?php
if($data['selectedFormation'] != ""){
    $selectedFormation = $data['selectedFormation'];
}else{
    $selectedFormation = "";
}
if($data['selectedTD'] != ""){
    $selectedTD = $data['selectedTD'];
}else{
    $selectedTD = "";
}
?>
<div class="container">
    <h3><center>Affectation des étudiants aux groupes de TD</center></h3>
    <div class="row content">
        <div class="col-sm-4"></div>
        <form action="index.php?action=gererAffectationTD&type=formation" method="POST">
            <div class="col-sm-4">    
                <label for="sel1">Formation :</label>
                <select class="form-control" id="sel1" name='IdF' onchange="this.form.submit()" >
                    <option value="0">Choisir une formation </option>
                    <?php
                    for($i=0;$i<=count($data['formations'])-1;$i++){
                    ?>
                        <Option <?php if($selectedFormation == $data['formations'][$i]['IdF']){echo "selected";} ?> 
                            value ="<?php echo $data['formations'][$i]['IdF'] ?>"><?php echo $data['formations'][$i]['IntituleF']?></option>     
                    <?php
                    }
                    ?> 
                </select>
            </div>
        </form>
    </div>

    <div class="row content">
        <div class="col-sm-5 sidenav"> 
           <label for="sel1">Liste des étudiants non affecctés à un groupe de TD :</label>
           <select multiple class="form-control" id="nonAff" style="height:500px;" onchange="changeURL(id, value);">
            <?php
            for($i=0;$i<=count($data['nonAff'])-1;$i++){
            ?>
                <Option value ="<?php echo $data['nonAff'][$i]['IdE']?>">
                    <?php echo $data['nonAff'][$i]['IdE']." ".$data['nonAff'][$i]['NomE']." ".$data['nonAff'][$i]['PrenomE']?>
                </option>     
            <?php
            }
            ?> 
           </select>
        </div>

        <div class="col-sm-2 sidenav">
          <div style="margin-top:80%; margin-left:30%; padding:10px;">
            <!--ajout-->
            <p><a id="addURL" href="index.php?action=affecterEtudiant"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-right"></span></button></a></p>
            <!--delete-->
            <p><a id="removeURL" href="index.php?action=desaffecterEtudiant"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span></button></a></p>            
          </div>
        </div>

        <div class="col-sm-5 sidenav">
            <label for="sel1">Groupe de TD:</label>
            <form action="index.php?action=gererAffectationTD&type=td" method="POST">
                <select class="form-control" id="idGroupTD" name ='IdGTD' onchange="this.form.submit()">
                    <option >Choisir un groupe de TD </option>
                     <?php
                     for($i=0;$i<=count($data['td'])-1;$i++){
                    ?>
                        <Option <?php if($selectedTD == $data['td'][$i]['IdGTD']){ echo "Selected ";} ?> 
                            value ="<?php echo $data['td'][$i]['IdGTD'] ?>">
                                <?php echo $data['td'][$i]['NumGroupTD']?>
                        </option>     
                    <?php
                    }
                    ?> 
                </select>
            </form>
            <select multiple class="form-control" id="groupTD" style="height:450px;" onchange="changeURL(id, value);">
                <?php
                for($i=0;$i<=count($data['aff'])-1;$i++){
                ?>
                    <Option value ="<?php echo $data['aff'][$i]['IdE']?>"><?php echo $data['aff'][$i]['IdE']." ".$data['aff'][$i]['NomE']." ".$data['aff'][$i]['PrenomE']?></option>     
                <?php
                }
                ?> 
            </select>
        </div>
    </div>
</div>