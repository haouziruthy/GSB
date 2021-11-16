<?php
/**
 * Index du projet GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Ruthy Haouzi
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
// require once:condition preliminaire avant d'ouvrir l'index il faut que ces fichiers s'ouvrent 
// require: ca n'empeche pas le programme 
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
session_start();// FONCTION qui demarre la super globale session (variable qui peut jongler entre les differentes pages)
$pdo = PdoGsb::getPdoGsb(); // variable:avec un dollar devant pdo: variable, on lui affecte le resultat de la methode getPdoGsb()qui est dans la classe PdoGsb
$estConnecte = estConnecte();
$estConnecteVisiteur = estConnecteVisiteur();
$estConnecteComptable = estConnecteComptable();
require 'vues/v_entete.php';
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
if ($uc && !$estConnecte) { 
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil';// uc: variable super globale qui nous renseigne le controleur 
}
switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil':
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php';
    break;
case 'etatFrais':
    include 'controleurs/c_etatFrais.php';
    break;
case 'validerfrais':
    include'controleurs/c_validerFrais.php';
    break;
case 'suivrepaiement':
    include 'controleurs/c_suivrePaiement.php';
    break;
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
}
require 'vues/v_pied.php';
