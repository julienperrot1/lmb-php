<?php

include_once 'menu.php';

?>

<SCRIPT src="ajax/equipes.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Les équipes
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("Chercher une équipe : ");
			print ("<INPUT class=\"champTexte\" id=\"equipes.recherche.equipe\" type=\"text\" maxlength=\"255\" size=\"8\" oninput=\"chargeListeEquipes();\">");	
			print ("<BR/>");

			print ("<DIV id=\"equipes.liste\" class=\"texte\"></DIV>");
			print ("<DIV id=\"equipes.message\" class=\"texte\"></DIV>");
			print ("<BR/>");
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"equipes.creation.equipe\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeEquipe();\">Création d'une nouvelle équipe</DIV>");
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>


<!-- Chargement des diverses parties de la page -->
<SCRIPT type="text/javascript">chargeListeEquipes();</SCRIPT>
