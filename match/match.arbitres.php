<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'utils/Tableur.php');


if (isset($_POST["matchId"])) {
	$match = Match::recup($_POST["matchId"]);
	$arbitre1 = Joueur::recup($match->get("arbitre1_id"));
	$arbitre2 = Joueur::recup($match->get("arbitre2_id"));
	
	print ("Arbitre 1 : ");
	if ($arbitre1)
	{	
		print ($arbitre1->get("pseudo"));
		if (isset($utilisateur_en_cours))
		{
			print (" (" . $arbitre1->get("prenom") . " " . $arbitre1->get("nom") . ")");
		}
		if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
		{
			print ("<TD><IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveArbitre(1);\" src=\"images/suppression.jpg\"></IMG></TD>");
		}	
	}
	else
	{
		print ("Non défini");
	}
	print ("<BR/>");

	print ("Arbitre 2 : ");
	if ($arbitre2)
	{	
		print ($arbitre2->get("pseudo"));
		if (isset($utilisateur_en_cours))
		{
			print (" (" . $arbitre2->get("prenom") . " " . $arbitre2->get("nom") . ")");
		}
		if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
		
		{
			print ("<TD><IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveArbitre(2);\" src=\"images/suppression.jpg\"></IMG></TD>");
		}
		
		print ("<BR/>");
	}
	else
	{
		print ("Non défini");
		print ("<BR/>");

		if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
		{
			print ("Chercher un arbitre : ");
			print ("<INPUT class=\"champTexte\" id=\"match.arbitres.recherche.arbitre\" type=\"text\" maxlength=\"255\" size=\"8\" oninput=\"rechercheArbitre();\">");	
			print ("<BR/>");
		}
	}
	
	print ("<DIV id=\"match.arbitres.recherche.resultat\" class=\"texte\"></DIV>");
	print ("<BR/>");
}
else
{
	print ("<DIV class=\"messageErreur\" >Erreur lors du chargement de la DIV arbitres</DIV>");
}

?>
	