<?php
/*
 * Controller Administrateur
 */

function index(){
    renderView();
}

/*
 *  Formations
 */

function creerFormation(){
    if(!empty($_POST)){
        modelLoader();
        $result = setFormation($_POST);
        $idAction = getLastID('IdF', 'FORMATION');
    }       
    renderView('creerFormation');
}

function modifierFormation($data){
    modelLoader();
    if(!empty($_POST)){
        updateFormation($_POST);
        redirect(ADMIN, 'gererFormation');
    }
    $result = getFormation($data['idF']);
    renderView('modifierFormation', array($result)); 
}

function supprimerFormation($data){
    modelLoader();
    deleteFormation($data['idF']);
    redirect(ADMIN, 'gererFormation');
}

function ajouterTD(){
    modelLoader();
    addTD($_POST);
    redirect(ADMIN, 'modifierFormation', array( 'idF' => $_POST['idFormTD']));   
}

function supprimerTD($data){
    modelLoader();
    deleteTD($data['idT']);
    redirect(ADMIN, 'modifierFormation', array( 'idF' => $data['idF']));
}

function gererFormation(){
    modelLoader();
    $result = getFormations();
    renderView('gererFormation', $result);
}

/*
 *  Utilisateurs
 */

function creerUtilisateur($data = null){
    modelLoader(); 
    $arrayFormationS = getFormations2();
    $arrayDomaines = getDomaines();
    if(isset($data['activeParams'])){
        $pageActive = $data['activeParams'];
    }else{
        $pageActive = 'nothing';
    }
    if(!empty($_POST)){
        if($data['type'] == 'etudiant'){
            $done = InsererEtudiant($_POST['IdE'], $_POST['NomE'], $_POST['PrenomE'], $_POST['IdF'], $_POST['MdpE']);
            $pageActive = 'etudiants';
            if(!$done){
                renderView('creerUtilisateur', array('formations' => $arrayFormationS,
                        'domaines' => $arrayDomaines, 'active' => $pageActive, 'error' => 'Numéro d\'étudiant déjà utilisé', 'formulaire' => $_POST));
            }
        }elseif($data['type'] == 'enseignant'){
            $done = InsererEnseignant($_POST['IdEns'], $_POST['MdpEns'], $_POST['NomEns'],
                    $_POST['PrenomEns'], $_POST['TypeEns'], $_POST['IdDomaine']);
            $pageActive = 'enseignants';
            if(!$done){
                renderView('creerUtilisateur', array('formations' => $arrayFormationS,
                        'domaines' => $arrayDomaines, 'active' => $pageActive, 'error' => 'Numéro d\'enseignant déjà utilisé', 'formulaire' => $_POST));
            }
        }else if($data['type'] == 'admin'){
            $done = InsererAdminGest($_POST['IdA'], $_POST['NomA'], $_POST['PrenomA'],
                    $_POST['StatutA'], $_POST['MdpA']);
            $pageActive = 'gestionnaires';
            if(!$done){
                renderView('creerUtilisateur', array('formations' => $arrayFormationS,
                        'domaines' => $arrayDomaines, 'active' => $pageActive, 'error' => 'Numéro d\'administrateur et/ou gestionnare déjà utilisé', 'formulaire' => $_POST));
            }
        }
        redirect(ADMIN, 'creerUtilisateur', array('activeParams' => $pageActive));
    }
    renderView('creerUtilisateur', array('formations' => $arrayFormationS,
            'domaines' => $arrayDomaines, 'active' => $pageActive));    
}

