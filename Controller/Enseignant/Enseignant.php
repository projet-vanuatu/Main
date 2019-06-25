<?php

function index(){
    renderView();
}

function consulterPlanningEnseignant(){
    renderView('consulterPlanningEnseignant');
}

function gererEtudiant(){
    $arrayFormations = getFormationsList();
    $arrayTD = array();
    $arrayListe = getEtudiantNonAffForm();
    $formSelected = "";
    $tdSelected = "";
    if(!empty($_POST)){
        $formSelected = "";
        $tdSelected = "";
        if($_POST['IdF'] != 0){
            $formSelected = $_POST['IdF'];
            $arrayListe = getEtudiantFormList($formSelected);
            $arrayTD = getGroupeTDList($formSelected);
        }         
        if($_POST['IdGTD'] != 0){
            $tdSelected = $_POST['IdGTD'];
            $arrayListe= array();
            $arrayListe = getEtudiantTDList($tdSelected);
        }
    }
    renderView('gererEtudiant',
                array(
                    'formations' => $arrayFormations,
                    'groupTD' => $arrayTD,
                    'listEtudiant' => $arrayListe,
                    'formSelected' => $formSelected,
                    'tdSelected' => $tdSelected
                )
    );
}
