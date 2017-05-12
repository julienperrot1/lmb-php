<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/TempsDeJeu.php');


if (isset($_POST["tempsDeJeuId"]))
{
	$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
	$match = Match::recup($temps_de_jeu->get("match_id"));
	$temps_restant = $temps_de_jeu->get("temps_restant");
	
	if (isset($_POST["tempsADiminuer"]))
	{
		$temps_restant = $temps_restant - $_POST["tempsADiminuer"];
		if ($temps_restant < 0)
		{
			$temps_restant = 0;
		}
		
		if (! $temps_de_jeu->set("temps_restant", $temps_restant))
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de la mise à jour du temps restant</DIV>");
		}
	}
	
	$minutes_restantes = floor($temps_restant / 60);
	$secondes_restantes = $temps_restant - ($minutes_restantes * 60);
	
	print ("Chronomètre : ");
	print ("<INPUT class=\"champTexte\" id=\"match.chronometre.minutes\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . sprintf("%02d", $minutes_restantes) . "\"> : ");
	print ("<INPUT class=\"champTexte\" id=\"match.chronometre.secondes\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . sprintf("%02d", $secondes_restantes) . "\">");
}
else
{
	print ("<DIV class=\"messageErreur\" >Erreur lors du chargement du chronomètre</DIV>");
}

?>
	