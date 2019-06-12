//Formations
function afficherGroupeTD(){
    cacherGroupeTD();
    var nb = document.getElementById("nbTD").value;
    for (var i = 1; i <= nb; i++) {
        var div = 'con' + i;
        var elem = 'nbTD' + i;
        document.getElementById(elem).disabled  = false;
        document.getElementById(elem).required  = true;
        document.getElementById(div).style.display = "block";           
    }
}
function cacherGroupeTD(){
    for (var i = 1; i <= 6; i++) {
        var div = 'con' + i;
        var elem = 'nbTD' + i;
        document.getElementById(elem).disabled  = true;
        document.getElementById(elem).required  = false;
        document.getElementById(div).style.display = "none";
    }        
}

//Utilisateurs
function afficherEtudiant(){
    document.getElementById("etudiants").style.display = "block";
    document.getElementById("enseignants").style.display = "none";
    document.getElementById("admin").style.display = "none";

}
function afficherEnseignant(){
    document.getElementById("etudiants").style.display = "none";
    document.getElementById("enseignants").style.display = "block";
    document.getElementById("admin").style.display = "none";
}
function afficherGestionnaire(){
    document.getElementById("etudiants").style.display = "none";
    document.getElementById("enseignants").style.display = "none";
    document.getElementById("admin").style.display = "block";
}

//Matériel
function afficherMaterielEquipe(){
    document.getElementById("materielEquipe").style.display = "block";
    document.getElementById("materielNonEquipe").style.display = "none";
}
function afficherMaterielNonEquipe(){
    document.getElementById("materielEquipe").style.display = "none";
    document.getElementById("materielNonEquipe").style.display = "block";
}

//Matière
function afficherUE(){
    document.getElementById("UE").style.display = "block";
    document.getElementById("Matieres").style.display = "none";
}
function afficherMatieres(){
    document.getElementById("UE").style.display = "none";
    document.getElementById("Matieres").style.display = "block";
}

//affectation TD
function changeURL(origin, value){       
    if(origin == 'nonAff'){
        var idGroupTD = document.getElementById('idGroupTD').value;
        console.log('Nonaff :' + origin + ' ' + value + '' + idGroupTD);
        document.getElementById('addURL').href = "index.php?action=affecterEtudiant&id=" + value + "&td=" + idGroupTD;
    }else if(origin == 'groupTD'){
        console.log('aff :' + origin + ' ' + value + '');
        document.getElementById('removeURL').href = "index.php?action=desaffecterEtudiant&id=" + value;
    }
}
   
//Recherche
$(document).ready(function(){
$("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});
});

$(document).ready(function(){
$("#myInput2").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable2 tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});
});

$(document).ready(function(){
$("#myInput3").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable3 tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});
});

