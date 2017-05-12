<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Equipe.php');


if (isset($_POST["tournoiId"]) && $_POST["tournoiId"] != "")
{
	$tournoi = Tournoi::recup($_POST["tournoiId"]);
	$tournoi_equipes = $tournoi->getEquipes();

	print ("<DIV class=\"soustitre\">Equipe(s) inscrite(s) au tournoi</DIV>");
	if ($tournoi_equipes)
	{
		foreach ($tournoi_equipes as $tournoi_equipe)
		{
			$equipe = Equipe::recup($tournoi_equipe["equipe_id"]);
			print ("<LI><A href=\"equipe.php?id=" . $equipe->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B></A>");
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print (" <IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveEquipe(" . $equipe->get("id") . ");\" src=\"images/suppression.jpg\"></IMG>");
			}
			
			print ("</LI>");;
		}
	}
	else
	{
		print ("Aucune équipe inscrite au tournoi");
	}
	print ("<BR/>");
	
	if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
	{
		print ("Chercher une équipe à ajouter : ");
		print ("<INPUT class=\"champTexte\" id=\"tournoi.equipes.recherche.equipe\" type=\"text\" maxlength=\"255\" size=\"8\" oninput=\"rechercheEquipe();\">");	
		print ("<BR/>");

		print ("<DIV id=\"tournoi.equipes.recherche.equipe.resultat\" class=\"texte\"></DIV>");
		print ("<BR/>");
	}
}

?>
	