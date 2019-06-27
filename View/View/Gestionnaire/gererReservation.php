<br>
<br>
<div class="container-fluid" style="margin: 0px 50px 0px 50px;">
      <ul class="nav nav-tabs">
              <li class="nav-item">
                  <a class="nav-link acstive" onclick="afficherR();">Réservations des séances</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" onclick="afficherRHC()">Réservations hors séances</a>
              </li>               
      </ul>
</div>
<br>
<div class="container-fluid">
    <br>
    <div class="row" <?php echo (is_null($data['active'])) ? "style='display:block;'" : "style='display:none;'"; ?> id="searchR">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="inputR" onkeyup="myFunction();" placeholder="Rechercher.." title="">
            </div>
        </div>
        <script>
            $(document).ready( function(){
                $("#inputR").on("keyup", function(){
                    var value = $(this).val().toLowerCase();
                    $("#tableR tr").filter(function(){
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
            function afficherR(){
                document.getElementById("searchR").style.display = "block";
                document.getElementById("searchRHC").style.display = "none";
            }
            function afficherRHC(){
                document.getElementById("searchR").style.display = "none";
                document.getElementById("searchRHC").style.display = "block";
            }
        </script>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h3>Réservations de séances &nbsp;&nbsp;<a href="index.php?action=creerReservation"><button type="button" class="btn btn-success">Créer</button></a></h3>
                <table class="table table-hover bg-global-gris" id="tableR" >
                    <thead>
                         <th>Nom</th>
                         <th>Prénom</th>
                         <th>Matériel</th>
                         <th>Numéro de série</th>
                         <th>Matière</th>
                         <th>Reservé le :</th>
                         <th>Date de la séance</th>
                         <th>Début</th>
                         <th>Fin</th>
                    </thead>
                    <tbody>
                        <?php
                        for($i=0;$i<=count($data['reservations'])-1;$i++){
                        ?>
                            <tr>               
                                <td><?php echo utf8_encode($data['reservations'][$i]['NomENS']); ?></td>
                                <td><?php echo utf8_encode($data['reservations'][$i]['PrenomENS']); ?></td>
                                <td><?php echo utf8_encode($data['reservations'][$i]['TypeMat']); ?></td> 
                                <td><?php echo $data['reservations'][$i]['NumSerie']; ?></td>
                                <td><?php echo utf8_encode($data['reservations'][$i]['IntituleM']); ?></td>
                                <td><?php echo $data['reservations'][$i]['resa']; ?></td>
                                <td><?php echo $data['reservations'][$i]['date']; ?></td>
                                <td><?php echo $data['reservations'][$i]['debut']; ?></td>
                                <td><?php echo $data['reservations'][$i]['fin']; ?></td> 
                                <?php
                                    $IdRes = $data['reservations'][$i]['IdRes'];
                                ?>
                                <td>
                                    <p> 
                                        <a href="index.php?action=modifierReservation&id=<?php echo $IdRes; ?>">
                                        <button type="button" class="btn btn-warning">Modifier</button>
                                        </a>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <a href="index.php?action=supprimerReservation&id=<?php echo $IdRes; ?>">
                                        <button type="button" class="btn btn-danger" >Supprimer</button>
                                        </a>
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
    <div class="row" <?php echo (!is_null($data['active'])) ? "style='display:block;'" : "style='display:none;'"; ?> id="searchRHC">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="inputRHC" onkeyup="myFunction();" placeholder="Rechercher.." title="">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <script>
            $(document).ready(
            function(){
                $("#inputRHC").on("keyup", function(){
                var value = $(this).val().toLowerCase();
                    $("#tableRHC tr").filter(function(){
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h3>Réservations hors séances &nbsp;&nbsp;<a href="index.php?action=creerReservationHC"><button type="button" class="btn btn-success">Créer</button></a></h3>
                <table class="table table-hover bg-global-gris"  id="tableRHC" >
                    <thead>
                         <th>Nom</th>
                         <th>Prénom</th>
                         <th>Matériel</th>
                         <th>Numéro de série</th>
                         <th>Reservé le :</th>
                         <th>Date</th>
                         <th>Debut</th>
                         <th>Fin</th>
                    </thead>
                    <tbody>
                        <?php
                          for($i=0;$i<=count($data['reservationsHC'])-1;$i++){
                        ?>
                              <tr>
                                  <!-- AFFICHAGE DONNEES -->
                                  <td><?php echo utf8_encode($data['reservationsHC'][$i]['NomENS']); ?></td>
                                  <td><?php echo utf8_encode($data['reservationsHC'][$i]['PrenomENS']); ?></td>
                                  <td><?php echo utf8_encode($data['reservationsHC'][$i]['TypeMat']); ?></td>
                                  <td><?php echo utf8_encode($data['reservationsHC'][$i]['NumSerie']); ?></td> 
                                  <td><?php echo utf8_encode($data['reservationsHC'][$i]['dateresa']); ?></td>
                                  <td><?php echo $data['reservationsHC'][$i]['date']; ?></td>
                                  <td><?php echo $data['reservationsHC'][$i]['heureD']; ?></td>
                                  <td><?php echo $data['reservationsHC'][$i]['heureF']; ?></td>
                                  <!-- RECUPERER DONNEES -->
                                  <?php
                                  $IdResHC = $data['reservationsHC'][$i]['IdResHC'];   
                                  ?>
                                  <td>
                                      <p>
                                          <a href="index.php?action=modifierReservationHC&id=<?php echo $IdResHC; ?>">
                                              <button type="button" class="btn btn-warning">Modifier</button>
                                          </a>
                                      </p>
                                  </td>
                                  <td>
                                      <p>
                                          <a href="index.php?action=supprimerReservationHC&id=<?php echo $IdResHC; ?>">
                                              <button type="button" class="btn btn-danger" >Supprimer</button>
                                          </a>
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