<?php
@session_start();
require_once './Core/Manager.php';

//Recupère les seances d'un enseignant 
 function RecupCoursEns($IdEns){ 
        $conn = dbConnect();
        $sql = "SELECT NumS 
                FROM DISPENSE
                WHERE DISPENSE.IdENS=$IdEns";
               
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resG;
    }
    
//Recuperer les séances d'une salle donnée
 function recupSeanceSalle($IdS){
        $conn = dbConnect();
        $sql = " SELECT NumS "
                . "FROM SEANCES "
                . "WHERE SEANCES.IdS=$IdS";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resG;
    }
    
 //Fonction affichage des salles
 function afficherSalle(){
        $conn = dbConnect();
        $sql = "SELECT SALLE.IdS, SALLE.NomS, SITE.NomSITE"
                . " FROM SALLE, SITE"
                . " WHERE SALLE.IdSITE=SITE.IdSITE"
                . " ORDER BY SITE.NomSITE ASC";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resG;
    }
    
   //Fonction des seance d'une formation
 function recupSeanceForm($IdF){
        $conn = dbConnect();
        $sql = "(SELECT NumS "
                . "FROM SEANCES, GROUPE_TD "
                . "WHERE GROUPE_TD.IdGTD=SEANCES.IdGTD "
                . "AND GROUPE_TD.IdF=$IdF)"
                . "UNION"
                . "(SELECT NumS "
                . "FROM SEANCES, GROUPE_CM "
                . "WHERE GROUPE_CM.IdGCM=SEANCES.IdGCM "
                . "AND GROUPE_CM.IdF=$IdF)";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resG;
    }


//Fonction d'affichage des groupes TD
 function RecupGroupeTD($IdF){
        $conn = dbConnect();
        $sql = "SELECT IdGTD, NumGroupTD FROM GROUPE_TD WHERE IdF=$IdF";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resG;
    }
    //Fonction d'affichage des groupes CM
    function RecupGroupeCM($IdF){
        $conn = dbConnect();
        $sql = "SELECT IdGCM, NumGroupCM FROM GROUPE_CM WHERE IdF=$IdF";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resG;
    }
    //Fonction D'affichage des formations
    function RecupFormation(){
        $conn = dbConnect();
        $sql = "SELECT IdF, IntituleF FROM FORMATION";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resF = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resF;
    }
    
    //Fonction D'affichage des matieres
    function RecupMCM($IdF){
        $conn = dbConnect();
        $sql = "SELECT MATIERES.NumM, MATIERES.IntituleM FROM MATIERES, UNITE_ENSEIGNEMENT WHERE MATIERES.IdUE=UNITE_ENSEIGNEMENT.IdUE AND UNITE_ENSEIGNEMENT.IdF=$IdF AND MATIERES.TypeM='CM'";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resM = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resM;
    }
    function RecupMTD($IdF){
        $conn = dbConnect();
        $sql = "SELECT MATIERES.NumM, MATIERES.IntituleM FROM MATIERES, UNITE_ENSEIGNEMENT WHERE MATIERES.IdUE=UNITE_ENSEIGNEMENT.IdUE AND UNITE_ENSEIGNEMENT.IdF=$IdF AND MATIERES.TypeM='TD'";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resM = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resM;
    }
        
    //Fonction D'affichage des cours speciaux
    function RecupCSPE(){
        $conn = dbConnect();
        $sql = "SELECT IdCSPE, IntituleCSPE FROM COURS_SPECIAUX";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resCSPE = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resCSPE;
    }
    //Fonction D'affichage du nombre d'heures
    function RecupHeuresF($NumM){
        $conn = dbConnect();
        $sql = "SELECT NbHeuresFixees FROM MATIERES WHERE MATIERES.NumM=$NumM";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resH = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resH;
    }
    
    function RecupSeance(){
        $conn = dbConnect();
        $sql = "SELECT SEANCES.NumS FROM MATIERES, SEANCES WHERE SEANCES.NumM=MATIERES.NumM AND MATIERES.NumM=9";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resCSPE = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resCSPE;
    }    
    
    //Fonctions d'affichage des enseignants
    function RecupEns(){
        $conn = dbConnect();
        $sql = "SELECT IdENS, NomENS, PrenomENS FROM ENSEIGNANT";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resE = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resE;
    }
