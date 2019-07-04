<?php

function index(){
    renderView();
}

/*
 * Groupe TD
 */

function gererAffectationTD($params = null){    
    modelLoader();
    $selectedTD = "";
    $arrayEtudiantTD = array();
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
        $numTD = getAttrTable('NumGroupTD', 'GROUPE_TD', 'IdGTD', $params['td']);
        $form = getFormationFromTD($params['td']);
        writeLog('Affécté TD : ', $params['id']." à ".$form." ".$numTD);
    }
    redirect(GEST, 'gererAffectationTD');
}

function desaffecterEtudiant($params){
    if(!empty($params['id']) && !empty($params['td'])){
        modelLoader();
        $numTD = getAttrTable('NumGroupTD', 'GROUPE_TD', 'IdGTD', $params['td']);
        $form = getFormationFromTD($params['td']);
        deleteEtduaintTD($params['id']);
        writeLog('Désaffécté TD : ', $params['id']." à ".$form." ".$numTD);
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
        //log
        $id = getLastID('IdResHC', 'RESERVERHORSCOURS');      
        $date = date("d/m/Y", strtotime($_POST['date']));
        $debut = date("H:i", strtotime($_POST['debut']));
        $fin = date("H:i", strtotime($_POST['fin']));
        $action = 'ID réservation : '.$id.' - Matériel : '.$_POST['IdMatHC'].' - Pour : '.$_POST['IdENSHC'].' - le : '.$date.' de '.$debut.' à '.$fin;
        writeLog('Créer une réservation hors séance : ', $action);
        //redirection
        redirect(GEST, 'creerReservationHC');
    }
    $arrayEnseignants = RecupererEnseignant();
    renderView('creerReservationHC', array('enseignants' => $arrayEnseignants));
}

function modifierReservationHC($params = null){
    modelLoader();
    if(!empty($_POST)){
        $id = $_POST['IdResHC'];      
        $date = date("d/m/Y", strtotime($_POST['date']));
        $debut = date("H:i", strtotime($_POST['debut']));
        $fin = date("H:i", strtotime($_POST['fin']));
        $action = 'ID réservation (original) : '.$id.' // Valeur après mise à jour // Matériel : '.$_POST['IdMatHC'].' - Pour : '.$_POST['IdENSHC'].' - le : '
                .$date.' de '.$debut.' à '.$fin;
        writeLog('Modifier une réservation hors séance : ', $action);
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
    $action = 'ID réservation : '.$params['id'];
    writeLog('Supprimer une réservation hors séance : ', $action);
    deleteReservationHC($params['id']);
    redirect(GEST, 'gererReservation', array("active" => 'hc'));  
}

function creerReservation($params = null){
    modelLoader();
    if(!empty($_POST)){
        insertReservation($_POST);
        $id = getLastID('IdRes', 'RESERVER');
        $db = dbConnect();
        $infoSeance = getSeanceInfoLog($db, $_POST['NumS']);
        if($infoSeance){          
            $date = date("d/m/Y", strtotime($infoSeance[0]['DateDebutSeance']));
            $debut = date("H:i", strtotime($infoSeance[0]['DateDebutSeance']));
            $fin = date("H:i", strtotime($infoSeance[0]['DateFinSeance']));           
        }else{
            $date = "";
            $debut = "";
            $fin = "";               
        }
        $action = 'ID réservation : '.$id.' - Enseignant : '.$_POST['IdENS'].' - Matériel : '.$_POST['IdMat'].' - Séannce du '.$date.' de '.$debut.' à '.$fin;
        writeLog('Créer une réservation séance : ', $action);
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
        $id = $_POST['IdRes'];
        $db = dbConnect();
        $infoSeance = getSeanceInfoLog($db, $_POST['NumS']);
        if($infoSeance){          
            $date = date("d/m/Y", strtotime($infoSeance['DateDebutSeance']));
            $debut = date("H:i", strtotime($infoSeance['DateDebutSeance']));
            $fin = date("H:i", strtotime($infoSeance['DateFinSeance']));           
        }else{
            $date = "";
            $debut = "";
            $fin = "";               
        }
        $mateirel = $_POST['IdMat'];
        $action = 'ID réservation (original) : '.$id.' // Valeur après mise à jour // Matériel : '.$_POST['IdMat'].' - Pour : '.$_POST['IdENS'].' - le : '
                .$date.' de '.$debut.' à '.$fin;
        writeLog('Modifier une réservation séance : ', $action);
        updateReservation($_POST);
        redirect(GEST, 'gererReservation');
    }
    $arrayReservation = getReservation($params['id']);
    $arrayEnseignants = RecupererEnseignant();
    renderView('creerReservation', array('enseignants' => $arrayEnseignants, 'formulaire' => $arrayReservation));
}

function supprimerReservation($params){
    modelLoader();
    $action = 'ID réservation : '.$params['id'];
    writeLog('Supprimer une réservation séance : ', $action);
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

function creerSeance($params = null){
    modelLoader();
    if(!empty($_POST)){
        if($_POST['Typec']=='CM' && $_POST['Typem']=='ST'){
            insertSeanceCM($_POST);
            $matiere = getAttrTable('IntituleM', 'MATIERES', 'NumM', $_POST['titleCM']);
            $td = "CM";
        }else if($_POST['Typec']=='CM' && $_POST['Typem']=='SP'){
            insertSeanceCMspe($_POST);
            $matiere = "Cours spécial";
            $td = "CM";
        }else if($_POST['Typec']=='TD' && $_POST['Typem']=='ST'){
            insertSeanceTD($_POST);
            $matiere = getAttrTable('IntituleM', 'MATIERES', 'NumM', $_POST['titleTD']);
            $td = getAttrTable('NumGroupTD', 'GROUPE_TD', 'IdGTD', $_POST['grptd']);
        }else if($_POST['Typec']=='TD' && $_POST['Typem']=='SP'){
            insertSeanceTDspe($_POST);
            $matiere = "Cours spécial";
            $td = getAttrTable('NumGroupTD', 'GROUPE_TD', 'IdGTD', $_POST['grptd']);
        } 
        $salle = getAttrTable('NomS', 'SALLE', 'IdS', $_POST['ids']);       
        $action = $_POST['nomForm']." - ".$td." ".$matiere." ".$_POST['nomEns']." le : ".date("d/m/Y", strtotime($_POST['start']))." de : "
                .date("H:i", strtotime($_POST['start']))." à ".date("H:i", strtotime($_POST['end']))." dans la salle : ".$salle;
        writeLog("Créer une séance : ", $action);
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
    if(!is_null($params)){
        $datedebut = $params['start'];
        $datefin = $params['end'];
        $Salle = horaireSalleDispo($datedebut, $datefin);       
    }else{
        $datedebut = "";
        $datefin = "";
        $Salle = "";
    }
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

/*
 * Gérer étudiant
 */

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