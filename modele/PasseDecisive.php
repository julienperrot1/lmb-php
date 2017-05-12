<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une action de type passe décisive lors d'un match
*/
class PasseDecisive extends Objet
{
	const TYPE 		= "PasseDecisive";
	const TABLE 	= "monobasket_passe_decisive";
	const CHAMPS 	= "action_id";
}

?>