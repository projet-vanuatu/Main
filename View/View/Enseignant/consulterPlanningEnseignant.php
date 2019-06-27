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
            events: 'Ajax/Enseignant/planningPersonnel.php',
            editable:false,
            eventClick:function(event){    
                var id = event.id;
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
                            document.getElementById('Equip').innerHTML = rep;    
                        });         
                    }
                });    
            },
        });
    }); 
</script>
<br><br>
<div class="container-fluid">
    <div class="row">
        <div class='col-sm-2'>
              <div class="row" style="height:15%;"></div>
              <div class="well" id='Equip'>
                  <p>Matériel(s) :</p>
              </div>          
        </div>
        <div class="col-sm-10">  
            <div class="container-fluid">      
                <center><h2 style='font-weight:bold'>Mon Planning </h2></center>
                <hr>   
                <div id="calendar"><script></script></div>
            </div>
        </div>    
    </div>
</div>
