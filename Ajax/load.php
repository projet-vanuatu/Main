<?php

@session_start();

require_once '../Core/Manager.php';

if(isset($_SESSION['enseignantPlanning']) && $_SESSION['formationPlanning']){
    $idens = $_SESSION['enseignantPlanning'];
    $idf = $_SESSION['formationPlanning'];    
}else{
    $idens = 0;
    $idf = 0;     
}

$connect = dbConnect();

$NumS = RecupNumS($connect,$idens,$idf);
$res1 = Recupinfo($connect,$NumS);

$data = array();
$result = $res1;

if(!empty($result)){
    foreach($result as $row){
        $data[] = array(
            'id'   => $row[0]["NumS"],
            'start'   => $row[0]["DateDebutSeance"],
            'groupe'  => $row[0]["groupe"],
            'title'   => utf8_encode($row[0]["title"]),
            'nomS'   => utf8_encode($row[0]["NomS"]),
            'end'   => $row[0]["DateFinSeance"],
            'nomens'=> utf8_encode($row[0]['NomENS']),
            'prenomens'=> utf8_encode($row[0]['PrenomENS']),
            'site'=> utf8_encode($row[0]['NomSITE']),
            'NomF'=> utf8_encode($row[0]['IntituleF']),
            'color'=> $row[0]['couleur'],
        );
    }
}else{
    $data = array();        
}

function RecupNumS($conn, $idens, $idf){       
    $sql = "(SELECT SEANCES.NumS
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

function Recupinfo($conn, $NumS){
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

echo (isset($data)) ? utf8_encode(json_encode($data)) : ""; 
