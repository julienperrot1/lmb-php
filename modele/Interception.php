<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une action de type interception lors d'un match
*/
class Interception extends Objet
{
	const TYPE 		= "Interception";
	const TABLE 	= "monobasket_interception";
	const CHAMPS 	= "action_id";
}

?>