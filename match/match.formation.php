<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'utils/Tableur.php');
include_once ($RACINE . 'utils/Regle.php');


if (isset($_POST["formationId"]) && $_POST["formationId"] > 0)
{
	$formation = Formation::recup($_POST["formationId"]);
	$equipe1 = Equipe::recup($formation->get("equipe_id"));
	$formation_joueurs = $formation->getFormationJoueurs();
	$match = Match::recup($formation->get("match_id"));
	
	print ("<A href=\"equipe.php?id=" . $equipe1->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe1->get("couleur_base") . "\">" . $equipe1->get("nom") . "</FONT></B></A>");
	print ("<BR/>");
	
	if ($formation_joueurs)
	{
		print ("<TABLE>");
		foreach ($formation_joueurs as $formation_joueur)
		{
			$joueur = Joueur::recup($formation_joueur["joueur_id"]);
			
			print ("<TR>");
						
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{	
				print ("<TD><IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieNumeroJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . ");\" src=\"images/modification.jpg\"></IMG></TD>");
				print ("<TD><DIV title=\"" . $joueur->get("prenom") . " " . $joueur->get("nom") . "\" class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . ");\">" . $formation_joueur["numero"] . "</DIV></TD>");
				print ("<TD><DIV title=\"" . $joueur->get("prenom") . " " . $joueur->get("nom") . "\" class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . ");\">" . $joueur->get("pseudo") . "</DIV></TD>");
				print ("<TD><DIV class=\"champ_a_cliquer_ok_petit\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . "); choixAction(" . $ACTION_TYPE_SHOOT . ", 2, 1);\">2</DIV></TD>");
				print ("<TD><DIV class=\"champ_a_cliquer_nok_petit\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . "); choixAction(" . $ACTION_TYPE_SHOOT . ", 2, 0);\">2</DIV></TD>");
				print ("<TD><DIV class=\"champ_a_cliquer_nok_petit\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . "); choixJoueur(0, 0); choixAction(" . $ACTION_TYPE_FAUTE . ", 'P', null);\">F</DIV></TD>");	
				print ("<TD><DIV class=\"champ_a_cliquer_ok_petit\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . "); choixAction(" . $ACTION_TYPE_REBOND . ", 1, null);\">O</DIV></TD>");		
				print ("<TD><DIV class=\"champ_a_cliquer_ok_petit\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"choixJoueur(" . $_POST["formationId"] . ", " . $joueur->get("id") . "); choixAction(" . $ACTION_TYPE_REBOND . ", 2, null);\">D</DIV></TD>");	
			}
			else
			{
				print ("<TD>" . $formation_joueur["numero"] . " " . $joueur->get("pseudo") . "</TD>");
			}
			
			print ("<TD>");
			for ($i = 0; $i < $match->recupNbFautesJoueur($joueur->get("id")); $i++)
			{
				print ("<IMG class=\"image_symbole\" src=\"images/point_rouge.jpg\"></IMG>");
			}
			print ("</TD>");
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<TD><IMG class=\"image_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"enleveJoueurAFormation(" . $joueur->get("id") . ", " . $_POST["formationId"] . ");\" src=\"images/suppression.jpg\"></IMG></TD>");
			}
			
			print ("</TR>");
			
		}
		print ("</TABLE>");
	}
	else
	{
		print ("Aucun joueur n'est inscrit dans cette formation pour l'instant");
	}
	print ("<BR/>");
					
	if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
	{
		print ("Chercher un joueur à ajouter : ");
		print ("<INPUT class=\"champTexte\" id=\"match.formation" . $_POST["formationId"] . ".recherche.joueur\" type=\"text\" maxlength=\"255\" size=\"8\" oninput=\"rechercheJoueur(" . $_POST["formationId"] . ");\">");	
		print ("<BR/>");

		print ("<DIV id=\"match.formation" . $_POST["formationId"] . ".recherche.resultat\" class=\"texte\"></DIV>");
		print ("<BR/>");
		
		print ("<DIV class=\"champ_a_cliquer\" id=\"match.formation.duplique.phase\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"dupliqueFormationPhase(" . $_POST["formationId"] . ");\">Duplique la formation</DIV>");			
		print ("<BR/>");
		
		print ("<DIV id=\"match.formation" . $_POST["formationId"] . ".duplique.phase.resultat\" class=\"texte\"></DIV>");
		print ("<BR/>");
	}
}
else if (isset($_POST["regle"]) && $_POST["regle"] != "null" && $_POST["regle"] != -1)
{
	print ("<BR/>");
	print ("Régle :");
	print ("<BR/>");
	print (Regle::versString($_POST["regle"]));
	print ("<BR/>");
}
else
{
	print ("Aucune équipe ni aucune régle n'a été selectionnée pour ce match");
}

?>
	