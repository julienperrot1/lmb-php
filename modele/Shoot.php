<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une action de type shoot lors d'un match
*/
class Shoot extends Objet
{
	const TYPE 		= "Shoot";
	const TABLE 	= "monobasket_shoot";
	const CHAMPS 	= "action_id,type,reussi";
}

?>