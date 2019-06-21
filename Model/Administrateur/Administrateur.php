<?php
/*
 *  Model Administrateur
 */

require_once FPUBLIC.DS.'Core/Manager.php';

/*
 *  Formations
 */

function getIdGcm($idFormation){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT IdGCM FROM GROUPE_CM WHERE IdF = $idFormation;");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res['IdGCM'];
}

function setFormation(){    
    $db = dbConnect();   
    //Formation
    $nomForm = $_POST['nomForm'];  
    $stmt = $db->prepare("INSERT INTO FORMATION (IntituleF) VALUES (:nomForm);");
    $stmt->bindParam(':nomForm', $nomForm);
    $stmt->execute();
    $stmt->closeCursor();
    $idForm = getLastID('IdF', 'FORMATION');  
    //Groupe CM
    $capaciteCM = $_POST['capaciteForm'];
    $NumGroupCM = 'Groupe CM';
    $stmt = $db->prepare("INSERT INTO GROUPE_CM (Capacite_max_GCM, NumGroupCM, IdF) VALUES (:capacite, :NumGroupeCM, :idForm);");
    $stmt->bindParam(':capacite', $capaciteCM);
    $stmt->bindParam(':NumGroupeCM', $NumGroupCM);
    $stmt->bindParam(':idForm', $idForm);
    $stmt->execute();
    $stmt->closeCursor();   
    //Groupe TDa
    $groupeTD = arrayAssocToIndex($_POST, 'nbTdForm');
    for($i=2;$i<=count($groupeTD);$i++){
        $stmt = $db->prepare("INSERT INTO GROUPE_TD (Capacite_max_GTD, NumGroupTD, IdF) VALUES (:capacite, :NumGroupeTD, :idForm);");
        $cmp = $i-1;
        $NumGroupTD = 'Groupe TD '.$cmp;
        $stmt->bindParam(':capacite', $groupeTD[$i]);
        $stmt->bindParam(':NumGroupeTD', $NumGroupTD);
        $stmt->bindParam(':idForm', $idForm);
        $stmt->execute(); 
        $stmt->closeCursor();
    }  
}

function getFormations(){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT F.IdF,F.IntituleF,CM.NumGroupCM,CM.IdGCM,CM.Capacite_max_GCM FROM FORMATION F,GROUPE_CM CM where F.IdF=CM.IdF;");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    for($i=0;$i<=count($res)-1;$i++){
        $id = $res[$i]['IdF'];
        $stmt = $db->prepare("SELECT IdGTD, Capacite_max_GTD, NumGroupTD FROM GROUPE_TD WHERE IdF = $id;");
        $stmt->execute();
        $groupeTD = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); 
        $res[$i]['TD'] = $groupeTD;
    }
    return $res;      
}

function getFormation($id){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT F.IdF,F.IntituleF,CM.NumGroupCM,CM.IdGCM,CM.Capacite_max_GCM FROM FORMATION F,GROUPE_CM CM where F.IdF=CM.IdF AND F.IdF = $id;");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    for($i=0;$i<=count($res)-1;$i++){
        $id = $res[$i]['IdF'];
        $stmt = $db->prepare("SELECT IdGTD, Capacite_max_GTD, NumGroupTD FROM GROUPE_TD WHERE IdF = $id;");
        $stmt->execute();
        $groupeTD = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); 
        $res[$i]['TD'] = $groupeTD;
    }
    return $res;
}

function updateFormation($data){
    //Update Formation
    $nomForm = $data['nomForm'];
    $idForm = $data['idForm'];
    $sql = "UPDATE FORMATION SET FORMATION.IntituleF = '$nomForm' WHERE FORMATION.IdF = $idForm;";
    updateTable($sql);   
    //Update CM
    $capaciteForm = $data['capaciteForm'];
    $sql = "UPDATE GROUPE_CM SET GROUPE_CM.Capacite_max_GCM= '$capaciteForm' WHERE GROUPE_CM.IdF = $idForm;";
    updateTable($sql);   
    //Update TD
    $arrayCapaciteTD = arrayAssocToIndex($data, 'nbTdForm');
    $arrayGroupID = getTD($idForm);
    $newArrayGroupID = rebuildArray($arrayGroupID, 'IdGTD');
    for($i=1;$i<=count($arrayCapaciteTD);$i++){
        $capaciteTD = $arrayCapaciteTD[$i];
        $idTD = $newArrayGroupID[$i];
        $sql = "UPDATE GROUPE_TD SET GROUPE_TD.Capacite_max_GTD = '$capaciteTD' WHERE GROUPE_TD.IdGTD = $idTD AND GROUPE_TD.IdF = $idForm;";
        updateTable($sql);        
    }
}

function updateTable($sql){
    $db = dbConnect();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();   
}

function getTD($idFormation){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT IdGTD FROM GROUPE_TD WHERE IdF = $idFormation ORDER BY IdGTD ASC;");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;
}

function deleteTD($id){
    $db = dbConnect();   
    //Désinscrire étudiant du groupe de td
    $sql = "DELETE FROM APPARTIENT WHERE IdGTD = $id;";
    $db->exec($sql);
    //suprimer les séances
    $sql = "UPDATE SEANCES SET IdGTD = NULL WHERE IdGTD = $id;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();   
    //Supprimer le td
    $sql = "DELETE FROM GROUPE_TD WHERE IdGTD = $id;";
    $db->exec($sql);    
}

function addTD($data){    
    $db = dbConnect();
  
    $id = $data['idFormTD'];
    $stmt = $db->prepare("SELECT COUNT(idGTD) as nbGroup FROM GROUPE_TD WHERE IdF = $id");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    $numGroup = $res['nbGroup']; 
    
    if($numGroup < 6){
        $numGroupe = $numGroup + 1;
        $nomGroupe = 'Groupe '.$numGroupe;
        $stmt = $db->prepare("INSERT INTO GROUPE_TD (Capacite_max_GTD, NumGroupTD, IdF) VALUES (:capacite, :numGroup, :idFormation)");       
        $stmt->bindParam(':capacite', intval($data['nbTDajout']));        
        $stmt->bindParam(':numGroup', $nomGroupe);       
        $stmt->bindParam(':idFormation', $id);        
        $stmt->execute();
        $stmt->closeCursor();        
    }
}

