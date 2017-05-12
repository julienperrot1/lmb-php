<?php

session_start(); 

$BDD = new Bdd();

include_once ($RACINE . 'modele/Joueur.php');

if (isset($_SESSION['utilisateur_id']))
{
	$utilisateur_en_cours = Joueur::recup($_SESSION['utilisateur_id']);
}

?>