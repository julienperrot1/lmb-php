<?php

include_once 'menu.php';

?>

<SCRIPT src="ajax/joueurs.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Les joueurs
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("Chercher un joueur : ");
			print ("<INPUT class=\"champTexte\" id=\"joueurs.recherche.joueur\" type=\"text\" maxlength=\"255\" size=\"8\" oninput=\"chargeListeJoueurs();\">");	
			print ("<BR/>");

			print ("<DIV id=\"joueurs.liste\" class=\"texte\"></DIV>");
			print ("<DIV id=\"joueurs.message\" class=\"texte\"></DIV>");
			print ("<BR/>");
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"joueurs.creation.joueur\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeJoueur();\">Ajout d'un nouveau joueur</DIV>");	
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>


<!-- Chargement des diverses parties de la page -->
<SCRIPT type="text/javascript">chargeListeJoueurs();</SCRIPT>
