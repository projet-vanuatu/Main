<?php

function index(){
    renderView();
}

/*
 * Groupe TD
 */

function gererAffectationTD($params = null){    
    modelLoader();
    $arrayFormations = getFormations();    
    isset($_POST['IdF']) ? $_SESSION['formation'] = $_POST['IdF'] : "";
    isset($_POST['IdF']) ? $_SESSION['td'] = "" : "";   
    if($_SESSION['formation'] != 0){     
        isset($_SESSION['formation']) ? $selectedFormation = $_SESSION['formation'] : $selectedFormation = "";
        isset($_SESSION['formation']) ? $arrayEtudiantNonAff = getEtudiantNonaffecté($_SESSION['formation']): $arrayEtudiantNonAff = array(); 
        $td = getGroupTD($_SESSION['formation']);
        !empty($td) ? $arrayGroupTD = arrayAssocToIndex($td, 'NumGroupTD', 5) : $arrayGroupTD = array(); 
        isset($_POST['IdGTD']) ? $_SESSION['td'] = $_POST['IdGTD'] : "";     
        if($_SESSION['td'] != 0){
            isset($_SESSION['td']) && !empty($_SESSION['td']) ? $selectedTD = $_SESSION['td'] : $selectedTD = ""; 
            isset($_SESSION['td']) ? $arrayEtudiantTD = getEtudiantGroupTD($_SESSION['td']) : $arrayEtudiantTD = array();            
        }       
        renderView('gererAffectationTD', array('formations' => $arrayFormations, 'selectedFormation' => $selectedFormation,
                    'nonAff' => $arrayEtudiantNonAff, 'td' => $td, 'selectedTD' => $selectedTD, 'aff' => $arrayEtudiantTD));        
    }else{
        renderView('gererAffectationTD', array('formations' => $arrayFormations, 'selectedFormation' => "",
                    'nonAff' => array(), 'td' => array(), 'selectedTD' => "", 'aff' => array()));            
    }
}

function affecterEtudiant($params){
    if(!empty($params['id']) && !empty($params['td'])){
        modelLoader();
        addEtudiantTD($params['id'], $params['td']);
        writeLog($_SESSION['id'], $_SESSION['nom'], $_SESSION['prenom'],
                'Affecter TD', $params['id'].' - '.$params['td']);
    }
    redirect(GEST, 'gererAffectationTD');
}

function desaffecterEtudiant($params){
    if(!empty($params['id'])){
        modelLoader();
        deleteEtduaintTD($params['id']);
    }
    redirect(GEST, 'gererAffectationTD');
}

/*
 * Réservations
 */

function gererReservation($params = null){
    modelLoader();
    $arrayReservations = RecupererReserver();
    $arrayReservationsHC = RecupererReserverHorscours();
    renderView('gererReservation', array('reservations' => $arrayReservations, 'reservationsHC' => $arrayReservationsHC,
        'nom'  => $_SESSION['nom'], 'prenom' => $_SESSION['prenom'], 'active' => $params));
}

function creerReservationHC(){
    modelLoader();
    if(!empty($_POST)){
        insertReservationHC($_POST);
        redirect(GEST, 'creerReservationHC');
    }
    $arrayEnseignants = RecupererEnseignant();
    renderView('creerReservationHC', array('enseignants' => $arrayEnseignants));
}

function modifierReservationHC($params = null){
    modelLoader();
    if(!empty($_POST)){
        updateReservationHC($_POST);
//        insertReservationHC($_POST);
        redirect(GEST, 'gererReservation', array("active" => 'hc'));
    }
    $formulaire = getReservationHC($params['id']);
    $arrayEnseignants = RecupererEnseignant();
//    supprimerReservationHC($params, 'modification');
    renderView('creerReservationHC', array('formulaire' => $formulaire,
        'enseignants' => $arrayEnseignants));
}

function supprimerReservationHC($params, $redirect = null){
    modelLoader();
    deleteReservationHC($params['id']);
//    if(is_null($redirect)){
       redirect(GEST, 'gererReservation', array("active" => 'hc')); 
//    }   
}

function creerReservation(){
    modelLoader();
    $arrayEnseignants = RecupererEnseignant();
    $arrayMateriels = RecupererMateriel();
    $arrayMatieres = RecupererMatieres();
    renderView('creerReservation', array('enseignants' => $arrayEnseignants, 'materiels' => $arrayMateriels, 'matieres' => $arrayMatieres));
}

function modifierReservation(){
    modelLoader();
    renderView('creerReservation');
}

function supprimerReservation(){
    modelLoader();
    renderView('creerReservation');
}