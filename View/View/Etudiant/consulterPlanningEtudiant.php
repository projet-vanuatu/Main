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
            events: 'Ajax/Etudiant/loadEtudiant.php',
            editable:false,
        });
    });   
</script>
<br><br>
<div class="container">
    <div class="row" style="width:100%;">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"> 
            <h2 style='font-weight:bold'><center>Mon Planning</center></h2>
        </div>
        <div class="col-sm-4"></div> 
    </div>
    <hr>
    <div id="calendar"><script></script></div> 
</div>
