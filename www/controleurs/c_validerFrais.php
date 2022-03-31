<?php

/** 
 * @category  PPE
 * @package   GSB
 * @author Ruthy Haouzi <ruthyhaouzi15@gmail.com>
 
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING); 
$idComptable = $_SESSION['idUtilisateur']; 
$moisActuel = getMois(date('d/m/Y')); 
$moisPrecedent = getMoisPrecedent($moisActuel); 
$fichesCL = $pdo->ficheDuDernierMoisCL($moisPrecedent); 
if (!$uc) {
    $uc = 'validerFrais'; }


switch ($action) {
case 'choisirVisiteursEtMois':
    
    $lesVisiteurs = $pdo->getLesVisiteurs();
    $lesCles1[] = array_keys($lesVisiteurs);
    $visiteurASelectionner = $lesCles1[0];
    $lesMois = getLesDouzeDerniersMois($moisActuel);
    $lesCles [] = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include 'vues/v_listeVisiteurs.php';

    break;
//case 'ChoisirLesMois':
   // $idVisiteur= filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    
    
    

    
case 'voirEtatFrais':
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $lesVisiteurs=$pdo->getLesVisiteurs();
        $visiteurASelectionner=$idVisiteur;
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesMois = getLesDouzeDerniersMois($moisActuel);
        $moisASelectionner=$leMois;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $_SESSION['idV'] = $idVisiteur;
        $_SESSION['idM'] = $leMois;
        var_dump($_SESSION['idM']);
        if(!is_array($lesInfosFicheFrais)){
            ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois');
            include 'vues/v_erreurs.php';
            include 'vues/v_listeMois.php';
        }
        else{
            include 'vues/v_validerFrais.php';
        }
        break;
        
        case 'validerMajFraisForfait':
             $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
             $idVisiteur= $_SESSION['idV']; 
             $lesVisiteurs=$pdo->getLesVisiteurs();
             $visiteurASelectionner=$idVisiteur;
             $mois= $_SESSION['idM'];
             $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
             $lesMois = getLesDouzeDerniersMois($moisActuel);
    var_dump($idVisiteur);
    var_dump($mois);
    if (lesQteFraisValides($lesFrais)) {
       
        $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
        include 'vues/v_validerFrais.php';
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    break;
    case 'corrigerElementHorsForfait':
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        var_dump($idFrais);
        $pdo->supprimerFraisHorsForfait($idFrais);
     
    include 'vues/v_corrigerElementHorsForfait.php';
    break;

case 'actualiserNouvelElement':
    $mois= $_SESSION['idM'];
    $idVisiteur= $_SESSION['idV'];
    $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
    $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
    $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    //var_dump($montant);//var_dump= afficher les informations d'une variable.
    valideInfosFrais($dateFrais, $libelle, $montant);
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
        $pdo->corrigerFraisHorsForfait(
            $idVisiteur,
            $mois,
            $libelle,
            $dateFrais,
            $montant
               
        );
        $lesMois = getLesDouzeDerniersMois($moisActuel);
        $moisASelectionner=$mois;
        $lesVisiteurs=$pdo->getLesVisiteurs();
        $visiteurASelectionner=$idVisiteur;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
         include 'vues/v_validerFrais.php';
    }
    break;
    
   
case 'supprimerFraisHorsForfait':
     $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
     var_dump($idFrais);
     $pdo->supprimerFraisHorsForfait($idFrais);
     
        $mois= $_SESSION['idM'];
        $idVisiteur= $_SESSION['idV'];
        $lesMois = getLesDouzeDerniersMois($moisActuel);
        $moisASelectionner=$mois;
        $lesVisiteurs=$pdo->getLesVisiteurs();
        $visiteurASelectionner=$idVisiteur;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
     
         include 'vues/v_validerFrais.php';
     break;
   
case 'validerLaFicheDeFrais':
    $idVisiteur= $_SESSION['idV'];
    $mois= $_SESSION['idM'];
    $pdo->majEtatFicheFrais($idVisiteur, $mois, 'VA');
   
    include 'vues/v_accueilComptable.php';
    break;

}