function modifierUtilisateur($params){
    modelLoader();
    if(!empty($_POST)){
        if($params['type'] == 'etudiant'){
            $done = modifierEtudiant($_POST['MdpE'], $_POST['IdE'], $_POST['NomE'], $_POST['PrenomE'], $_POST['IdF'], $_POST['oldIdE']);
            $pageActive = 'etudiants';
            if(!$done){
                renderView('creerUtilisateur', array('formations' => $arrayFormationS, 'type' => $typeUtilisateur,
                        'domaines' => $arrayDomaines, 'active' => $pageActive, 'error' => 'Numéro d\'étudiant déjà utilisé', 'formulaire' => $_POST));
            }
        }elseif($params['type'] == 'enseignant'){
            modifierEnseignant($_POST['MdpEns'], $_POST['IdEns'], $_POST['NomEns'], $_POST['PrenomEns'], $_POST['TypeEns'], $_POST['IdDomaine']);
            $pageActive = 'enseignants';
        }else if($params['type'] == 'admin'){
            modifierAdmin($_POST['MdpA'], $_POST['IdA'], $_POST['NomA'], $_POST['PrenomA'], $_POST['StatutA']);
            $pageActive = 'gestionnaires';
        }
        redirect(ADMIN, 'gererUtilisateur', array('activeParams' => $pageActive));
    }else{
        $arrayFormationS = getFormations2();
        $arrayDomaines = getDomaines();
        if($params['type'] == 'etudiant'){
            $information = getEtudiant($params['id']);
            $pageActive = 'etudiants';
        }elseif($params['type'] == 'enseignant'){
            $information = getEnseignant($params['id']);
            $pageActive = 'enseignants';
        }else if($params['type'] == 'admin'){
            $information = getAdmin($params['id']);
            $pageActive = 'gestionnaires';
        }
    }
    renderView('creerUtilisateur', array('formulaire' => $information, 'active' => $pageActive, 'domaines' => $arrayDomaines,
        'formations' => $arrayFormationS));
}

function supprimerUtilisateur($params){
    modelLoader();
    if($params['type'] == 'etudiant'){
        supprimerEtudiant($params['idEtudiant'], $params['idMdp']);
        $pageActive = array('activeParams' => 'etudiants');
    }elseif($params['type'] == 'enseignant'){
        SupprimerEnseignant($params['idEnseignant'], $params['idMdp']);
        $pageActive = array('activeParams' => 'enseignants');
    }else if($params['type'] == 'admin'){
        supprimerAdmin($params['idAdmin'], $params['idMdp']);
        $pageActive = array('activeParams' => 'gestionnaires');
    }
    redirect(ADMIN, 'gererUtilisateur', $pageActive);
}

function gererUtilisateur($params = null){
    if(!is_null($params['activeParams'])){
        $pageActive = $params['activeParams'];
    }else{
        $pageActive = 'nothing';
    }
    modelLoader();
    $arrayFormation = getFormations2();
    $arrayEtudiants = getEtudiants();
    $arrayEnseignants = getEnseignants();
    $arrayAdmins = getAdmins();
    renderView('gererUtilisateur', array('formation' => $arrayFormation, 'etudiants' => $arrayEtudiants,
        'enseignants' => $arrayEnseignants, 'admins' => $arrayAdmins, 'active' => $pageActive));
}

/*
 *  Matières / UE
 */

function creerMatiere(){
    modelLoader();
    if(!empty($_POST)){
        insererMatiere($_POST);
        redirect(ADMIN, 'creerMatiere');        
    }
    $arrayDomaines = getDomaines();
    $arrayUEs = getUEs();
    renderView('creerMatiere', array('ues' => $arrayUEs, 'domaines' => $arrayDomaines));
}

function supprimerMatiere($params){
    modelLoader();
    deleteMatiere($params['id']);
    redirect(ADMIN, 'gererMatiereUE', array('activeParams' => 'matiere'));
}

function modifierMatiere($params){
    modelLoader();
    if(!empty($_POST)){
        updateMatiere($_POST);      
        redirect(ADMIN, 'gererMatiereUE', array('activeParams' => 'matiere'));
    }
    $informations = getMatiere($params['id']);
    $arrayDomaines = getDomaines();
    $arrayUEs = getUEs();
    renderView('creerMatiere', array('formulaire' => $informations, 'ues' => $arrayUEs,
        'domaines' => $arrayDomaines));
}

function creerUE(){
    modelLoader();
    if(!empty($_POST)){
        insererUE($_POST['intituleUE'], $_POST['formationUE']);
        redirect(ADMIN, 'creerUE');
    }
    $arrayFormations = getFormations2();
    renderView('creerUE', array('formations' => $arrayFormations));
}

function supprimerUE($params){
    modelLoader();
    deleteUE($params['id']);
    redirect(ADMIN, 'gererMatiereUE', array('activeParams' => 'ue'));    
}

function modifierUE($params){
    modelLoader();
    if(!empty($_POST)){
        updateUE($_POST);
        redirect(ADMIN, 'gererMatiereUE', array('activeParams' => 'ue'));
    }
    $informations = getUE($params['id']);
    $arrayFormations = getFormations2();
    renderView('creerUE', array('formulaire' => $informations, 'formations' => $arrayFormations));    
}

