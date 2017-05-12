<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');

?>

<SCRIPT src="ajax/match.saisie.rapide.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Saisie rapide des résultats d'un match
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("<INPUT id=\"match.saisie.rapide.match_id\" type=\"hidden\" value=\"" . $_GET["id"] . "\">");
			if (isset($_GET["pouleId"]))
			{
				print ("<INPUT id=\"match.saisie.rapide.poule_id\" type=\"hidden\" value=\"" . $_GET["pouleId"] . "\">");
			}
			if (isset($_GET["tournoiId"]))
			{
				print ("<INPUT id=\"match.saisie.rapide.tournoi_id\" type=\"hidden\" value=\"" . $_GET["tournoiId"] . "\">");
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				$match = Match::recup($_GET["id"]);
				$formation1 = Formation::recup($match->get("formation1_id"));
				$formation2 = Formation::recup($match->get("formation2_id"));
				$equipe1 = Equipe::recup($formation1->get("equipe_id"));
				$equipe2 = Equipe::recup($formation2->get("equipe_id"));
				$temps_de_jeux = TempsDeJeu::recupParChamp("match_id", $_GET["id"], "ordre_temporel ASC");
				
				print ("<TABLE class=\"tableau_recherche\">");
				
				print ("<TR class=\"tableau_recherche_tr\">");
				print ("<TD></TD>");
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					print ("<TD class=\"tableau_recherche_th\">" . $temps_de_jeu->get("libelle") . "</TD>");
				}
				print ("</TR>");
				
				print ("<TR class=\"tableau_recherche_tr\">");
				print ("<TD class=\"tableau_recherche_td\">Paniers <B><FONT color=\"#" . $equipe1->get("couleur_base") . "\">" . $equipe1->get("nom") . "</FONT></B></TD>");
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					print ("<TD class=\"tableau_recherche_td\"><INPUT class=\"champTexte\" id=\"match.saisie.rapide.points1." . $temps_de_jeu->get("ordre_temporel") . "\" type=\"text\" size=\"40\">");	
				}
				print ("</TR>");
				
				print ("<TR class=\"tableau_recherche_tr\">");
				print ("<TD class=\"tableau_recherche_td\">Paniers <B><FONT color=\"#" . $equipe2->get("couleur_base") . "\">" . $equipe2->get("nom") . "</FONT></B></TD>");
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					print ("<TD class=\"tableau_recherche_td\"><INPUT class=\"champTexte\" id=\"match.saisie.rapide.points2." . $temps_de_jeu->get("ordre_temporel") . "\" type=\"text\" size=\"40\">");	
				}
				print ("</TR>");
				
				print ("<TR class=\"tableau_recherche_tr\">");
				print ("<TD class=\"tableau_recherche_td\">Fautes <B><FONT color=\"#" . $equipe1->get("couleur_base") . "\">" . $equipe1->get("nom") . "</FONT></B></TD>");
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					print ("<TD class=\"tableau_recherche_td\"><INPUT class=\"champTexte\" id=\"match.saisie.rapide.fautes1." . $temps_de_jeu->get("ordre_temporel") . "\" type=\"text\" size=\"40\">");	
				}
				print ("</TR>");
				
				print ("<TR class=\"tableau_recherche_tr\">");
				print ("<TD class=\"tableau_recherche_td\">Fautes <B><FONT color=\"#" . $equipe2->get("couleur_base") . "\">" . $equipe2->get("nom") . "</FONT></B></TD>");
				foreach ($temps_de_jeux as $temps_de_jeu)
				{
					print ("<TD class=\"tableau_recherche_td\"><INPUT class=\"champTexte\" id=\"match.saisie.rapide.fautes2." . $temps_de_jeu->get("ordre_temporel") . "\" type=\"text\" size=\"40\">");	
				}
				print ("</TR>");
				
				print ("</TABLE>");			
			
				print ("<DIV id=\"match.saisie.rapide.message\" class=\"texte\"></DIV>");
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"valideSaisie();\">Valider la saisie</DIV>");	
			}
			else
			{
				print ("<DIV id=\"match.saisie.rapide.message\" class=\"messageErreur\">Vous n'avez pas les droits pour saisir les résultats d'un match</DIV>");
			}
			
			print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourMatch();\">Annuler</DIV>");	
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
