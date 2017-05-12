<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/Tournoi.php');

?>

<SCRIPT src="ajax/poule.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'une poule
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("<INPUT id=\"poule.creamodi.tournoi_id\" type=\"hidden\" value=\"" . $_GET["tournoiId"] . "\">");
			print ("<INPUT id=\"poule.creamodi.phase_poules_id\" type=\"hidden\" value=\"" . $_GET["phasePoulesId"] . "\">");
			
			$poule = null;
			$poule_libelle = "";
			$poule_points_victoire = $POINTS_VICTOIRE_DEFAUT;
			$poule_points_defaite = $POINTS_DEFAITE_DEFAUT;
			$poule_points_nul = $POINTS_NUL_DEFAUT;
			$poule_goal_average_ecart_max = $GOAL_AVERAGE_ECART_MAX_DEFAUT;
			$poule_etat = 1;
			if (isset($_GET["id"]))
			{
				$poule = Poule::recup($_GET["id"]);
				$poule_libelle = $poule->get("libelle");
				$poule_points_victoire = $poule->get("points_victoire");
				$poule_points_defaite = $poule->get("points_defaite");
				$poule_points_nul = $poule->get("points_nul");
				$poule_goal_average_ecart_max = $poule->get("goal_average_ecart_max");
				$poule_etat = $poule->get("etat");
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("Libelle : ");
				print ("<INPUT class=\"champTexte\" id=\"poule.creamodi.libelle\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $poule_libelle . "\">");	
				print ("<BR/>");
				
				print ("Nombre de points rapportés par une victoire : ");
				print ("<INPUT class=\"champTexte\" id=\"poule.creamodi.points_victoire\" type=\"text\" maxlength=\"4\" size=\"5\" value=\"" . $poule_points_victoire . "\">");	
				print ("<BR/>");
				
				print ("Nombre de points rapportés par une défaite : ");
				print ("<INPUT class=\"champTexte\" id=\"poule.creamodi.points_defaite\" type=\"text\" maxlength=\"4\" size=\"5\" value=\"" . $poule_points_defaite . "\">");	
				print ("<BR/>");
				
				print ("Nombre de points rapportés par un match nul : ");
				print ("<INPUT class=\"champTexte\" id=\"poule.creamodi.points_nul\" type=\"text\" maxlength=\"4\" size=\"5\" value=\"" . $poule_points_nul . "\">");	
				print ("<BR/>");
				
				print ("Nombre de points d'écart maximum comptabilisés lors du goal average : ");
				print ("<INPUT class=\"champTexte\" id=\"poule.creamodi.goal_average_ecart_max\" type=\"text\" maxlength=\"4\" size=\"5\" value=\"" . $poule_goal_average_ecart_max . "\">");	
				print ("<BR/>");

				print ("Etat actuel : ");
				foreach ($ETAT_DESC as $etat => $etat_desc)
				{
					print ("<INPUT id=\"poule.creamodi.etat." . $etat . "\" name=\"poule.creamodi.etat\" type=\"radio\" value=\"" . $etat . "\"");
					if ($poule_etat == $etat)
					{
						print (" checked");
					}
					print ("> " . $etat_desc . " ");	
				}
				print ("<BR/>");
				
				if ($poule)
				{
					print ("<INPUT id=\"poule.creamodi.id\" type=\"hidden\" value=\"" . $poule->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifiePoule();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creePoule();\">Créer la poule</DIV>");	
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
								
				print ("<DIV id=\"poule.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($poule)
				{
					print ("<DIV id=\"poule.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer une poule</DIV>");
				}
				else
				{
					print ("<DIV id=\"poule.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer une nouvelle poule</DIV>");
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