function deleteFormation($idFormation){
    $db = dbConnect();
    
    //désinscrire étudiants à formation et CM
    $sql = "UPDATE ETUDIANT SET IdGCM = NULL, IdF = NULL WHERE IdF = $idFormation";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();
    
    //suprimer les séances CM
    $idGCM = getIdGcm($idFormation);
    $sql = "UPDATE SEANCES SET IdGCM = NULL WHERE IdGCM = $idGCM;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();   
    
    //désinscrire étudiants aux groupes de td et supprimer séances
    $arrayGroupID = getTD($idFormation);
    $newArrayGroupID = rebuildArray($arrayGroupID, 'IdGTD');  
    foreach($newArrayGroupID as $value){
        retirerEtudiantTD($db, $value);
        supprimerSeancesTD($db, $value);
    }
    
    //déssafecter l'UE
    $sql = "UPDATE UNITE_ENSEIGNEMENT SET IdF = NULL WHERE IdF = $idFormation";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();
       
    //Supprimer la formation et les groupes appartenants
    $sql = "DELETE FROM GROUPE_TD WHERE IdF = $idFormation;";
    $db->exec($sql);
    $sql = "DELETE FROM GROUPE_CM WHERE IdF = $idFormation;";
    $db->exec($sql);  
    $sql = "DELETE FROM FORMATION WHERE IdF = $idFormation;";
    $db->exec($sql);  
}

function retirerEtudiantTD($db, $id){
    $sql = "DELETE FROM APPARTIENT WHERE IdGTD = $id";
    $db->exec($sql);    
}

function supprimerSeancesTD($db, $id){
    $sql = "UPDATE SEANCES SET IdGTD = NULL WHERE IdGTD = $id;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();         
}

/*
 *  Utilisateurs
 */

function getFormations2(){
    $conn = dbConnect();
    $sql = "SELECT IdF, IntituleF FROM FORMATION;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;
}

function getEtudiants(){    
    $conn = dbConnect();
    $sql = "SELECT e.IdE, e.PrenomE, e.NomE, e.IdMdp FROM ETUDIANT e WHERE e.IdF IS NULL;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    $sql = "SELECT e.IdE, e.PrenomE, e.NomE, f.IntituleF, e.IdMdp FROM ETUDIANT e, FORMATION f WHERE e.IdF = f.IdF AND e.IdE NOT IN(SELECT IdE FROM APPARTIENT)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    $result = array_merge($res, $res2);
    
    $sql = "SELECT e.IdE, e.PrenomE, e.NomE, f.IntituleF, e.IdMdp, g.NumGroupTD FROM ETUDIANT e, FORMATION f, GROUPE_TD g, APPARTIENT a "
        . "WHERE e.IdF = f.IdF AND e.IdE = a.IdE AND a.IdGTD = g.IdGTD;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $result2 = array_merge($result, $res3);
        
    return $result2;
}

function getEnseignants(){   
    $conn = dbConnect();
    $sql = "SELECT e.IdEns, e.NomEns, e.PrenomEns, e.TypeEns, c.Mdp, c.IdMdp FROM ENSEIGNANT e, CODES c WHERE e.IdMdp = c.IdMdp;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;
}

function getAdmins(){    
    $conn = dbConnect();
    $sql = "SELECT * FROM ADMINISTRATION";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;
}

function getDomaines(){
    $conn = dbConnect();
    $sql = "SELECT IdDomaine, Intitule_domaine FROM DOMAINE;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function getEnseignant($IdEns){
    $conn = dbConnect();
    $sql = "SELECT e.IdEns, e.NomEns, e.PrenomEns, e.TypeEns, c.Mdp as MdpEns, d.Intitule_domaine, d.IdDomaine "
            . "FROM ENSEIGNANT e, CODES c, DOMAINE d, SPECIALISER s "
            . "WHERE e.IdMdp = c.IdMdp AND d.IdDomaine = s.IdDomaine "
            . "AND e.IdEns = s.IdEns AND e.IdEns = '$IdEns';";
   $stmt = $conn->prepare($sql); 
   $stmt->execute();
   $res = $stmt->fetch(PDO::FETCH_ASSOC);
   return $res;
}

function getEtudiant($IdE){
     $conn = dbConnect();
     $sql = "SELECT e.IdE, e.NomE , e.PrenomE, e.IdF , c.Mdp as MdpE FROM ETUDIANT e , CODES c WHERE e.IdMdp=c.IdMdp AND e.IdE ='$IdE'";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res;
}

function getAdmin ($IdA){
    $conn = dbConnect();
    $sql = "SELECT a.IdA, a.NomA , a.PrenomA, a.StatutA , c.Mdp as MdpA FROM ADMINISTRATION a , CODES c WHERE a.IdMdp=c.IdMdp AND a.IdA ='$IdA'";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res;
}

function getMdp($attr, $table, $criteria, $value){
    $conn = dbConnect();
    $sql = "SELECT $attr FROM $table WHERE $criteria = $value;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res[$attr];    
}

//  --- Insertion etudiant --- //
function InsererEtudiant($IdE,$NomE,$PrenomE,$IdF,$MdpE) {
    $exist = getEtudiant($IdE);
    if(!$exist){
        $cx = bdConnect();
        $InsererMdp ="INSERT INTO CODES (IdMdp, Mdp) VALUES (NULL, '$MdpE');";
        $queryInsererMdp = mysqli_query($cx,$InsererMdp);
        if($queryInsererMdp){ 
            $IdMdpE = getLastID('IdMdp', 'CODES');
            if($IdF != ""){
                $IdGCM = getIdGcm($IdF);
                $InsererEtudiant = "INSERT INTO ETUDIANT (IdE ,NomE, PrenomE,IdGCM,IdF,IdMdp) values ('$IdE','$NomE','$PrenomE','$IdGCM','$IdF','$IdMdpE');";            
            }else{
                $InsererEtudiant = "INSERT INTO ETUDIANT (IdE ,NomE, PrenomE,IdGCM,IdF,IdMdp) values ('$IdE','$NomE','$PrenomE', NULL , NULL ,'$IdMdpE');";
            }
            mysqli_query($cx,$InsererEtudiant);
        }
        return true;
    }else{
        return false;
    }

}

