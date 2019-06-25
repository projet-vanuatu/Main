<?php
/*
 * Modèle général qui regroupe les fonctions utilisées par plusieurs modèles
 */

require_once 'Manager.php';

function getLastID($attr, $table){
    $db = dbConnect();
    $stmt = $db->prepare("SELECT max($attr) as attr FROM $table;");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res['attr'];  
}

function rebuildArray($array, $index){
    $newArray = array();
    $cmp = 1;
    for($i=0;$i<=count($array)-1;$i++){
        $newArray[$cmp] = $array[$i][$index];
        $cmp++;
    }
    return $newArray;
}

function arrayAssocToIndex($data, $pattern, $len = 8){
    $matches = array();
    $indice = 1;
    foreach($data as $key => $value) {      
        if(substr($key, 0, $len) === $pattern){
            $matches[$indice] = $value;
            $indice++;     
        }
    }  
    return $matches;
}

/*
 * Fonction d'écriture du fichier log
 * @param string $id
 * @param string $nom
 * @param string $prenom
 * @param string $action
 * @param string $idAction
 */
function writeLog($id = '', $nom = 'N/A', $prenom = 'N/A', $action = 'N/A', $idAction = 'N/A'){
//    $path = logFilePath();
    $path = './Logs/Log.txt';
    $file = fopen($path, 'a');
    if($file){
        $ligne = $id . ' - ' . $nom . ' ' . $prenom . ' - a ' . $action . ' : ' . $idAction . '\n';
        fputs($file, $ligne);
        fclose($file);       
    }
}

/*
 * Fonction tranversale
 */

function getEnseignantsPlanning(){   
    $conn = dbConnect();
    $sql = "SELECT IdEns, NomEns, PrenomEns FROM ENSEIGNANT;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $res;
}

 function getFormationsPlanning(){
    $conn = dbConnect();
    $sql = "SELECT IdF, IntituleF FROM FORMATION";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resF = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resF;
}

function getSallesPlanning(){
    $conn = dbConnect();
    $sql = "SELECT SALLE.IdS, SALLE.NomS, SITE.NomSITE"
            . " FROM SALLE, SITE"
            . " WHERE SALLE.IdSITE=SITE.IdSITE"
            . " ORDER BY SITE.NomSITE, SALLE.NomS ASC";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    $resG = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resG;
}

/*
 * Recherche planning
 */

function Recupinfo($NumS){
    $conn = dbConnect();
    for($i=0;$i<=count($NumS)-1;$i++){                
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
        }else{  
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
            }else{
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
                }else{
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
                    }else{
                        $Resultat[$i] = ""; 
                    }                                 
                }                      
            }             
        }         
    }
    return $Resultat;             
}
