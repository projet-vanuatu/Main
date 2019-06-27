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
            events: 'Ajax/Etudiant/loadEtudiant.php',
            editable:false,
        });
    });   
</script>
<br><br>
<div class="container">
    <h2 style="font-weight:bold;" class="text-center">Mon Planning</h2>
</div>
<hr>
<div class="container-fluid">
    <div id="calendar"><script></script></div> 
</div>