// --- Insertion enseignant --- //
function InsererEnseignant($IdEns,$MdpEns,$NomEns,$PrenomEns,$TypeEns,$IdDomaine) {
    $exist = getEnseignant($IdEns);
    if(!$exist){
        $cx = bdConnect();
        $InsererMdp ="INSERT INTO CODES (IdMdp, Mdp) VALUES (NULL, '$MdpEns');";
        $queryInsererMdp = mysqli_query($cx,$InsererMdp);
        if($queryInsererMdp){
            $IdMdpEns = getLastID('IdMdp', 'CODES');
            $InsererEnseignant = "INSERT INTO ENSEIGNANT (IdENS , PrenomENS,NomENS,TypeENS,IdMdp) values ('$IdEns','$PrenomEns','$NomEns','$TypeEns','$IdMdpEns')";
            $queryInsererEnseignant = mysqli_query($cx,$InsererEnseignant);
            if($queryInsererEnseignant){
                $InsererDomaine ="INSERT INTO SPECIALISER (IdENS,IdDomaine) values ('$IdEns','$IdDomaine')";
                $queryInsererDomaine = mysqli_query($cx,$InsererDomaine);
            }
        }
        return true;
    }
    return false;
}

// --- Insertion admin --- //
function InsererAdminGest($IdA,$NomA,$PrenomA,$StatutA,$MdpA){
    $exist = getAdmin($IdA);
    if(!$exist){
        $cx = bdConnect();
        $InsererMdp ="INSERT INTO CODES (IdMdp, Mdp) VALUES (NULL, '$MdpA');";
        $queryInsererMdp = mysqli_query($cx,$InsererMdp);
        if($queryInsererMdp){   
            $IdMdpA = getLastID('IdMdp', 'CODES');
            $InsererAdmin = "INSERT INTO ADMINISTRATION (IdA ,NomA, PrenomA,StatutA,IdMdp) values ('$IdA','$NomA','$PrenomA','$StatutA','$IdMdpA')";
            $queryInsererAdmin = mysqli_query($cx,$InsererAdmin); 
        }
        return true;
    }
    return false;
}

// --- Supprimer Etudiant --- //
function supprimerEtudiant($IdESupp,$IdMdp){
    $cx = bdConnect();
    $supprimerEtudiantTD = "DELETE FROM APPARTIENT WHERE IdE = $IdESupp;";
    mysqli_query($cx,$supprimerEtudiantTD);
    $supprimerEtudiant = "DELETE FROM ETUDIANT WHERE IdE ='$IdESupp'";
    mysqli_query($cx,$supprimerEtudiant);
    $supprimerMdp = "DELETE FROM CODES WHERE IdMdp = '$IdMdp'";
    mysqli_query($cx,$supprimerMdp); 
}

// --- Supprimer Enseignant --- //
function SupprimerEnseignant($IdESupp,$IdMdp){
    $cx = bdConnect();  
    $supprimerDispense = "DELETE FROM DISPENSE WHERE IdEns = $IdESupp;";
    mysqli_query($cx,$supprimerDispense);
    
    $supprimerReserver = "DELETE FROM RESERVER WHERE IdEns ='$IdESupp'";
    mysqli_query($cx,$supprimerReserver);
    
    $supprimerReserverHC = "DELETE FROM RESERVERHORSCOURS WHERE IdEns ='$IdESupp'";
    mysqli_query($cx,$supprimerReserverHC);
    
    $supprimerEnseigne = "DELETE FROM ENSEIGNE WHERE IdEns ='$IdESupp'";
    mysqli_query($cx,$supprimerEnseigne);
    
    $supprimerSpecialiser = "DELETE FROM SPECIALISER WHERE IdEns ='$IdESupp'";
    $querysupprimerSpecialiser = mysqli_query($cx,$supprimerSpecialiser);
    
    $supprimerEnseignant = "DELETE FROM ENSEIGNANT WHERE IdEns ='$IdESupp'";
    $querysupprimerEnseignant = mysqli_query($cx,$supprimerEnseignant);
    $supprimerMdp = "DELETE FROM CODES WHERE IdMdp = '$IdMdp'";
    $querysupprimerMdp = mysqli_query($cx,$supprimerMdp);
}

// --- Supprimer Admin --- //
function supprimerAdmin($IdESupp,$IdMdp){
    $cx = bdConnect();
    $supprimerReserver = "DELETE FROM RESERVER WHERE IdEns ='$IdESupp'";
    mysqli_query($cx,$supprimerReserver);

    $supprimerReserverHC = "DELETE FROM RESERVERHORSCOURS WHERE IdEns ='$IdESupp'";
    mysqli_query($cx,$supprimerReserverHC);
    $supprimerAdmin = "DELETE FROM ADMINISTRATION WHERE IdA ='$IdESupp'";
    $querysupprimerAdmin = mysqli_query($cx,$supprimerAdmin);
    $supprimerMdp = "DELETE FROM CODES WHERE IdMdp = '$IdMdp'";
    $querysupprimerMdp = mysqli_query($cx,$supprimerMdp); 
}

// --- Modifier Enseignant --- //
function getMdpEnseignant($IdEns){
    $cx = bdConnect();
    $Mdp = "SELECT IdMdp FROM ENSEIGNANT WHERE IdENS ='$IdEns'";
    $queryMdp = mysqli_query($cx,$Mdp);
    $res= mysqli_fetch_array($queryMdp);
    return $res['IdMdp'];
}

function modifierEnseignant($MdpEns,$IdEns,$NomEns,$PrenomEns,$TypeEns,$IdDomaine){
    $cx = bdConnect();
    $ModifierEns = "UPDATE ENSEIGNANT SET NomENS ='$NomEns' , PrenomENS = '$PrenomEns' , TypeENS = '$TypeEns' WHERE IdENS ='$IdEns';";
    mysqli_query($cx,$ModifierEns);

    $IdMdpEns = getMdpEnseignant($IdEns);
    $ModifierMdp = "UPDATE CODES SET Mdp ='$MdpEns' WHERE IdMdp ='$IdMdpEns';";
    mysqli_query($cx,$ModifierMdp);
    
    $ModifierDomaine = "UPDATE SPECIALISER SET IdDomaine ='$IdDomaine' WHERE IdENS ='$IdEns';";
    mysqli_query($cx,$ModifierDomaine);
}

// --- modifier etudiant --- //
function getMdpEtudiant($IdE){
    $cx = bdConnect();
    $Mdp = "SELECT IdMdp FROM ETUDIANT WHERE IdE ='$IdE'";
    $queryMdp = mysqli_query($cx,$Mdp);
    $res= mysqli_fetch_array($queryMdp);
    return $res['IdMdp'] ;
}

