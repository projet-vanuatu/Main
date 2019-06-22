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
    if(isset($_SESSION['formation'])){
        if($_SESSION['formation'] != 0){
            isset($_SESSION['formation']) ? $selectedFormation = $_SESSION['formation'] : $selectedFormation = "";
            isset($_SESSION['formation']) ? $arrayEtudiantNonAff = getEtudiantNonaffecté($_SESSION['formation']): $arrayEtudiantNonAff = array(); 
            $td = getGroupTD($_SESSION['formation']);
            !empty($td) ? $arrayGroupTD = arrayAssocToIndex($td, 'NumGroupTD', 5) : $arrayGroupTD = array(); 
            isset($_POST['IdGTD']) ? $_SESSION['td'] = $_POST['IdGTD'] : "";     
            if(isset($_SESSION['td']) && $_SESSION['td'] != 0){
                isset($_SESSION['td']) && !empty($_SESSION['td']) ? $selectedTD = $_SESSION['td'] : $selectedTD = ""; 
                isset($_SESSION['td']) ? $arrayEtudiantTD = getEtudiantGroupTD($_SESSION['td']) : $arrayEtudiantTD = array();            
            }       
            renderView('gererAffectationTD', array('formations' => $arrayFormations, 'selectedFormation' => $selectedFormation,
                        'nonAff' => $arrayEtudiantNonAff, 'td' => $td, 'selectedTD' => $selectedTD, 'aff' => $arrayEtudiantTD));
        }
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
        redirect(GEST, 'gererReservation', array("active" => 'hc'));
    }
    $formulaire = getReservationHC($params['id']);
    $arrayEnseignants = RecupererEnseignant();
    renderView('creerReservationHC', array('formulaire' => $formulaire,
        'enseignants' => $arrayEnseignants));
}

function supprimerReservationHC($params, $redirect = null){
    modelLoader();
    deleteReservationHC($params['id']);
    redirect(GEST, 'gererReservation', array("active" => 'hc'));  
}

function creerReservation($params = null){
    modelLoader();
    if(!empty($_POST)){
        insertReservation($_POST);
        redirect(GEST, 'gererReservation');
    }
    $arrayEnseignants = RecupererEnseignant();
    if(is_null($params)){
        renderView('creerReservation', array('enseignants' => $arrayEnseignants));
    }else{
        $selection = array('idSeance' => $params['idSeance'], 'idEnseignant' => $params['idEnseignant']);
        renderView('creerReservation', array('enseignants' => $arrayEnseignants, 'preSelection' => $selection));
    }
    
}

function modifierReservation($params){
    modelLoader();
    if(!empty($_POST)){
        updateReservation($_POST);
        redirect(GEST, 'gererReservation');
    }
    $arrayReservation = getReservation($params['id']);
    $arrayEnseignants = RecupererEnseignant();
    renderView('creerReservation', array('enseignants' => $arrayEnseignants, 'formulaire' => $arrayReservation));
}

function supprimerReservation($params){
    modelLoader();
    deleteReservation($params['id']);
    redirect(GEST, 'gererReservation');
}

/*
 * Gestion EDT
 */

function gererPlanning(){
    modelLoader();
    if(!empty($_POST)){
        $_SESSION['formationPlanning'] = $_POST['idf'];
        $_SESSION['enseignantPlanning'] = $_POST['idens'];
    }
    $arrayEnseignants = RecupererEnseignant();
    $arrayFormation = getFormations();
    renderView('gererPlanning', array('enseignants' => $arrayEnseignants, 'formations' => $arrayFormation),
            'Gestion du planning', 'GestionnairePlanning');
}

function creerSeance($params){
    modelLoader();
    if(!empty($_POST)){
        if($_POST['Typec']=='CM' && $_POST['Typem']=='ST'){
            insertSeanceCM($_POST);
        }else if($_POST['Typec']=='CM' && $_POST['Typem']=='SP'){
            insertSeanceCMspe($_POST);
        }else if($_POST['Typec']=='TD' && $_POST['Typem']=='ST'){
            insertSeanceTD($_POST);
        }else if($_POST['Typec']=='TD' && $_POST['Typem']=='SP'){
            insertSeanceTDspe($_POST);
        }      
        if($_POST['Valider'] == 'Valider'){
            redirect(GEST, 'gererPlanning');
        }else{         
            $idSeance = getLastID('NumS', 'SEANCES');
            $idEnseignant = $_POST['IdENS'];
            redirect(GEST, 'creerReservation', array('idSeance' => $idSeance, 'idEnseignant' => $idEnseignant)); 
        }
    }   
    $idens = $_SESSION['enseignantPlanning'];
    $idf = $_SESSION['formationPlanning'];
    $arrayEnseignant = RecupNomEns($idens);
    $arrayFormation = RecupNomFormation($idf);
    $cspe = RecupCSPE();
    $nummcm = RecupMCM($idf);
    $nummtd = RecupMTD($idf);
    $numgtd = RecupGroupeTD($idf);
    $numgcm = RecupGroupeCM($idf);
    $datedebut = $params['start'];
    $datefin = $params['end'];
    $Salle = horaireSalleDispo($datedebut, $datefin);
    renderView(
        'formulairePlanning', 
        array(
            'idEnseignant' => $idens, 'idFormation' => $idf, 'coursSpe' => $cspe,
            'matiereCM' => $nummcm, 'matiereTD' => $nummtd, 'groupeCM' => $numgcm, 'groupeTD' => $numgtd, 
            'dateDebut' => $datedebut, 'dateFin' => $datefin, 'salles' => $Salle,
            'enseignants' => $arrayEnseignant, 'formations' => $arrayFormation
        ),
        'Formulaire création séance',
        'GestionnairePlanning'
    );
}