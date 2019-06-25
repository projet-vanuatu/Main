<?php
@session_start();

require_once '../../Core/Manager.php';

$conn = dbConnect();

if(isset($_POST['id'])){
    $NumS=$_POST['id'];
    $sql1="SELECT MATERIELS.TypeMat as TypeMat, MATERIELS.numSerie as numSerie , MATERIELS.Etat_fonctionnement as Etat ,  SALLE.NomS as NomS , SITE.NomSITE as Site 
            FROM MATERIELS, SEANCES, SALLE , SITE
            WHERE MATERIELS.IdS=SALLE.IdS
            AND SALLE.IdSITE=SITE.IdSITE
            AND SALLE.IdS=SEANCES.IdS
            AND SEANCES.NumS=$NumS";
    $stmt = $conn->prepare($sql1); 
    $stmt->execute();
    $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($res1)){
        $resultat[] = array(
            'titre' => "<b>Matériel(s) équipé dans la salle :</b>",
            'TypeMat'   => "",
            'Etat'   => '',
            'numSerie'  => "",
            'NomS'  => '',
            'Site'  => '',
        );   
        for($i=0;$i<=count($res1)-1;$i++){
            $resultat[] = array(
                'titre' => '',
                'TypeMat'   =>$res1[$i]['TypeMat'],
                'Etat'   => $res1[$i]['Etat'],
                'numSerie'  => $res1[$i]['numSerie'],
                'NomS'  => $res1[$i]['NomS'],
                'Site'  => $res1[$i]['Site'],
            );
        }  
    }else{    
        $resultat[] = array(
            'titre' => "<b>Pas de matériel équipé dans salle.</b>",
            'TypeMat'   => "",
            'Etat'   => '',
            'numSerie'  => "",
            'NomS'  => '',
            'Site'  => '',
        );
    } 

    $sql2="SELECT MATERIELS.TypeMat  as TypeMat , MATERIELS.numSerie  as numSerie   , MATERIELS.Etat_fonctionnement as Etat  , SALLE.NomS as NomS , SITE.NomSITE as Site
            FROM MATERIELS, RESERVER , SALLE , SITE , SEANCES
            WHERE MATERIELS.IdMat=RESERVER.IdMat
             AND SALLE.IdSITE=SITE.IdSITE
            AND SEANCES.IdS=SALLE.IdS
            AND RESERVER.NumS=SEANCES.NumS
            AND SEANCES.NumS=$NumS";
    $stmt = $conn->prepare($sql2); 
    $stmt->execute();
    $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(!empty($res2)){
        $resultat[] = array(
            'titre' => "",
            'TypeMat'   => "<b>Réservation(s):</b>",
            'Etat'   => '',
            'numSerie'  => "",
            'NomS'  => '',
            'Site'  => '',
        );
        for($i=0;$i<=count($res2)-1;$i++){
            $resultat[] = array(
                'titre' => '',
                'TypeMat'   =>$res2[$i]['TypeMat'],
                'Etat'   => $res2[$i]['Etat'],
                'numSerie'  => $res2[$i]['numSerie'],
                'NomS'  => $res2[$i]['NomS'],
                'Site'  => $res2[$i]['Site'],
            );
        } 
    }else{
        $resultat[] = array(
        'titre' => "",
        'TypeMat'   => "<b>Pas de réservation.</b>",
        'Etat'   => '',
        'numSerie'  => "",
        'NomS'  => '',
        'Site'  => '',
        );
    }
 
    $_SESSION['matequip']=$resultat;

    if(isset($_SESSION['matequip'])){
        print_r(utf8_encode(json_encode($_SESSION['matequip'])));
    }

}

echo isset($_SESSION['matequip']) ? utf8_encode(json_encode($_SESSION['matequip'])) : "";