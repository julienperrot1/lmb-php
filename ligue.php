<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Ligue.php');
include_once ($RACINE . 'utils/Tableur.php');

?>

<SCRIPT src="ajax/ligue.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			<?php
			
			$ligue = Ligue::recup($_GET["id"]);
			print ($ligue->get("libelle"));
			print ("<INPUT id=\"ligue.id\" type=\"hidden\" value=\"" . $_GET["id"] . "\">");
			
			?>
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php

			print ("<DIV class=\"soustitre\">Tournois</DIV>");
			print ("<BR/>");
			
			print ("<DIV id=\"tournois.liste\" class=\"texte\"></DIV>");
			print ("<DIV id=\"ligue.tournois.message\" class=\"texte\"></DIV>");
			print ("<BR/>");
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"ligue.creation.tournoi\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeTournoi();\">Création d'un nouveau tournoi pour cette ligue</DIV>");	
			}
			
			print ("<BR/>");
			

			print ("<DIV class=\"soustitre\">Classement</DIV>");
			print ("<BR/>");
			
			$tournois = Tournoi::recupParChamp("ligue_id", $ligue->get("id"), $tri = "id ASC");
			$classement_ligue = $ligue->getClassement();
			if ($tournois && $classement_ligue)
			{
				$classe_table = "tableau_recherche";
				$classe_tr = "tableau_recherche_tr";
				$classe_th = "tableau_recherche_th";
				$classe_td = "tableau_recherche_td";
				
				print ("<TABLE class=\"" . $classe_table . "\">");

				print ("<TR class=\"" . $classe_tr . "\">");
				print ("<TH class=\"" . $classe_th . "\">Place</TH>");
				print ("<TH class=\"" . $classe_th . "\">Equipe</TH>");
				foreach($tournois as $tournoi)
				{	
					print ("<TH class=\"" . $classe_th . "\">" . $tournoi->get("libelle") . "</TH>");
				}
				print ("<TH class=\"" . $classe_th . "\">Total</TH>");
				print ("</TR>");
				
				foreach($classement_ligue as $classement)
				{
					$equipe = Equipe::recup($classement["equipe_id"]); 
					print ("<TR class=\"" . $classe_tr . "\">");
					print ("<TD class=\"" . $classe_td . "\">" . $classement["classement"] . "</TD>");
					print ("<TD class=\"" . $classe_td . "\"><A href=\"equipe.php?id=" . $equipe->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B></A></TD>");
					foreach($tournois as $tournoi)
					{	
						$classement_tournoi = ClassementTournoi::recupParTournoiEtEquipe($tournoi->get("id"), $classement["equipe_id"]);
						if ($classement_tournoi)
						{
							$valeur = $classement_tournoi->get("points");
						}
						else
						{
							$valeur = "-";
						}
						print ("<TD class=\"" . $classe_td . "\">" . $valeur . "</TD>");
					}
					print ("<TD class=\"" . $classe_td . "\">" . $classement["points"] . "</TD>");
					print ("</TR>");
				}
				
				print ("</TABLE>");
			}
			else
			{
				print ("<DIV class=\"texte\">Aucun classement pour l'instant</DIV>");
			}
			print ("<BR/>");
			
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"ligue.modifie.ligue\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieLigue(" . $_GET["id"] . ");\">Modification de la ligue</DIV>");	
			}
			print ("<DIV class=\"champ_a_cliquer\" id=\"ligue.retour.ligues\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourLigues();\">Retour à la liste des ligues</DIV>");	
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>


<!-- Chargement des diverses parties de la page -->
<SCRIPT type="text/javascript">chargeListeTournois();</SCRIPT>
