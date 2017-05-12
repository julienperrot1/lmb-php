<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une action de type rebond lors d'un match
*/
class Rebond extends Objet
{
	const TYPE 		= "Rebond";
	const TABLE 	= "monobasket_rebond";
	const CHAMPS 	= "action_id,type";
}

?>