<?php

include_once 'menu.php';

?>

<SCRIPT src="ajax/ligues.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Les ligues / matchs
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("Chercher une ligue : ");
			print ("<INPUT class=\"champTexte\" id=\"ligues.recherche.ligue\" type=\"text\" maxlength=\"255\" size=\"8\" oninput=\"chargeListeLigues();\">");	
			print ("<BR/>");

			print ("<DIV id=\"ligues.liste\" class=\"texte\"></DIV>");
			print ("<DIV id=\"ligues.message\" class=\"texte\"></DIV>");
			print ("<BR/>");
						
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"ligues.creation.ligue\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeLigue();\">Création d'une nouvelle ligue</DIV>");
			}
		
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>


<!-- Chargement des diverses parties de la page -->
<SCRIPT type="text/javascript">chargeListeLigues();</SCRIPT>
