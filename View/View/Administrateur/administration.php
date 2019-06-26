<br><br>
<div class="container">
    <!--Inserer étudiant CSV-->
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <h4><center>Insertion étudiant CSV</center></h4>
                <form action="index.php?action=creerMultipleEtudiant" method="POST" enctype="multipart/form-data">
                    <!-- Formation -->
                    <div class="form-group">
                        <label for="form">Sélectionner la formation :</label>
                        <select name="idForm" class="form-control" id="form" required>
                            <option value="">Choisir la formation</option>
                        <?php
                            for($i=0;$i<=count($data['formations'])-1;$i++){
                                $id = $data['formations'][$i]['IdF'];
                                $value = $data['formations'][$i]['IntituleF'];
                                echo "<option value='$id'>$value</option>";                        
                            }
                        ?>
                        </select>
                    </div>
                    <!-- Fichier -->
                    <label>Sélectionner vôtre fichier</label>
                    <input type="file" class="custom-file-input" name="file" required><br>
                    <!-- Valider -->
                    <input type="submit" class="btn btn-success btn-block" value="Valider">
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
    <br><hr>
    <!--Export-->
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <h4><center>Export des données</center></h4>
                <form action="index.php?action=export" method="POST">   
                    <div class="form-group"> 
                        <label for="tab">Choix de l'export :</label>
                        <select class="form-control" name="choixExport" required>
                            <option value="">Choisir un jeu de données</option>
                            <option value="etudiants">Etudiants</option>
                            <option value="enseignants">Enseignants</option>
                            <option value="formations">Formations</option>
                            <option value="materiels">Matériels</option>
                            <option value="seances">Séances</option>
                            <option value="salles">Salles</option>
                            <option value="sites">Sites</option>
                            <option value="reservations">Réservations</option>
                        </select>
                    </div>  
                    <input type="submit" class="btn btn-success btn-block" value="Valider"/>
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
    <!-- clean data base-->
    <br><br><hr>
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                    <h4><center>Nettoyage général : </center></h4>
                    <div class="alert alert-danger">
                        <strong>Attention !</strong> Utilisation en fin d'année uniquement !
                        <p>Cette fonctionnalité permet de réinitialiser la base de données</p>
                        <p>Suppréssion des éléménts suivants : </p>
                        <ul>
                            <li>Etudiants et mots de passe</li>
                            <li>Réservations</li>
                            <li>Les séances (emploi du temps)</li>
                        </ul>
                        <hr>
                        <a href="index.php?action=cleanUP">
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('Cela aura un impact important (pas de retour en arrière) sur la base de données ?');" value="Valider">Nettoyer</button>
                        </a>
                    </div>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</div>