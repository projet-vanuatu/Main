<?php
@session_start();
require_once 'fonctionsUtiles.php';
$formation= RecupFormation();
$ens= RecupEns();
$Nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
if(!empty($_GET['idens'])){
    $idens = $_GET['idens'];
    $_SESSION['idens']=$idens;
}else{
    $idens= ""; 
}

if(!empty($_GET['idf'])){
    $idf=$_GET['idf'];
    $nummcm=RecupMCM($idf);
    $nummtd=RecupMTD($idf);
    $numgtd= RecupGroupeTD($idf);
    $numgcm= RecupGroupeCM($idf);
    $_SESSION['idf']=$idf;
}else{
    $idf="";
}

if(!empty($_SESSION['idf'])&&!empty($_SESSION['idens'])){
    $idfs = $_SESSION['idf'] ; 
    $idenss=$_SESSION['idens']; 
}else {
     $idfs="";
     $idenss= "";
}
$cspe=RecupCSPE();
?>
<!DOCTYPE html>
<html> 
    <head>
        <meta charset="UTF-8"> 
        <title>Jquery Fullcalandar Integration with PHP and Mysql</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <link rel="stylesheet" href="Public/Css/style.css" rel="stylesheet" type="text/css"/>
        <script src="Public/JavaScript/jquery.js"></script>
        <script src="Public/JavaScript/myJavaScript.js" type="text/javascript"></script>
        <script src="Public/JavaScript/fullCalendar.js"></script>
        <script>      
            $(document).ready(function(){
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
                events: 'load.php',
                selectable : true ,
                selectHelper:true,
                editable : true ,
                select: function(start, end, allDay ){
                    var form = document.getElementById('form').value;
                    var ens = document.getElementById('ens').value;
                    if (form == "" && ens == ""){
                        alert('Selectionnez une formation et un enseignant');
                    }else{
                        var start = $.fullCalendar.formatDate(start,"Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                        window.location.href ='formulaire.php?start='+start+'&end='+end;
                    }
                },
                eventResize:function(event){
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    var id = event.id;
                    $.ajax({
                        url:"update.php",
                        type:"POST",
                        data:{title:title, start:start, end:end, id:id},
                        success:function(){
                            calendar.fullCalendar('refetchEvents');
                            $.getJSON('http://etu-web2/~21401222/PhpProject1/update.php', function(data){
                                console.log(data);
                                alert(data.msg);
                            });      
                        }
                    })
                },
                eventDrop:function(event){
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var id = event.id;
                    $.ajax({
                        url:'update.php',
                        type:'POST',
                        data:{start:start, end:end, id:id },
                        success:function(){ 
                            calendar.fullCalendar('refetchEvents');
                            $.getJSON('http://etu-web2/~21401222/PhpProject1/update.php', function(data){
                                 alert(data.msg);
                            });      
                        }     
                    })
                },
                eventClick:function(event){
                    if(confirm("Etes-vous sur de vouloir supprimer cette séance ?")){
                        var id = event.id;
                        $.ajax({
                            url:"delete.php",
                            type:"POST",
                            data:{id:id},
                            success:function(){
                                calendar.fullCalendar('refetchEvents');
                                alert("Séance supprimée");
                            }
                        })
                    }
                },
                });
            });
        </script>
    </head>
    <body>
        <div class="myNavbar">
            <div class="navbar-brand" style="float:left;">             
                <img src="Public/Images/logo.JPG" style="width:52px;" alt=""/>           
            </div>
                <a href="index.php?action=index">Accueil</a>
                <a href="GestionPlanning.php">Gestion du planning</a>
                <a href="index.php?action=gererAffectationTD">Gestion groupes TD</a>
                <div class="subnav">
                    <button class="subnavbtn">Réservations &nbsp;<i class="fa fa-caret-down"></i></button>
                    <div class="subnav-content">
                        <a href="gestionReservation.php">Gérer réservations</a>
                        <a href="creationReserver.php">Créer réservation</a>
                        <a href="creationReserverHC.php">Créer réservation hors cours</a>
                    </div>
                </div>
                <div class="subnav">
                    <button class="subnavbtn">Consulter planning &nbsp;<i class="fa fa-caret-down"></i></button>
                    <div class="subnav-content">
                        <a href="#company">Par formation</a>
                        <a href="#company">Par salle</a>
                        <a href="#company">Par enseignant</a>
                    </div>
                </div>
                <div class="subnav2">
                    <a href = "index.php?action=deconnection" class="subnavbtn2">Deconnection&nbsp;<span class="glyphicon glyphicon-log-in"></span></a>
                </div>
                <div class="subnav2">
                    <button class="subnavbtn3"><span class="glyphicon glyphicon-user"></span>&nbsp<?php echo $Nom;?>&nbsp<?php echo $prenom?> </button>
                </div>
          </div> 
         <div class="row" style="height:5%;width:100%;"></div>   
        <div class="row" style="width:100%;">
            <div class="col-sm-1"></div>
            <div class="col-sm-2">        
               <div class=" well" style="height:28%;"></div>
               <div   class="jumbotron"class="well">
                    <center><label  for="sel1">Paramètres d'affichage :</label></center>            
                    <form action ='GestionPlanning.php'>
                        <label   for="sel1">Formation :</label>
                        <select class="form-control"  name="idf" onchange="verifForm();">
                            <option value ='' >Selectionnez une formation</option>
                            <?php             
                               for($i=0;$i<=count($formation)-1;$i++){
                            ?>
                               <Option <?php if($idfs == $formation[$i]['IdF'] ){echo "selected";}  ?> 
                                   value ="<?php echo $formation[$i]['IdF'] ?>"><?php echo $formation[$i]['IntituleF'] ?></option>     
                            <?php
                               }
                            ?>
                        </select>
                        <label for="sel1">Enseignant :</label>
                        <select class="form-control"  name="idens" onchange="verifEns();" >
                            <option value ='' >Selectionnez un enseignant</option>
                            <?php             
                                for($i=0;$i<=count($ens)-1;$i++){
                            ?>
                                <Option <?php if($idenss == $ens[$i]['IdENS']) {echo "selected";} ?> 
                                    value ="<?php echo $ens[$i]['IdENS'] ?>"><?php echo $ens[$i]['NomENS']." ".$ens[$i]['PrenomENS'] ?></option>     
                            <?php
                                }
                            ?>
                        </select> 
                        <input hidden value="<?php echo $idfs?>" id='form'>
                        <input hidden value="<?php echo $idenss ?>" id='ens'>
                        <br>
                        <center><input class="btn btn-primary" type ='submit'  value ='Valider'></center>
                    </form>          
               </div>       
            </div>
            <div class="col-sm-8">  
                <div class="container">      
                    <center><h2 style='font-weight:bold' >Gestion planning</h2></center>
                    <br>

                    <div id="calendar"><script></script></div>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </div>
    </body>
</html>

