<?php

$RACINE = "../";
	
include_once ($RACINE . 'utils/Bdd.php');
include_once ($RACINE . 'utils/load_session.php');
include_once ($RACINE . 'config.php');

$BDD = new Bdd();

foreach ($_POST as $index => $valeur)
{
	$_POST[$index] = str_replace('\'', '\'\'', $valeur);
}

?>