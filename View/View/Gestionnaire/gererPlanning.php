<?php
if(isset($_SESSION['formationPlanning'])){
    $idfs = $_SESSION['formationPlanning'];
}else{
    $idfs = "";   
}
if(isset($_SESSION['enseignantPlanning'])){
    $idenss = $_SESSION['enseignantPlanning'];     
}else{
    $idenss = ""; 
}
?>
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
            editable:true,
            slotLabelFormat:"HH:mm",
            header:{
                left:'prev,next today',
                center:'title',
                right:" ",
            },
            events: 'Ajax/load.php',
            type:"POST",
            selectable : true ,
            selectHelper:true,
            editable : true ,
            select: function(start, end, allDay){
                var form = document.getElementById('form').value;
                var ens = document.getElementById('ens').value;
                console.log(form);
                if(form == "" && ens == ""){
                    alert('Selectionnez une formation et un enseignant');
                }else{                    
                    var start = $.fullCalendar.formatDate(start,"Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    window.location.href ='index.php?action=creerSeance&start='+start+'&end='+end;
                }
            },
            eventResize: function(event){
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url:"Ajax/update.php",
                    type:"POST",
                    data:{title:title, start:start, end:end, id:id},
                    success:function(){
                        calendar.fullCalendar('refetchEvents');
                        $.getJSON('./Ajax/update.php', function(data){
                            if(data.msg){
                                alert(data.msg);
                            }                           
                        });      
                    }
                })
            },
            eventDrop: function(event){
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var id = event.id;
                $.ajax({
                    url:'Ajax/update.php',
                    type:'POST',
                    data:{start:start, end:end, id:id},
                    success:function(){ 
                        calendar.fullCalendar('refetchEvents');
                        $.getJSON('./Ajax/update.php', function(data){
                            if(data.msg){
                                alert(data.msg);
                            } 
                        });      
                    }     
                })
            },
            eventClick: function(event){
                if(confirm("Etes-vous sur de vouloir supprimer cette séance ?")){
                    var id = event.id;
                    $.ajax({
                        url:"Ajax/delete.php",
                        type:"POST",
                        data:{id:id},
                        success:function(){
                            calendar.fullCalendar('refetchEvents');
                        }
                    })
                }
            },
        });
    });
</script>
<div class="container-fluid" style="margin-top: 50px;">
    <div class="row">
        <div class="col-sm-2">         
            <div class="well" style="margin-top: 200px;">
                <label for="sel1">Critères :</label>            
                <form  action ='index.php?action=gererPlanning' method='POST'>
                    <label   for="sel1">Formation :</label>
                    <select class="form-control"  name="idf" required>
                        <option value=''>Selectionnez une formation</option>
                        <?php             
                        for($i=0;$i<=count($data['formations'])-1;$i++){
                        ?>
                            <Option <?php if($idfs == $data['formations'][$i]['IdF'] ){echo "selected";} ?>
                                value ="<?php echo $data['formations'][$i]['IdF'] ?>"><?php echo $data['formations'][$i]['IntituleF'] ?></option>     
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <label for="sel1">Enseignant :</label>
                    <select class="form-control"  name="idens" required>
                        <option value=''>Selectionnez un enseignant</option>
                        <?php             
                        for($i=0;$i<=count($data['enseignants'])-1;$i++){
                        ?>
                            <Option <?php if($idenss == $data['enseignants'][$i]['IdENS']) {echo "selected";} ?> 
                                value ="<?php echo $data['enseignants'][$i]['IdENS'] ?>"><?php echo $data['enseignants'][$i]['NomENS']." ".$data['enseignants'][$i]['PrenomENS'] ?></option>     
                        <?php
                        }
                        ?>
                    </select> 
                    <input hidden value="<?php echo $idfs?>" id='form'>
                    <input hidden value="<?php echo $idenss ?>" id='ens'>
                    <br>
                    <input class="btn btn-success btn-block" type ='submit'  value ='Valider'>
                </form>          
            </div>      
        </div>
        <div class="col-sm-10">       
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <h2 style="font-weight:bold;">Gestion planning</h2>
                </div>
                <div class="col-sm-4"></div>               
            </div>
            <hr>
            <div id="calendar"><script></script></div>
        </div>
    </div>
</div>