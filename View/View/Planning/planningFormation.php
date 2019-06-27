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
            timeFormat: 'H:mm',
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
            events: 'Ajax/Planning/loadFormation.php',
            editable:false,    
        });
    });   
</script>
<br><br>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2" style="margin-top:250px;">
            <div class="well">
                <center><label  for="sel1">Paramètres d'affichage :</label></center>
                <form action='index.php?action=consulterPlanning' method="POST">
                    <input type="hidden" name="type" value="planningFormation">
                    <label for="sel1">Formations :</label>
                    <select class="form-control" name="criteria" required>
                        <option value="">Choisir une formation..</option>
                    <?php             
                    for($i=0;$i<=count($data['formulaire'])-1;$i++){
                    ?>
                        <Option <?php if($data['selected'] == $data['formulaire'][$i]['IdF']){echo "selected";}  ?> value ="<?php echo $data['formulaire'][$i]['IdF']; ?>">
                                <?php echo $data['formulaire'][$i]['IntituleF']; ?></option>
                    <?php
                    }
                    ?>
                    </select>        
                    <br>
                    <center><input class="btn btn-success btn-block" type ='submit' value ='Valider'></center>
                </form>
            </div>
        </div>
        <div class="col-sm-8">  
            <center><h2 style='font-weight:bold'>Planning des formations</h2></center>
            <hr>
            <div id="calendar"><script></script></div>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>
