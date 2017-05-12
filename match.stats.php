<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');

?>

<SCRIPT src="ajax/match.stats.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			<?php
			
			$match = Match::recup($_GET["id"]);
			print ("Statistiques - " . $match->get("libelle") . " (" . $match->get("date") . ")");
			
			?>
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("<INPUT id=\"match.stats.match_id\" type=\"hidden\" value=\"" . $_GET["id"] . "\">");
			if (isset($_GET["pouleId"]))
			{
				print ("<INPUT id=\"match.stats.poule_id\" type=\"hidden\" value=\"" . $_GET["pouleId"] . "\">");
			}
			if (isset($_GET["tournoiId"]))
			{
				print ("<INPUT id=\"match.stats.tournoi_id\" type=\"hidden\" value=\"" . $_GET["tournoiId"] . "\">");
			}
			
			$temps_de_jeux = TempsDeJeu::recupParChamp("match_id", $_GET["id"], "ordre_temporel ASC");
			print ("Stats du temps de jeu : <SELECT id=\"match.stats.tempsDeJeu\" onchange=\"activeModeCalcul(); chargeFormation1(); chargeFormation2()\">");
			print ("<OPTION value=\"-1\" selected=\"true\">Match complet</OPTION>");
			if ($temps_de_jeux)
			{
				foreach($temps_de_jeux as $temps_de_jeu)
				{			
					print ("<OPTION value=\"" . $temps_de_jeu->get("id") . "\">" . $temps_de_jeu->get("libelle") . "</OPTION>");
				}
			}
			print ("</SELECT>");
			print ("<BR/>");
			
			print ("Mode de calcul : <SELECT id=\"match.stats.modeCalcul\" onchange=\"chargeFormation1(); chargeFormation2()\">");
			print ("<OPTION value=\"-1\" selected=\"true\">Statistiques réelles</OPTION>");
			print ("<OPTION value=\"2400\">Equivalence match de 40 minutes</OPTION>");
			print ("</SELECT>");
			print ("<BR/>");
			print ("<BR/>");
			
			$formation1 = Formation::recup($match->get("formation1_id"));
			$formation2 = Formation::recup($match->get("formation2_id"));
			$equipe1 = Equipe::recup($formation1->get("equipe_id"));
			$equipe2 = Equipe::recup($formation2->get("equipe_id"));
			
			print ("<INPUT id=\"match.stats.formation1.id\" type=\"hidden\" value=\"" . $formation1->get("id") . "\">");
			print ("<INPUT id=\"match.stats.formation2.id\" type=\"hidden\" value=\"" . $formation2->get("id") . "\">");
			
			print ("<A href=\"equipe.php?id=" . $equipe1->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe1->get("couleur_base") . "\">" . $equipe1->get("nom") . "</FONT></B></A>");
			print ("<DIV id=\"match.stats.formation1\" class=\"texte\"></DIV>");
			print ("<BR/>");
			
			print ("<A href=\"equipe.php?id=" . $equipe2->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe2->get("couleur_base") . "\">" . $equipe2->get("nom") . "</FONT></B></A>");
			print ("<DIV id=\"match.stats.formation2\" class=\"texte\"></DIV>");
			print ("<BR/>");
		
			print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourMatch();\">Annuler</DIV>");	
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>

<SCRIPT type="text/javascript">chargeFormation1();</SCRIPT>
<SCRIPT type="text/javascript">chargeFormation2();</SCRIPT>