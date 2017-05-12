<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Tournoi.php');

?>

<SCRIPT src="ajax/tournoi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			<?php
			
			$tournoi = Tournoi::recup($_GET["id"]);
			print ($tournoi->get("libelle"));
			print ("<INPUT id=\"tournoi.id\" type=\"hidden\" value=\"" . $_GET["id"] . "\">");
			print ("<INPUT id=\"ligue.id\" type=\"hidden\" value=\"" . $tournoi->get("ligue_id") . "\">");
			
			?>
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php

			print ("<DIV id=\"tournoi.phases.liste\" class=\"texte\"></DIV>");
			print ("<DIV id=\"tournoi.phases.message\" class=\"texte\"></DIV>");
			print ("<BR/>");
			
			print ("<DIV id=\"tournoi.classement\" class=\"texte\"></DIV>");
			print ("<DIV id=\"tournoi.classement.message\" class=\"texte\"></DIV>");
			print ("<BR/>");
			
			print ("<DIV id=\"tournoi.equipes.liste\" class=\"texte\"></DIV>");
			print ("<DIV id=\"tournoi.equipes.message\" class=\"texte\"></DIV>");
			print ("<BR/>");
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.creation.phase\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creePhase();\">Création d'une nouvelle phase pour ce tournoi</DIV>");	
				
				print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.modifie.tournoi\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieTournoi();\">Modification du tournoi</DIV>");	
			}
			
			print ("<DIV class=\"champ_a_cliquer\" id=\"tournoi.retour.ligue\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourLigue();\">Retour à la liste des tournois</DIV>");	
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>


<!-- Chargement des diverses parties de la page -->
<SCRIPT type="text/javascript">chargeListePhases();</SCRIPT>
<SCRIPT type="text/javascript">chargeClassement();</SCRIPT>
<SCRIPT type="text/javascript">chargeListeEquipes();</SCRIPT>