function modifierEtudiant($MdpE,$IdE,$NomE,$PrenomE,$IdF,$oldIdE){
    $exist = getEtudiant($IdE);
    if(!$exist){
        $cx = bdConnect();   
        $groupeTD = getEtudiantTD($IdE);
        if(!is_null($groupeTD)){
            //Suppréssion du groupe de td
            $deleteEtudiantTD = "DELETE FROM APPARTIENT WHERE IdE = $IdE;";
            mysqli_query($cx,$deleteEtudiantTD);          
        }
        //Update de l'étudiant
        $ModifierE = "UPDATE ETUDIANT SET IdE = $IdE, NomE ='$NomE' , PrenomE= '$PrenomE' , IdF = $IdF WHERE IdE = $oldIdE;";
        mysqli_query($cx,$ModifierE);
        if(!is_null($groupeTD)){
            //Réaffectation
            $affectation = "INSERT INTO APPARTIENT (IdGTD, IdE) VALUES ($groupeTD, $IdE);";
            mysqli_query($cx,$affectation);       
        }
        //Mise a jour du mot de passe
        $IdMdpE = getMdpEtudiant($IdE);
        $ModifierMdp = "UPDATE CODES SET Mdp ='$MdpE' WHERE IdMdp ='$IdMdpE';";
        mysqli_query($cx,$ModifierMdp);
        return true;
    }
    return false;
}

function getEtudiantTD($IdE){
    $conn = dbConnect();
    $sql = "SELECT IdGTD FROM APPARTIENT WHERE IdE = $IdE;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['IdGTD'];    
}

// --- modifier administrateur --- //
function getMdpAdmin($IdA){
    $cx = bdConnect();
    $Mdp = "SELECT IdMdp FROM ADMINISTRATION WHERE IdA ='$IdA'";
    $queryMdp = mysqli_query($cx,$Mdp);
    $res= mysqli_fetch_array($queryMdp);
    return $res['IdMdp'] ;
}

function modifierAdmin($MdpA,$IdA,$NomA,$PrenomA,$StatuA){
    $cx = bdConnect();
    $ModifierA = "UPDATE ADMINISTRATION SET NomA ='$NomA', PrenomA= '$PrenomA', StatutA = '$StatuA' WHERE IdA = $IdA;";
    mysqli_query($cx,$ModifierA);

    $IdMdpA = getMdpAdmin($IdA);
    $ModifierMdp = "UPDATE CODES SET Mdp = '$MdpA' WHERE IdMdp = $IdMdpA;";
    mysqli_query($cx,$ModifierMdp);
}

/*
 *  Matuères / UE
 */

function getMatieres(){
    $conn = dbConnect();
    $sql = "SELECT m.NumM, m.IntituleM, m.TypeM, m.NbHeuresFixees, m.IdUE, m.IdDomaine, u.IntituleUE FROM MATIERES m, UNITE_ENSEIGNEMENT u WHERE m.IdUE = u.IdUE;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function getMatiere($id){
    $conn = dbConnect();
    $sql = "SELECT m.NumM, m.IntituleM, m.TypeM, m.NbHeuresFixees, m.IdUE, m.IdDomaine, u.IntituleUE FROM MATIERES m, UNITE_ENSEIGNEMENT u"
            . " WHERE m.IdUE = u.IdUE AND m.NumM = $id;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    //on regarde si il y a un td  
    if($res['TypeM'] == 'CM'){
        $idTD = getMatiereTD($res['IntituleM']);
        if($idTD){
            $stmt = $conn->prepare("SELECT NbHeuresFixees FROM MATIERES WHERE NumM = $idTD;");
            $stmt->execute();
            $groupeTD = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor(); 
            $res['nbHeuresMTD'] = $groupeTD['NbHeuresFixees'];                
        }
    }else if($res['TypeM'] == 'TD'){
        $res['nbHeuresMTD'] = $res['NbHeuresFixees'];
        unset($res['NbHeuresFixees']);
    }
    return $res;    
}

function getUEs(){
    $db = dbConnect();
    $sql="SELECT u.IdUE, u.IntituleUE, f.IntituleF, f.IdF FROM UNITE_ENSEIGNEMENT u, FORMATION f WHERE u.IdF=f.IdF";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    return $result;
}

function getUE($id){
    $db = dbConnect();
    $sql="SELECT u.IdUE, u.IntituleUE, f.IntituleF, f.IdF FROM UNITE_ENSEIGNEMENT u, FORMATION f"
            . " WHERE u.IdF=f.IdF AND u.IdUE = $id";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    return $result[0];   
}

function insererUE($nomUE, $resIdF){
    $cx = bdConnect();
    $InsererUE = "INSERT INTO UNITE_ENSEIGNEMENT(IntituleUE, IdF) VALUES ('$nomUE', $resIdF);";
    $queryInsererUE = mysqli_query($cx,$InsererUE);
    if($queryInsererUE){
        return true;
    }
    return false;
}

function updateUE($data){
    $idue = $data['idue'];
    $intue = $data['intituleUE'];
    $idf = $data['formationUE'];
    $cx = bdConnect();
    $updateUE = "UPDATE UNITE_ENSEIGNEMENT SET IntituleUE = '$intue',IdF = '$idf' WHERE IdUE=$idue";
    mysqli_query($cx,$updateUE);  
}

function deleteUE($IdUE){
    $cx = bdConnect();
    $arrayMatiere = getMatiereUE($IdUE);
    
    for($i=0;$i<=count($arrayMatiere)-1;$i++){
        $idMatiere = $arrayMatiere[$i]['NumM'];
        deleteMatiere($idMatiere);
    }
    $sql6="DELETE FROM UNITE_ENSEIGNEMENT WHERE IdUE=$IdUE";
    $query6=mysqli_query($cx,$sql6);  
}