//Fonctions récuperer le nom et prénom de l'ensiegnant sélectionné
 function RecupNomEns($idens){
        $conn = dbConnect();
        $sql = "SELECT NomENS, PrenomENS FROM ENSEIGNANT WHERE ENSEIGNANT.IdENS = $idens";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resE = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resE;
    }
    //Fonction récuperer le nom de la formation selectionnée
 function RecupNomFormation($idf){
        $conn = dbConnect();
        $sql = "SELECT  IntituleF FROM FORMATION WHERE FORMATION.IdF=$idf";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resF = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resF;
    }
  // Récuperer les numéros de séance en fonction du prof et de la formation  
    function RecupNumS($idens,$idf){
        
        $conn = dbConnect();
        $sql = "    (SELECT SEANCES.NumS
FROM SEANCES 
WHERE  SEANCES.IdGCM IN (SELECT IdGCM FROM GROUPE_CM WHERE IdF=$idf)
OR SEANCES.IdGTD IN (SELECT IdGTD FROM GROUPE_TD WHERE IdF=$idf) 
ORDER BY SEANCES.NumS)    
UNION(SELECT SEANCES.NumS
      FROM SEANCES , DISPENSE
      WHERE SEANCES.NumS=DISPENSE.NumS
      AND DISPENSE.IdENS = $idens
      ORDER BY SEANCES.NumS)";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resNumS = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $resNumS;
        
    }
 // Recuperer les séance d'un étudiant    
     function RecupCours($IdE){
        
        $conn = dbConnect();
        $sql = "    (SELECT DISTINCT SEANCES.NumS
                    FROM SEANCES, ETUDIANT
                    WHERE SEANCES.IdGCM=ETUDIANT.IdGCM
                    AND ETUDIANT.IdE = $IdE)
                    UNION 
                    (SELECT DISTINCT SEANCES.NumS
                    FROM SEANCES, APPARTIENT, GROUPE_TD
                    WHERE SEANCES.IdGTD=APPARTIENT.IdGTD
                    AND APPARTIENT.IdE = $IdE)";
        $stmt = $conn->prepare($sql); 
        $stmt->execute();
        $resNumS = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $resNumS;
        
    }
    
    function Recupinfo($NumS){
    
       
                               
                for($i=0;$i<=count($NumS)-1;$i++){
                 
 $conn = dbConnect();                   
$sql ="SELECT SEANCES.NumS ,MATIERES.IntituleM AS title, GROUPE_TD.NumGroupTD AS groupe, SALLE.NomS , ENSEIGNANT.NomENS , ENSEIGNANT.PrenomENS, SEANCES.DateDebutSeance , SEANCES.DateFinSeance , SITE.NomSITE , FORMATION.IntituleF, MATIERES.CouleurM AS couleur
FROM MATIERES , SEANCES, GROUPE_TD, SALLE , DISPENSE , ENSEIGNANT ,SITE , FORMATION 
WHERE SEANCES.NumM=MATIERES.NumM
AND GROUPE_TD.IdF=FORMATION.IdF
AND SALLE.IdSITE=SITE.IdSITE
AND DISPENSE.NumS=SEANCES.NumS
AND SEANCES.IdGTD=GROUPE_TD.IdGTD
AND ENSEIGNANT.IdENS=DISPENSE.IdENS
AND SEANCES.IdS=SALLE.IdS
AND SEANCES.NumS = ".$NumS[$i]['NumS'] ;
$stmt = $conn->prepare($sql); 
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
if(count($res)!= 0){
    
    $Resultat[$i] = $res ;
    
}else {
        $conn = dbConnect();    
        $sql1 =" SELECT SEANCES.NumS , MATIERES.IntituleM AS title, GROUPE_CM.NumGroupCM AS groupe, SALLE.NomS , ENSEIGNANT.NomENS , ENSEIGNANT.PrenomENS , SEANCES.DateDebutSeance , SEANCES.DateFinSeance , SITE.NomSITE   , FORMATION.IntituleF, MATIERES.CouleurM AS couleur
        FROM MATIERES , SEANCES, GROUPE_CM, SALLE , DISPENSE , ENSEIGNANT , SITE , FORMATION
        WHERE SEANCES.NumM=MATIERES.NumM
        AND GROUPE_CM.IdF=FORMATION.IdF
        AND SALLE.IdSITE=SITE.IdSITE
        AND DISPENSE.NumS=SEANCES.NumS
        AND SEANCES.IdGCM=GROUPE_CM.IdGCM
        AND ENSEIGNANT.IdENS=DISPENSE.IdENS
        AND SEANCES.IdS=SALLE.IdS
        AND SEANCES.NumS =".$NumS[$i]['NumS'] ;
        $stmt = $conn->prepare($sql1); 
        $stmt->execute();
        $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);  
        if(count($res1)!= 0){

            $Resultat[$i] = $res1 ;

        }else {
                $conn = dbConnect();
                $sql2 ="SELECT  SEANCES.NumS , COURS_SPECIAUX.IntituleCSPE AS title, GROUPE_CM.NumGroupCM AS groupe, SALLE.NomS , ENSEIGNANT.NomENS , ENSEIGNANT.PrenomENS, SEANCES.DateDebutSeance , SEANCES.DateFinSeance , SITE.NomSITE  , FORMATION.IntituleF, COURS_SPECIAUX.CouleurSPE AS couleur
                FROM COURS_SPECIAUX, SEANCES, GROUPE_CM, SALLE , DISPENSE , ENSEIGNANT , SITE, FORMATION
                WHERE SEANCES.IdCSPE=COURS_SPECIAUX.IdCSPE
                AND GROUPE_CM.IdF=FORMATION.IdF
                AND SALLE.IdSITE=SITE.IdSITE
                AND DISPENSE.NumS=SEANCES.NumS
                AND SEANCES.IdGCM=GROUPE_CM.IdGCM
                AND ENSEIGNANT.IdENS=DISPENSE.IdENS
                AND SEANCES.IdS=SALLE.IdS
                AND SEANCES.NumS = ".$NumS[$i]['NumS'];
                $stmt = $conn->prepare($sql2); 
                $stmt->execute();
                $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($res2)!= 0){

                    $Resultat[$i] = $res2 ;

                }else {

                        $conn = dbConnect();
                        $sql3 = "SELECT SEANCES.NumS , COURS_SPECIAUX.IntituleCSPE AS title, GROUPE_TD.NumGroupTD AS groupe, SALLE.NomS , ENSEIGNANT.NomENS , ENSEIGNANT.PrenomENS, SEANCES.DateDebutSeance , SEANCES.DateFinSeance, SITE.NomSITE  , FORMATION.IntituleF, COURS_SPECIAUX.CouleurSPE AS couleur
                        FROM COURS_SPECIAUX, SEANCES, GROUPE_TD, SALLE , DISPENSE , ENSEIGNANT , SITE, FORMATION
                        WHERE SEANCES.IdCSPE=COURS_SPECIAUX.IdCSPE
                        AND GROUPE_TD.IdF=FORMATION.IdF
                        AND SALLE.IdSITE=SITE.IdSITE
                        AND DISPENSE.NumS=SEANCES.NumS
                        AND SEANCES.IdGTD=GROUPE_TD.IdGTD
                        AND ENSEIGNANT.IdENS=DISPENSE.IdENS
                        AND SEANCES.IdS=SALLE.IdS
                        AND SEANCES.NumS = ".$NumS[$i]['NumS'];
                        $stmt = $conn->prepare($sql3); 
                        $stmt->execute();
                        $res3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if(count($res3)!= 0){

                            $Resultat[$i] = $res3 ;
                             
                         }else { $Resultat[$i] = ""; 
                         
                         
                         }        
                         
                    }  
                    
                } 
             
    }         
                }

                return $Resultat;             
 }
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 // Vérification de la disponibilité de la salle 
 // Recehrcher Identifiant de la salle
 
 function IdSalle($NumS){
     $conn = dbConnect(); 
     $sql="SELECT SEANCES.IdS
     FROM SEANCES 
     WHERE SEANCES.NumS = $NumS";
      $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetch();
    return $res[0];
 }
 
 //Recherche horaires d'une salle 
 
 function horaireSalle($NumS,$DateDebut,$DateFin){
     $IdS = IdSalle($NumS);
      $conn = dbConnect(); 
     $sql1="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES
            WHERE SEANCES.NumS <> $NumS
            AND SEANCES.IdS= $IdS
            AND SEANCES.DateDebutSeance >= '$DateDebut' AND SEANCES.DateDebutSeance < '$DateFin'";         
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetchAll();
    
    $sql2="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES
            WHERE SEANCES.NumS <> $NumS
            AND SEANCES.IdS= $IdS
            AND SEANCES.DateFinSeance > '$DateDebut' AND SEANCES.DateFinSeance <= '$DateFin'";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res2 = $stmt->fetchAll();
                

           $sql3="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES
            WHERE SEANCES.NumS <> $NumS
            AND SEANCES.IdS= $IdS
            AND SEANCES.DateDebutSeance < '$DateDebut' 
            AND SEANCES.DateFinSeance > '$DateFin'";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();
    $res3 = $stmt->fetchAll();
    
    if (count($res1)!=0||count($res2)!=0||count($res3)!=0){
        return false;
    }else{
        return true;
    }

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 // Vérification de la disponibilité du prof 
 // Recehrcher Identifiant du prof
 
 function IdEns($NumS){
     $conn = dbConnect(); 
     $sql1="SELECT DISPENSE.IdENS
     FROM DISPENSE
     WHERE DISPENSE.NumS = $NumS";
      $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetch();
    return $res1[0];
 }
 
 //Recherche horaires d'un prof 
 
 function horaireEns($NumS,$DateDebut,$DateFin){
     $IdEns = IdEns($NumS);
      $conn = dbConnect(); 
     $sql2="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES, DISPENSE
            WHERE DISPENSE.NumS=SEANCES.NumS 
            AND DISPENSE.NumS <> $NumS
            AND DISPENSE.IdEns= $IdEns
            AND SEANCES.DateDebutSeance >= '$DateDebut' AND SEANCES.DateDebutSeance < '$DateFin'";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res2 = $stmt->fetchAll();
    
    $sql3="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES, DISPENSE
            WHERE DISPENSE.NumS=SEANCES.NumS 
            AND DISPENSE.NumS <> $NumS
            AND DISPENSE.IdEns= $IdEns
             AND SEANCES.DateFinSeance > '$DateDebut' AND SEANCES.DateFinSeance <= '$DateFin'";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();
    $res3 = $stmt->fetchAll();


 $sql4="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES, DISPENSE
            WHERE DISPENSE.NumS=SEANCES.NumS 
            AND DISPENSE.NumS <> $NumS
            AND DISPENSE.IdEns= $IdEns
           AND SEANCES.DateDebutSeance < '$DateDebut' 
            AND SEANCES.DateFinSeance > '$DateFin'";
 
    $stmt = $conn->prepare($sql4); 
    $stmt->execute();
    $res4 = $stmt->fetchAll();
    
    if (count($res2)!=0||count($res3)!=0||count($res4)!=0){
        return false;
    }else{
        return true;
    }

}
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 // Vérification de la disponibilité des groupes
 // Recehrcher Identifiant de du groupe de CM 
function IdGCMTD($NumS){
     $conn = dbConnect(); 
     $sql1="SELECT GROUPE_CM.IdF
     FROM SEANCES , GROUPE_CM
     WHERE SEANCES.IdGCM = GROUPE_CM.IdGCM
     AND SEANCES.NumS = $NumS";
     
      $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetch();
}    
 
//if($res1[0]!=NULL){

     //Recherche horaires d'un groupe CM
 
 function horaireCM($NumS,$DateDebut,$DateFin){
     $Idf = IdGCMTD($NumS);
      $conn = dbConnect(); 
     $sql2="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES , GROUPE_CM 
            WHERE SEANCES.IdGCM = GROUPE_CM.IdGCM
            AND SEANCES.NumS <> $NumS
            AND GROUPE_CM.IdF= $Idf
            AND SEANCES.DateDebutSeance >= '$DateDebut' AND SEANCES.DateDebutSeance < '$DateFin'";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res2 = $stmt->fetchAll();
    
    $sql3="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
            FROM SEANCES , GROUPE_CM 
            WHERE SEANCES.IdGCM = GROUPE_CM.IdGCM
            AND SEANCES.NumS <> $NumS
            AND GROUPE_CM.IdF= $Idf
             AND SEANCES.DateFinSeance > '$DateDebut' AND SEANCES.DateFinSeance <= '$DateFin'";
    $stmt = $conn->prepare($sql3); 
    $stmt->execute();
    $res3 = $stmt->fetchAll();


 $sql4="SELECT SEANCES.DateDebutSeance , SEANCES.DateFinSeance 
           FROM SEANCES , GROUPE_CM 
            WHERE SEANCES.IdGCM = GROUPE_CM.IdGCM
            AND SEANCES.NumS <> $NumS
            AND GROUPE_CM.IdF= $Idf
           AND SEANCES.DateDebutSeance < '$DateDebut' 
           AND SEANCES.DateFinSeance > '$DateFin'";
 
    $stmt = $conn->prepare($sql4); 
    $stmt->execute();
    $res4 = $stmt->fetchAll();
    
    if (count($res2)!=0||count($res3)!=0||count($res4)!=0){
        return false;
    }else{
        return true;
    }

}

// Recehrhcer les salles disponibles



function horaireSalleDispo($DateDebut,$DateFin){
     
      $conn = dbConnect(); 
     $sql1="SELECT distinct(SALLE.IdS) , SALLE.NomS , SITE.NomSITE , SALLE.CapaciteS 
            FROM  SALLE , SITE
            WHERE SALLE.IdSITE=SITE.IdSITE
            AND SALLE.IdS NOT IN (
                                      SELECT SEANCES.IdS
                                      FROM SEANCES
                                      WHERE SEANCES.DateDebutSeance >= '$DateDebut' AND SEANCES.DateDebutSeance < '$DateFin')
            AND SALLE.IdS NOT IN (
                                      SELECT SEANCES.IdS
                                      FROM SEANCES
                                      WHERE SEANCES.DateFinSeance > '$DateDebut' AND SEANCES.DateFinSeance <= '$DateFin')
             AND SALLE.IdS NOT IN (
                                      SELECT SEANCES.IdS
                                      FROM SEANCES
                                      WHERE SEANCES.DateDebutSeance < '$DateDebut' 
            						  AND SEANCES.DateFinSeance > '$DateFin')";        
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
$res1 = $stmt->fetchAll();
return $res1;
}