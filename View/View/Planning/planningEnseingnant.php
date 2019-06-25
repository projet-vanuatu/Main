<br><br>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2" style="margin-top:100px;">
            <div class="jumbotron">
                <center><label  for="sel1">Paramètres d'affichage :</label></center>
                <form action='index.php?action=consulterPlanning' method="POST">
                    <input type="hidden" name="type" value="planningEnseingnant">
                    <label for="sel1">Enseignant :</label>
                    <select class="form-control" name="criteria" required>
                        <option value="">Choisir un enseignant..</option>
                    <?php             
                    for($i=0;$i<=count($data['formulaire'])-1;$i++){
                    ?>
                        <Option <?php if($data['selected'] == $data['formulaire'][$i]['IdEns']){echo "selected";}  ?> value ="<?php echo $data['formulaire'][$i]['IdEns']; ?>">
                                <?php echo $data['formulaire'][$i]['PrenomEns']." ".$data['formulaire'][$i]['NomEns']; ?></option>
                    <?php
                    }
                    ?>
                    </select>        
                    <br>
                    <center><input class="btn btn-primary" type ='submit'  value ='Valider'></center>
                </form>
            </div>
        </div>
        <div class="col-sm-8">  
            <center><h2 style='font-weight:bold'>Planning des enseignants</h2></center>
            <br>
            <div id="calendar"><script></script></div>
        </div>
        <div class="col-sm-2" style="margin-top:100px;">
            <input type="hidden" id="statut" value="<?php echo isset($_SESSION['request']['controller']) ? $_SESSION['request']['controller'] : ""; ?>">
        <?php 
        if(isset($_SESSION['request']['controller']) && $_SESSION['request']['controller'] !== 'Etudiant'){
        ?>
            <div id="reserv" class="jumbotron">
                <center><label  for="sel1">Reservations :</label></center>
            </div>
        <?php 
        }
        ?>
        </div>
    </div>
</div>
<script>      
    $(document).ready(function() {       
        var calendar = $('#calendar').fullCalendar({ 
            height:735,
            titleFormat:'DD MMMM YYYY',
            columnFormat:'dddd DD/MM',
            monthNamesShort:['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Jui', 'Aout', 'Sep', 'Oct', 'Nov', 'Dec'],
            monthNames:['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
            dayNames:['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort:['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            buttonText: {
                prev: "Précédent",
                next: "Suivant",
                today: "Aujourd'hui",
                year: "Année",
                month: "Mois",
                day: "Jour",
                list: "Mon planning"
            },
            firstDay:1,
            hiddenDays:[0],
            minTime:"07:00:00",
            maxTime:"20:00:00",
            allDaySlot:false,
            defaultView:'agendaWeek', 
            slotLabelFormat:"HH:mm",
            header:{
                left:'prev,next today',
                center:'title',
                right:" ",
            },   
            events: 'Ajax/Planning/loadEnseignant.php',
            editable:false,   
            eventClick:function(event){
                var access = document.getElementById('statut').value;
                var id = event.id;
                if(access != 'Etudiant' && access != ''){
                    $.ajax({
                        url:"Ajax/Enseignant/equipementSalle.php",
                        type:"POST",
                        data:{id:id},
                        success:function(){
                            calendar.fullCalendar('refetchEvents');
                            $.getJSON('Ajax/Enseignant/equipementSalle.php', function(data){         
                                var rep = '<center><b> Affichage du matériel lié à la séance :</b><center><br><table style="border-spacing: 10px 2px; border-collapse: separate;">';   
                                $.each(data, function(i, obj) {      
                                    rep += '<tr>'+ obj.titre +'</tr><tr><td>' + obj.numSerie + ' </td><td>' + obj.TypeMat  + '</td><td>' + obj.Etat  + '</td></tr>' ;      
                                }),
                                rep += '</table>'   
                                document.getElementById('reserv').innerHTML = rep;     
                            });        
                        }
                    }) 
                } 
            },
        });
    });  
</script>