function getMatiereUE($idue){
    $db = dbConnect();
    $sql="SELECT NumM FROM MATIERES WHERE IdUE=$idue";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function insererMatiere($post){
    $cx = bdConnect();
    $nomMat = $post['intituleM'];
    $DomaineM = $post['choixDomaine'];
    $UEM = $post['choixUE'];
    $typeM = $post['choixTypeMatiere'];
    $nbHeuresM = $post['nbHeuresM'];
    $InsererM = "INSERT INTO MATIERES(IntituleM, TypeM, NbHeuresFixees, IdUE, IdDomaine) "
        . "VALUES ('$nomMat', '$typeM', '$nbHeuresM', '$UEM', '$DomaineM')";
    mysqli_query($cx,$InsererM);
    if(isset($post['nbHeuresMTD'])){
        $nomMat = $post['intituleM'];
        $nbHeureTD = $post['nbHeuresMTD'];
        $InsererTD = "INSERT INTO MATIERES(IntituleM, TypeM, NbHeuresFixees, IdUE, IdDomaine) "
            . "VALUES ('$nomMat', 'TD', '$nbHeureTD', '$UEM', '$DomaineM')";
        mysqli_query($cx,$InsererTD);
    }   
}

function updateMatiere($data){
    $cx = bdConnect();
    $intM = $data['intituleM'];
    $typeM = $data['choixTypeMatiere'];
    $nbheure = $data['nbHeuresM'];
    $idue = $data['choixUE'];
    $iddom = $data['choixDomaine'];
    $idm = $data['NumM'];
    $updateM = "UPDATE MATIERES SET IntituleM = '$intM',TypeM = '$typeM', NbHeuresFixees = '$nbheure',IdUE = '$idue', IdDomaine = '$iddom' WHERE NumM=$idm";
    mysqli_query($cx,$updateM);
    //Si c'est un CM on regarde si il y à un TD à mettre à jour
    if($typeM == 'CM'){
        $numTD = getMatiereTD($intM);
        //si il y à un TD on le met à jour
        if($numTD){
           $updateTD = "UPDATE MATIERES SET IntituleM = '$intM',TypeM = '$typeM', NbHeuresFixees = '$nbheure',IdUE = '$idue', IdDomaine = '$iddom' WHERE NumM=$numTD";
            mysqli_query($cx,$updateTD);
        }
    }   
}

function updateMatiereTD($data){
    $cx = bdConnect();
    $nbheure = $data['nbHeuresMTD'];
    $idm = $data['NumM'];
    $updateM = "UPDATE MATIERES SET NbHeuresFixees = '$nbheure' WHERE NumM = $idm;";
    mysqli_query($cx,$updateM);
}

function getMatiereTD($nomCM){
    $db = dbConnect();
    $sql="SELECT NumM FROM MATIERES WHERE IntituleM = '$nomCM' AND TypeM = 'TD';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['NumM'];    
}

function deleteMatiere($NumM){
    $cx= bdConnect();
    $arrayNumeroSeance = getSeance($NumM);
    
    for($i=0;$i<=count($arrayNumeroSeance)-1;$i++){
        $NumS = $arrayNumeroSeance[$i]['NumS'];
        $sql1="DELETE FROM RESERVER WHERE NumS=$NumS";
        $query1=mysqli_query($cx,$sql1);
        $sql2="DELETE FROM DISPENSE WHERE NumS=$NumS";
        $query2=mysqli_query($cx,$sql2);
        $sql3="DELETE FROM SEANCES WHERE NumS=$NumS";
        $query3=mysqli_query($cx,$sql3);        
    }
    
    $sql4="DELETE FROM ENSEIGNE WHERE NumM=$NumM";
    $query4=mysqli_query($cx,$sql4);
    $sql5="DELETE FROM MATIERES WHERE NumM=$NumM";
    $query5=mysqli_query($cx,$sql5);    
}

function getSeance($NumM){   
    $db = dbConnect();
    $sql="SELECT NumS FROM SEANCES WHERE NumM= $NumM;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/*
 * Salles
 */

function getSalles(){
    $db = dbConnect();
    $sql="SELECT IdS, NomS, CapaciteS, TypeS, SA.IdSITE,NomSITE FROM SALLE SA,SITE SI WHERE SA.IdSITE = SI.IdSITE";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    return $result;    
}

function getSalle($id){
    $conn = dbConnect();
    $sql = "SELECT  IdS, NomS, CapaciteS, TypeS, SA.IdSITE,NomSITE FROM SALLE SA,SITE SI "
            . "WHERE SA.IdSITE = SI.IdSITE AND  SA.IdS ='$id'";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res[0];
}

function getSites(){
    $conn = dbConnect();
    $sql = "SELECT IdSITE, NomSITE, NumRue, RueSite, Ville, BoitePostale FROM SITE";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resSite = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resSite;
}

function insererSalle($args){
    $cx = bdConnect();
    $site = $args['choixSite'];
    $typeSalle = $args['choixTypeSalle'];
    $capaciteSalle = $args['capaciteMaxSalle'];
    $nomSalle = $args['nomSalle'];
    $InsertSalle = "INSERT INTO SALLE(NomS, CapaciteS, TypeS, IdSITE) VALUES ('$nomSalle', '$capaciteSalle', '$typeSalle', '$site');";
    $queryInsererSalle = mysqli_query($cx, $InsertSalle);
}

function deleteSalle($IdS){
    $cx = bdConnect();
    $RecupererMateriel="SELECT IdMat FROM MATERIELS WHERE IdS = '$IdS';";
    $queryrecupererMateriel = mysqli_query($cx,$RecupererMateriel);
    $resIdMat = mysqli_fetch_array($queryrecupererMateriel);
    $IdMat = $resIdMat['IdMat'];    
    //On met à jour les matériels qui sont équipé dans la salle à supprimer 
    $supprimerMateriel = "UPDATE MATERIELS SET IdS = NULL WHERE IdMat ='$IdMat';";
    $querysupprimerMateriel = mysqli_query($cx,$supprimerMateriel);    
    // On récupère les numéros de séance qui se déroulent dans la salle que l'on souhaite supprimer
    $conn = dbConnect();
    $sql = "SELECT NumS FROM SEANCES WHERE IdS = '$IdS';";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resSalle = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Pour chaque numéro de sééance on surpprime les réservations associées et les profs qui dispensnet ccette séance 
    for($i=0;$i<=count($resSalle)-1;$i++){            
      $a = $resSalle[$i]['NumS'];
      $supprimerDispense= "DELETE FROM DISPENSE WHERE NumS = '$a'";
      $querysupprimerDispense = mysqli_query($cx,$supprimerDispense);

      $supprimerReserver ="DELETE FROM RESERVER WHERE NumS ='$a'";
      $querysupprimerReserver = mysqli_query($cx,$supprimerReserver);

      $supprimerSeance = "DELETE FROM SEANCES WHERE NumS ='$a'";
      $querysupprimerSeance = mysqli_query($cx,$supprimerSeance);                
    }     
    // On supprime la salle 
    $supprimerSalle = "DELETE FROM SALLE WHERE IdS= $IdS;";
    $querysupprimerSalle = mysqli_query($cx,$supprimerSalle);    
}

function updateSalle($args){
    $cx = bdConnect();    
    $IdSITE = $args['choixSite'];
    $typeSalle = $args['choixTypeSalle'];
    $capaciteSalle = $args['capaciteMaxSalle'];
    $nomSalle = $args['nomSalle'];
    $id = $args['IdS'];
    $sql ="UPDATE SALLE SET NomS='$nomSalle',CapaciteS= '$capaciteSalle', typeS='$typeSalle', IdSITE='$IdSITE' WHERE IdS= $id;";
    mysqli_query($cx,$sql);  
}

/*
 * Matériels
 */

function insererMateriel($args){
    $cx = bdConnect();
    $Type = $args['Type'];
    $Etat = $args['Etat'];
    $IdS = $args['Salle'];
    $IdS = $args['Salle']; 
    $serie = $args['numSerie'];
    $Materiel="INSERT INTO MATERIELS (IdMat, numSerie, TypeMat,Etat_fonctionnement,IdS) value (NULL, '$serie', '$Type', '$Etat', $IdS);";
    $queryMateriel = mysqli_query($cx,$Materiel);    
}

function getMaterielsAff(){
     $conn = dbConnect();
     $sql = "SELECT s.NomS , m.numSerie, si.NomSITE , m.TypeMat ,m.Etat_fonctionnement ,m.IdMat
             FROM SALLE s , SITE si ,MATERIELS m
             WHERE s.IdSITE=si.IdSITE
             AND m.IdS=s.IdS";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;    
}

function getMaterielsNonAff(){
     $conn = dbConnect();
     $sql = "SELECT  TypeMat, numSerie ,Etat_fonctionnement ,IdMat
             FROM MATERIELS
             WHERE IdMat Not In (SELECT m.IdMat
                                 FROM MATERIELS m , SALLE s , SITE si
                                 WHERE s.IdSITE=si.IdSITE
                                 AND m.IdS=s.IdS)";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;    
}

function getMateriel($IdMat){
    $conn = dbConnect();
    $sql = "SELECT m.IdMat, m.numSerie, s.IdS, si.NomSITE , m.TypeMat ,m.Etat_fonctionnement FROM SALLE s , SITE si ,MATERIELS m "
            . "WHERE s.IdSITE=si.IdSITE AND m.IdS=s.IdS AND m.IdMat ='$IdMat'";
   $stmt = $conn->prepare($sql); 
   $stmt->execute();
   $res = $stmt->fetch(PDO::FETCH_ASSOC);
   return $res;
}

function getMaterielNonAff($IdMat){
    $conn = dbConnect();
    $sql = "SELECT IdMat, numSerie, TypeMat ,Etat_fonctionnement FROM MATERIELS "
            . "WHERE IdMat = $IdMat";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res;    
}

function updateMateriel($args){
    $cx = bdConnect();
    $Type = $args['Type'];
    $Etat = $args['Etat'];
    $IdS = $args['Salle'];
    $IdMat = $args['IdMat'];
    $serie = $args['numSerie'];
    $ModifierMat ="UPDATE MATERIELS
                   SET TypeMat ='$Type' , numSerie = '$serie', Etat_fonctionnement='$Etat', IdS=$IdS
                   WHERE IdMat = '$IdMat'";
    $queryModifierMat = mysqli_query($cx,$ModifierMat);   
}

function deleteMateriel($IdMat){
    $cx = bdConnect();
    $sql = "DELETE FROM RESERVERHORSCOURS WHERE IdMat = $IdMat;";
    mysqli_query($cx,$sql);
    $sql = "DELETE FROM RESERVER WHERE IdMat = $IdMat;";
    mysqli_query($cx,$sql); 
    $sql = "DELETE FROM MATERIELS WHERE IdMat = $IdMat;";
    mysqli_query($cx, $sql);     
}

/* Site */

function insertSite($args){
    $db = dbConnect();
    $stmt = $db->prepare("INSERT INTO SITE (NomSITE, NumRue, RueSite, Ville, BoitePostale) VALUES (:nomSite, :numRue, :nomRue, :ville, :cp);");
    $stmt->bindParam(':nomSite', $args['nomSite']);
    $stmt->bindParam(':numRue', $args['numRue']);
    $stmt->bindParam(':nomRue', $args['nomRue']);
    $stmt->bindParam(':ville', $args['ville']);
    $stmt->bindParam(':cp', $args['cp']);
    $stmt->execute();
    $stmt->closeCursor();      
}

function getSite($id){
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT IdSITE, NomSITE, NumRue, RueSite, Ville, BoitePostale FROM SITE WHERE IdSITE = $id"); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res;
}

function updateSite($args){
    $db = dbConnect();
    $id = $args['IdSITE'];
    $nomSite = $args['nomSite'];
    $numRue = $args['numRue'];
    $nomRue = $args['nomRue'];
    $ville = $args['ville'];
    $cp = $args['cp'];    
    $sql = "UPDATE SITE SET NomSITE = '$nomSite', NumRue = '$numRue', RueSite = '$nomRue', Ville = '$ville',"
            . " BoitePostale = '$cp'  WHERE IdSITE = $id;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->closeCursor();      
}

function deleteSite($id){
    $db = dbConnect();
    //récuperer les salles du batiments
    $stmt = $db->prepare("SELECT IdS FROM SALLE WHERE IdSITE = $id"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Pour chaque salle
    for($i=0;$i<=count($res)-1;$i++){
        $idSalle = $res[$i]['IdS'];
        //Récuperer les numéro de séances liés à la salle
        $stmt = $db->prepare("SELECT NumS FROM SEANCES WHERE IdS = $idSalle"); 
        $stmt->execute();
        $seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Désafecter les réservations liées aux séances
        for($j=0;$j<=count($seances)-1;$j++){
            $idSeance = $seances[$j]['NumS'];
            $sql = "DELETE FROM RESERVER WHERE NumS = $idSeance;";
            $db->exec($sql);
            $stmt->closeCursor();             
        }       
        //supprimer les séances de ces salles
        $sql = "DELETE FROM SEANCES WHERE IdS = $idSalle;";
        $db->exec($sql);
        $stmt->closeCursor(); 
        //désafecter le matériel
        $sql = "UPDATE MATERIELS SET IdS = NULL WHERE IdS = $idSalle;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();
    }
    //Supprimer les salles
    $sql = "DELETE FROM SALLE WHERE IdSITE = $id;";
    $db->exec($sql);
    $stmt->closeCursor();
    //supprimer le batiment
    $sql = "DELETE FROM SITE WHERE IdSITE = $id;";
    $db->exec($sql);    
    $stmt->closeCursor();
}

/* Administration */

function getCSV($file){  
    $row = 1;
    if (($handle = fopen($file['file']['tmp_name'], 'r')) !== FALSE) {
        $arrayCsv = array();
        $nbLigne = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            $row++;
            for ($c=0; $c < $num; $c++) {
                $arrayCsv[$nbLigne] = utf8_encode($data[$c]);
                $nbLigne++;
            }
        }
        fclose($handle);
    }    
    $arrayTrim = array();
    $cmp = 0;
    foreach($arrayCsv as $row){
        $arrayTrim[$cmp] = explode(';', $row);
        $cmp++;
    }
    return $arrayTrim;
}

function insertEtudiantMultiple($array, $idForm){
    for($i=3;$i<=count($array)-1;$i++){
        $mdp = generatePassword();
        $nomEtudiant = $array[$i][2];
        $prenomEtudiant = $array[$i][3];
        $numEtudiant = $array[$i][5];
        if(empty($numEtudiant)){
            $numEtudiant = generateNumEtudiant();
        }
        InsererEtudiant($numEtudiant, $nomEtudiant, $prenomEtudiant, $idForm, $mdp);
    }  
}

function generatePassword($length = 8){
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);
    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }
    return $result;    
}

