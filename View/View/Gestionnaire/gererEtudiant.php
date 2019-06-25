<br>
<div class="container">
    <div class="row">           
        <div class='row'>
            <div class='col-sm-2'></div>
            <div class='col-sm-8'>
                <div class ='jumbotron' style="padding: 2%;">
                    <div class='row'>
                        <div class="col-sm-12">
                            <p style="font-weight: bold; font-size: 20px">Filtres</p>
                        </div>
                    </div>
                    <div class='row'>
                        <form action="index.php?action=gererEtudiant" method="POST" onchange="this.submit();">
                            <div class="col-sm-6">                            
                                <label>Formation :</label>
                                <select class="form-control" name="IdF">
                                    <option value="0">Sélectionner une formation</option>
                                    <?php
                                        for($i=0;$i<=count($data['formations'])-1;$i++){
                                    ?>
                                        <option <?php if($data['formSelected'] == $data['formations'][$i]['IdF']){ echo "Selected ";} ?> name="choixFormation" value="<?php echo $data['formations'][$i]['IdF']?>">
                                            <?php echo utf8_encode($data['formations'][$i]['IntituleF']) ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>&nbsp;&nbsp;Groupe de TD :</label>
                                <input type="hidden" name="type" value="td">
                                <select class="form-control" name="IdGTD" >
                                    <option value="0">Sélectionner un groupe de td</option>
                                    <?php
                                        for($i=0;$i<=count($data['groupTD'])-1;$i++){
                                    ?>
                                        <option  <?php if($data['tdSelected'] == $data['groupTD'][$i]['IdGTD']){echo "Selected ";} ?> value="<?php echo $data['groupTD'][$i]['IdGTD']; ?>">
                                            <?php echo $data['groupTD'][$i]['NumGroupTD']; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>                               
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class='col-sm-2'></div>
        </div>
    </div>  
</div>
<br>        
<div class="container">
    <h3>Liste des étudiants :</h3>
    <hr>
    <div class="row">
        <div class="col-sm-8">
            <input type="text" class="form-control" id="myInput" onkeyup="myFunction();" placeholder="Rechercher..">
        </div>
        <div class="col-sm-3"></div>
        <div class="col-sm-1">
            <button onClick="imprimer('imprime');" type="button" class="btn btn-danger">Imprimer</button>
        </div>        
    </div>
    <input hidden id="detruire">
    <br>
    <script>    
        function imprimer() {
            var elem = document.getElementById('removeClass');
            elem.classList.remove("table-wrapper-scroll-y");
            elem.classList.remove("my-custom-scrollbar");           
            var printContents = document.getElementById('imprime').innerHTML;    
            var originalContents = document.body.innerHTML;      
            document.body.innerHTML = printContents;     
            window.print();     
            document.body.innerHTML = originalContents;
            location.reload();
        } 
    </script>
    <div id="imprime">
        <div id="removeClass" class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-hover" id="tableR">
                <thead class="header">
                    <tr>
                        <th>Identifiant</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Formation</th>
                        <th>Groupe TD</th>
                    </tr>
                </thead>
                <tbody id="myTable">                       
                <?php             
                for($i=0;$i<=count($data['listEtudiant'])-1;$i++){
                ?>
                    <tr>
                        <td><?php echo $data['listEtudiant'][$i]['IdE']; ?></td>
                        <td><?php echo $data['listEtudiant'][$i]['NomE']; ?></td>
                        <td><?php echo $data['listEtudiant'][$i]['PrenomE']; ?></td>
                        <td><?php echo (isset($data['listEtudiant'][$i]['IntituleF'])) ? $data['listEtudiant'][$i]['IntituleF'] : 'Non affecté'; ?></td>
                        <td><?php echo (isset($data['listEtudiant'][$i]['NumGroupTD'])) ? $data['listEtudiant'][$i]['NumGroupTD'] : 'Non affecté'; ?></td>
                    </tr>
                <?php
                }
                ?>                      
                </tbody>
            </table>
        </div>
    </div>
</div> 