function gererMatiereUE($params){
    if(isset($params['activeParams'])){
        $active = $params['activeParams'];
    }else{
        $active = 'nothing';
    }
    modelLoader();
    $arrayMatieres = getMatieres();
    $arrayUE = getUEs();
    renderView('gererMatiereUE', array('matieres' => $arrayMatieres, 'ues' => $arrayUE, 'active' => $active));
}

/*
 *  Salles
 */

function creerSalle(){
    modelLoader();
    if(!empty($_POST)){
        insererSalle($_POST);
        redirect(ADMIN, 'creerSalle');
    }
    $arraySite = getSites();
    renderView('creerSalle', array('sites' => $arraySite));
}

function supprimerSalle($params){
    modelLoader();
    deleteSalle($params['id']);
    redirect(ADMIN, 'gererSalle');
}

function modifierSalle($params){
    modelLoader();
    if(!empty($_POST)){
        updateSalle($_POST);
        redirect(ADMIN, 'gererSalle');
    }
    $arraySite = getSites();
    $formulaire = getSalle($params['id']);
    renderView('creerSalle', array('sites' => $arraySite, 'formulaire' => $formulaire));    
}

function gererSalle(){
    modelLoader();
    $arraySalles = getSalles();
    renderView('gererSalle', array('salles' => $arraySalles));    
}

/*
 * Matériels
 */

function creerMateriel(){
    modelLoader();
    if(!empty($_POST)){
        insererMateriel($_POST);
        redirect(ADMIN, 'creerMateriel');
    }
    $arraySalle = getSalles();
    renderView('creerMateriel', array('salles' => $arraySalle));    
}

function supprimerMateriel($params){
    modelLoader();
    deleteMateriel($params['id']);
    redirect(ADMIN, 'gererMateriel', array('activeParams' => $params['activeParams']));  
}

function modifierMateriel($params){
    $redirection = $params['activeParams'];
    modelLoader();
    if(!empty($_POST)){
        updateMateriel($_POST);
        redirect(ADMIN, 'gererMateriel', array('activeParams' => $redirection));
    }
    if($params['activeParams'] == 'AF'){
        $formulaire = getMateriel($params['id']);  
    }else if($params['activeParams'] == 'NF'){
        $formulaire = getMaterielNonAff($params['id']);
    }   
    $arraySalle = getSalles();
    renderView('creerMateriel', array('salles' => $arraySalle, 'formulaire' => $formulaire, 'active' => $params['activeParams']));         
}

function gererMateriel($params = null){
    modelLoader();
    $arrayMaterielAff = getMaterielsAff();
    $arrayMaterielNonAff = getMaterielsNonAff();
    if(!is_null($params)){
        $page = $params['activeParams'];
    }else{
        $page = "";
    }
    renderView('gererMateriel', array('materielAff' => $arrayMaterielAff,
        'materielNonAff' => $arrayMaterielNonAff, 'active' => $page));   
}

/* Sites */

function creerSite(){
    if(!empty($_POST)){
       modelLoader();
       insertSite($_POST);
       redirect(ADMIN, 'creerSite');
    }
    renderView('creerSite');
}

function gererSite(){
    modelLoader();
    $arraySites = getSites();
    renderView('gererSite', array('sites' => $arraySites));    
}

function modifierSite($params){
    modelLoader();
    if(!empty($_POST)){
        updateSite($_POST);
        redirect(ADMIN, 'gererSite');
    }
    $arraySite = getSite($params['id']);
    renderView('creerSite', array('formulaire' => $arraySite)); 
    
}

function supprimerSite($params){
    modelLoader();
    deleteSite($params['id']);
    redirect(ADMIN, 'gererSite');      
}

/* Administration */

function administration(){
    modelLoader();
    $arrayFormation = getFormations2();
    renderView('administration', array('formations' => $arrayFormation));
}

function creerMultipleEtudiant(){
    modelLoader();
    $idFormation = $_POST['idForm'];
    $arrayCSV = getCSV($_FILES, $idFormation);
    insertEtudiantMultiple($arrayCSV, $idFormation);
    redirect(ADMIN, 'administration');
}

function export(){
    modelLoader();
    $export = "getExport" . ucfirst($_POST['choixExport']);
    call_user_func($export);
    redirect(ADMIN, 'administration');
}

function cleanUP(){
    modelLoader();
    cleanDataBase();
    redirect(ADMIN, 'administration');
}