function generateNumEtudiant(){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT max(IdE) as id FROM ETUDIANT WHERE IdE like '9%';"); 
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($res['id'])){
        $idEtudiant = 90000000;
    }else{
        $idEtudiant = $res['id'] + 1;
    }
    return $idEtudiant;   
}

//Export etudiants (etudiants, formations, td)
function getExportEtudiants(){
    $db = dbConnect();
    //Etudiant affecté dans une formation et un groupe de td
    $stmt = $db->prepare("SELECT e.IdE, e.NomE, e.PrenomE, f.IntituleF, g.NumGroupTD "
            . "FROM ETUDIANT e, FORMATION f, GROUPE_TD g, APPARTIENT a "
            . "WHERE e.IdF = f.IdF AND e.IdE = a.IdE AND a.IdGTD = g.IdGTD;"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);   
    //Etudiant sans formation (donc sans td)
    $stmt = $db->prepare("SELECT e.IdE, e.NomE, e.PrenomE "
            . "FROM ETUDIANT e WHERE IdF IS NULL;"); 
    $stmt->execute();
    $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);    
    //etudiant avec formation et sans td
    $stmt = $db->prepare("SELECT e.IdE, e.NomE, e.PrenomE, f.IntituleF "
            . "FROM ETUDIANT e, FORMATION f "
            . "WHERE e.IdF = f.IdF "
            . "AND e.IdF IS NOT NULL "
            . "AND e.IdE NOT IN (SELECT IdE FROM APPARTIENT);"); 
    $stmt->execute();
    $res3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Reconstitue les tableaux  
    $header = array('Numéro étudiants' => 0, 'Nom' => 0, 'Prenom' => 0, 'Formation' => 0, 'Groupe TD' => 0);
    $dataToExport = array_merge($res, $res2);
    $dataToExport = array_merge($dataToExport, $res3);
    //export
    export_data_to_csv($dataToExport, $header, 'Etudiants');
}

