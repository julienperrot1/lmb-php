<?php

global $RACINE;

include_once ($RACINE . 'modele/Objet.php');
	
/*
  Représente une action de type contre lors d'un match
*/
class Contre extends Objet
{
	const TYPE 		= "Contre";
	const TABLE 	= "monobasket_contre";
	const CHAMPS 	= "action_id";
}

?>