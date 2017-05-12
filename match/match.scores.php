<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');


if (isset($_POST["matchId"]) && isset($_POST["tempsDeJeuId"]))
{
	$match = Match::recup($_POST["matchId"]);
	if ($match->get("score1"))
	{
		$scoreFormation1 = $match->get("score1");
	}
	else if ($match->get("formation1_id") != null && $match->get("formation1_id") > 0)
	{
		$scoreFormation1 = $match->recupScoreFormation($match->get("formation1_id"));
	}
	else
	{
		$scoreFormation1 = "-";
	}
	
	if ($match->get("score2"))
	{
		$scoreFormation2 = $match->get("score2");
	}
	else if ($match->get("formation2_id") != null && $match->get("formation2_id") > 0)
	{
		$scoreFormation2 = $match->recupScoreFormation($match->get("formation2_id"));
	}
	else
	{
		$scoreFormation2 = "-";
	}
		
	$temps_de_jeu = TempsDeJeu::recup($_POST["tempsDeJeuId"]);
	
	if ($match->get("formation1_id") != null && $match->get("formation1_id") > 0)
	{
		$fautesFormation1 = $temps_de_jeu->recupNbFautesFormation($match->get("formation1_id"));
		$formation1 = Formation::recup($match->get("formation1_id"));
		$equipe1 = Equipe::recup($formation1->get("equipe_id"));
		$couleur_base1 = $equipe1->get("couleur_base");
	}
	else
	{
		$equipe1 = null;
		$fautesFormation1 = "-";
		$couleur_base1 = "000000";
	}
	
	if ($match->get("formation2_id") != null && $match->get("formation2_id") > 0)
	{
		$fautesFormation2 = $temps_de_jeu->recupNbFautesFormation($match->get("formation2_id"));
		$formation2 = Formation::recup($match->get("formation2_id"));
		$equipe2 = Equipe::recup($formation2->get("equipe_id"));
		$couleur_base2 = $equipe2->get("couleur_base");
	}
	else
	{
		$equipe2 = null;
		$fautesFormation2 = "-";
		$couleur_base2 = "000000";
	}
	
	print ("<DIV id=\"match.scores.actuel\">");
	print ("Score : <B><FONT color=\"#" . $couleur_base1 . "\">" . $scoreFormation1 . "</FONT> - <FONT color=\"#" . $couleur_base2 . "\">" . $scoreFormation2 . "</FONT></B>");
	print ("<BR/>");
	
	print ("Fautes du temps de jeu : <B><FONT color=\"#" . $couleur_base1 . "\">" . $fautesFormation1 . "</FONT> - <FONT color=\"#" . $couleur_base2 . "\">" . $fautesFormation2 . "</FONT></B>");
	print ("<BR/>");
	print ("<BR/>");
	
	
	if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
	{
		if ($match->get("resultat") == $MATCH_RESULTAT_AJOUER)
		{
			print ("<DIV class=\"champ_a_cliquer\" id=\"match.validation.match.affiche\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheValideMatch();\">Valider le match</DIV>");	 
			print ("</DIV>");
		}
		else
		{	
			print ("<DIV class=\"champ_a_cliquer\" id=\"match.validation.match.affiche\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheValideMatch();\">Modifier le résultat</DIV>");	 
			print ("</DIV>");
		}
		
		
		if ($equipe1 && $equipe2)
		{
			print ("<DIV id=\"match.validation.match\" hidden>");
			print ("Score : ");
			print ("<INPUT class=\"champTexte\" id=\"match.validation.score1\" type=\"text\" maxlength=\"3\" size=\"2\" value=\"" . $scoreFormation1 . "\">");
			print (" - ");
			print ("<INPUT class=\"champTexte\" id=\"match.validation.score2\" type=\"text\" maxlength=\"3\" size=\"2\" value=\"" . $scoreFormation2 . "\">");
			print ("<BR/>");

			print ("Vainqueur : ");
			print ("<INPUT id=\"match.validation.vainqueur.equipe1\" name=\"match.creamodi.vainqueur\" type=\"radio\"/");
			if ($scoreFormation1 > $scoreFormation2)
			{
				print (" checked");
			}
			print ("> <B><FONT color=\"#" . $couleur_base1 . "\">" . $equipe1->get("nom") . "</FONT></B> ");
			print ("<INPUT id=\"match.validation.vainqueur.equipe2\" name=\"match.creamodi.vainqueur\" type=\"radio\"/");
			if ($scoreFormation1 < $scoreFormation2)
			{
				print (" checked");
			}
			print ("> <B><FONT color=\"#" . $couleur_base2 . "\">" . $equipe2->get("nom") . "</FONT></B> ");
			print ("<INPUT id=\"match.validation.vainqueur.nul\" name=\"match.creamodi.vainqueur\" type=\"radio\"/");
			if ($scoreFormation1 == $scoreFormation2)
			{
				print (" checked");
			}
			print ("> Match nul");
			print ("<BR/>");
			print ("<BR/>");
				
			print ("<DIV class=\"champ_a_cliquer\" id=\"match.validation.match.valide\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"valideMatch();\">OK</DIV>");	
			print ("<DIV class=\"champ_a_cliquer\" id=\"match.validation.match.annule\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"annuleValideMatch();\">Annuler</DIV>");
			
			print ("</DIV>");
		}
	}
}
else
{
	print ("<DIV class=\"messageErreur\" >Erreur lors du chargement du score</DIV>");
}

?>
	