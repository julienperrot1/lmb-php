<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Ligue.php');

?>

<SCRIPT src="ajax/ligue.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'une ligue
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			$ligue = null;
			$ligue_libelle = "";
			$ligue_type = null;
			$ligue_nb_tournoi_class = 0;
			if (isset($_GET["id"]))
			{
				$ligue = Ligue::recup($_GET["id"]);
				$ligue_libelle = $ligue->get("libelle");
				$ligue_type = $ligue->get("type");
				$ligue_nb_tournoi_class = $ligue->get("nb_tournoi_class");
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{	
				print ("Libellé de la ligue : ");
				print ("<INPUT class=\"champTexte\" id=\"ligue.creamodi.libelle\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $ligue_libelle . "\">");	
				print ("<BR/>");

				print ("Type de ligue : ");
				print ("<SELECT id=\"ligue.creamodi.type\" STYLE=\"width:50%\">");
				foreach($LIGUE_TYPE_DESC as $type_index => $type_desc)
				{			
					print ("<OPTION value=\"" . $type_index . "\"");
					if ($ligue_type && $ligue_type == $type_index)
					{
						print (" selected=\"true\"");
					}
					print (">" . $type_desc . "</OPTION>");
				}
				print ("</SELECT>");
				print ("<BR/>");
				
				print ("Nombre de tournois comptants pour le classement, sur la totalité : ");
				print ("<INPUT class=\"champTexte\" id=\"ligue.creamodi.nb_tournoi_class\" type=\"text\" maxlength=\"2\" size=\"5\" value=\"" . $ligue_nb_tournoi_class . "\">");	
				print ("<BR/>");
				
				if ($ligue)
				{
					print ("<INPUT id=\"ligue.creamodi.id\" type=\"hidden\" value=\"" . $ligue->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieLigue();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeLigue();\">Créer la ligue</DIV>");	
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourLigues();\">Annuler</DIV>");	
								
				print ("<DIV id=\"ligue.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($ligue)
				{
					print ("<DIV id=\"ligue.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer une ligue</DIV>");
				}
				else
				{
					print ("<DIV id=\"ligue.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer une nouvelle ligue</DIV>");
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourLigues();\">Annuler</DIV>");	
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