//Export enseignants (enseignants, domaines)
function getExportEnseignants(){
    $db = dbConnect();
    //Etudiant affecté dans une formation et un groupe de td
    $stmt = $db->prepare("SELECT IdENS, PrenomENS, NomENS, TypeENS FROM ENSEIGNANT;"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $header = array('Numéro enseignants' => 0, 'Nom' => 0, 'Prenom' => 0, 'Type enseignant' => 0);
    export_data_to_csv($res, $header, 'Enseignants');
}

//Export formations et groupes de TD, UE et matières
function getExportFormations(){
    $db = dbConnect();
    //Etudiant affecté dans une formation et un groupe de td
    $stmt = $db->prepare("SELECT f.IntituleF, u.IntituleUE, m.IntituleM, m.TypeM, m.NbHeuresFixees, d.Intitule_domaine, ens.NomENS, ens.PrenomENS "
            . "FROM FORMATION f, UNITE_ENSEIGNEMENT u, MATIERES m, DOMAINE d, ENSEIGNE en, ENSEIGNANT ens "
            . "WHERE f.IdF = u.IdF AND u.IdUE = m.IdUE AND d.IdDomaine = m.IdDomaine AND m.NumM = en.NumM AND en.IdENS = ens.IdENS;"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $header = array(array('Formation' => 0, 'Unité d\'enseignement' => 0, 'Matière' => 0, 'Type' => 0, 'Nombre d\'heures' => 0, 'Domaine' => 0,
        'Nom enseignant' => 0, 'Prenom enseignant' => 0));
    export_data_to_csv($res, $header, 'Formations');
}

//Exports matériels
function getExportMateriels(){
    $db = dbConnect();
    //Matériel non affectés
    $stmt = $db->prepare("SELECT NumSerie, TypeMat, Etat_fonctionnement FROM MATERIELS WHERE IdS IS NULL;"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Matériel affectés
    $stmt = $db->prepare("SELECT m.NumSerie, m.TypeMat, m.Etat_fonctionnement, s.NomS, si.NomSITE FROM MATERIELS m, SALLE s, SITE si WHERE m.IdS = s.IdS AND s.IdSITE = si.IdSITE;"); 
    $stmt->execute();
    $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);      
    $header = array('Numéro de série' => 0, 'Type matériel' => 0, 'Etat' => 0, 'Nom salle' => 0, 'Nom site' => 0);
    $dataToExport = array_merge($res, $res2);
    export_data_to_csv($dataToExport, $header, 'Materiels');    
}

//Exports Séances (séances, classe, enseignant, salle, batiment, site, réservation(optionnel)
function getExportSeances(){
    $db = dbConnect();
    //seances CM
    //avec reservations
    $stmt = $db->prepare("SELECT f.IntituleF, cm.NumGroupCM, s.DateDebutSeance, s.DateFinSeance, m.IntituleM, e.NomENS, e.PrenomENS, sa.NomS, si.NomSITE "
            . "FROM SEANCES s, SALLE sa, SITE si, RESERVER r, MATIERES m, DISPENSE d, MATERIELS mat, ENSEIGNANT e, GROUPE_CM cm, FORMATION f "
            . "WHERE s.NumS = r.NumS AND r.IdMat = mat.IdMat AND s.NumS = d.NumS AND d.IdENS = e.IdENS AND s.NumM = m.NumM "
            . "AND s.IdS = sa.IdS AND sa.IdSITE = si.IdSITE AND s.IdGCM = cm.IdGCM AND cm.IdF = f.IdF;");
    $stmt->execute();
    $reqCMRes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //sans reservations
    $stmt = $db->prepare("SELECT f.IntituleF, cm.NumGroupCM, s.DateDebutSeance, s.DateFinSeance, m.IntituleM, e.NomENS, e.PrenomENS, sa.NomS, si.NomSITE "
            . "FROM SEANCES s, SALLE sa, SITE si, MATIERES m, DISPENSE d, ENSEIGNANT e, GROUPE_CM cm, FORMATION f "
            . "WHERE s.NumS = d.NumS AND d.IdENS = e.IdENS AND s.NumM = m.NumM "
            . "AND s.IdS = sa.IdS AND sa.IdSITE = si.IdSITE AND s.IdGCM = cm.IdGCM AND cm.IdF = f.IdF;"); 
    $stmt->execute();
    $reqCMNotRes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arrayCM = array_merge($reqCMRes, $reqCMNotRes);
    //Seances TD
    //avec reservations
    $stmt = $db->prepare("SELECT f.IntituleF, td.NumGroupTD, s.DateDebutSeance, s.DateFinSeance, m.IntituleM, e.NomENS, e.PrenomENS, sa.NomS, si.NomSITE "
            . "FROM SEANCES s, SALLE sa, SITE si, RESERVER r, MATIERES m, DISPENSE d, MATERIELS mat, ENSEIGNANT e, GROUPE_TD td, FORMATION f "
            . "WHERE s.NumS = r.NumS AND r.IdMat = mat.IdMat AND s.NumS = d.NumS AND d.IdENS = e.IdENS AND s.NumM = m.NumM "
            . "AND s.IdS = sa.IdS AND sa.IdSITE = si.IdSITE AND s.IdGTD = td.IdGTD AND td.IdF = f.IdF;");
    $stmt->execute();
    $reqTDRes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //sans reservations
    $stmt = $db->prepare("SELECT f.IntituleF, td.NumGroupTD, s.DateDebutSeance, s.DateFinSeance, m.IntituleM, e.NomENS, e.PrenomENS, sa.NomS, si.NomSITE "
            . "FROM SEANCES s, SALLE sa, SITE si, MATIERES m, DISPENSE d, ENSEIGNANT e, GROUPE_TD td, FORMATION f "
            . "WHERE s.NumS = d.NumS AND d.IdENS = e.IdENS AND s.NumM = m.NumM "
            . "AND s.IdS = sa.IdS AND sa.IdSITE = si.IdSITE AND s.IdGTD = td.IdGTD AND td.IdF = f.IdF;");
    $stmt->execute();
    $reqTDNotRes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arrayTD = array_merge($reqTDRes, $reqTDNotRes);
    $dataToExport = array_merge($arrayCM, $arrayTD);
    //Raccord
    $header = array('Formation' => 0, 'Groupe (CM/TD)' => 0, 'Date début' => 0, 'Date fin' => 0, 
        'Matière' => 0, 'Nom enseignant' => 0, 'Prenom enseignant' => 0, 'Salle' => 0, 'Site' => 0);
    export_data_to_csv($dataToExport, $header, 'Seances');    
}

//Exports site (site, batiment, salles)
function getExportSalles(){
    $db = dbConnect();
    //salle non éuipé
    $stmt = $db->prepare("SELECT s.NomS, s.CapaciteS, s.Types, si.NomSITE FROM SITE si, SALLE s WHERE s.IdSITE = si.IdSITE;"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Salle équipé
    $stmt = $db->prepare("SELECT s.NomS, s.CapaciteS, s.Types, si.NomSITE "
            . "FROM SITE si, SALLE s, MATERIELS m WHERE s.IdSITE = si.IdSITE AND m.IdS = s.IdS;"); 
    $stmt->execute();
    $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);   
    //Raccord
    $header = array('Salle' => 0, 'Capacité' => 0, 'Etat' => 0, 'Type' => 0, 'Site' => 0);
    $dataToExport = array_merge($res, $res2);
    export_data_to_csv($dataToExport, $header, 'Salles');  
}

//Sites
function getExportSites(){
    $db = dbConnect();
    //salle non éuipé
    $stmt = $db->prepare("SELECT NomSITE, NumRue, RueSite, Ville, BoitePostale FROM SITE;"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $header = array('Site' => 0, 'N°rue' => 0, 'Rue' => 0, 'Ville' => 0, 'Boite Postale' => 0);
    export_data_to_csv($res, $header, 'Sites');
}

//Export réservations hors cours
function getExportReservations(){
    $db = dbConnect();
    //salle non éuipé
    $stmt = $db->prepare("SELECT a.NomA, e.IdENS, m.NumSerie, m.TypeMat, hc.DateResaHC, hc.DateDebutResaHC, hc.DateFinResaHC "
            . "FROM RESERVERHORSCOURS hc, ENSEIGNANT e, MATERIELS m, ADMINISTRATION a "
            . "WHERE hc.IdENS = e.IdENS AND hc.IdA = a.IdA AND m.IdMat = hc.IdMat;"); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $header = array('Nom gestionnaire' => 0, 'Numéro enseignant' => 0, 'N°serie' => 0, 'Date' => 0,
        'Heure début' => 0, 'Heure fin' => 0);
    export_data_to_csv($res, $header, 'Reservations');   
}

function export_data_to_csv($data,$header, $filename='export',$delimiter = ';',$enclosure = '"'){    
    // Tells to the browser that a file is returned, with its name : $filename.csv
    header("Content-disposition: attachment; filename=$filename.csv");
    // Tells to the browser that the content is a csv file
    header("Content-Type: text/csv");

    // I open PHP memory as a file
    $fp = fopen("php://output", 'w');

    // Insert the UTF-8 BOM in the file
    fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

    // I add the array keys as CSV headers
   fputcsv($fp, array_keys($header),$delimiter,$enclosure);
   
    // Add all the data in the file
    for($i=0;$i<=count($data)-1;$i++){
        fputcsv($fp, $data[$i], $delimiter, $enclosure);
    }

    // Close the file
    fclose($fp);
    
    die();
}

