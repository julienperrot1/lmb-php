<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/ClassementTournoi.php');
include_once ($RACINE . 'utils/Regle.php');

?>

<SCRIPT src="ajax/classement_tournoi.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'un classement de tournoi
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("<INPUT id=\"classement_tournoi.creamodi.tournoi_id\" type=\"hidden\" value=\"" . $_GET["tournoiId"] . "\">");
			
			$tournoi = Tournoi::recup($_GET["tournoiId"]);
			
			$classement_tournoi = null;
			$classement_tournoi_place = ClassementTournoi::prochainePlace($_GET["tournoiId"]);
			$classement_tournoi_regle_equipe = "";
			$classement_tournoi_points = null;
			
			if (isset($_GET["id"]))
			{
				$classement_tournoi = ClassementTournoi::recup($_GET["id"]);
				$classement_tournoi_place = $classement_tournoi->get("place");
				$classement_tournoi_regle_equipe = $classement_tournoi->get("regle_equipe");
				$classement_tournoi_points = $classement_tournoi->get("points");
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("Place : ");
				print ("<INPUT class=\"champTexte\" id=\"classement_tournoi.creamodi.place\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"" . $classement_tournoi_place . "\">");	
				print ("<BR/>");
				
				print ("Régle : ");
				$selecteur_regle = Regle::recupSelecteurRegle($tournoi, null, $classement_tournoi_regle_equipe);				
				if ($selecteur_regle)
				{
					print(
						  "<SELECT id=\"classement_tournoi.creamodi.regle_equipe\" value=\"" . $classement_tournoi_regle_equipe . "\">"
						. $selecteur_regle
						. "</SELECT><BR/>");
				}
				else
				{
					print ("Aucune régle disponible pour ce tournoi");
				}
				print ("<BR/>");
				
				if ($classement_tournoi_points)
				{
					print ("Points : ");
					print ("<INPUT class=\"champTexte\" id=\"classement_tournoi.creamodi.points\" type=\"text\" maxlength=\"10\" size=\"5\" value=\"" . $classement_tournoi_points . "\">");	
					print ("<BR/>");
				}
													
				if ($classement_tournoi)
				{
					print ("<INPUT id=\"classement_tournoi.creamodi.id\" type=\"hidden\" value=\"" . $classement_tournoi->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieClassementTournoi();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeClassementTournoi();\">Créer le classement</DIV>");	
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
				
				print ("<DIV id=\"classement_tournoi.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($classement_tournoi)
				{
					print ("<DIV id=\"classement_tournoi.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer un classement de tournoi</DIV>");
				}
				else
				{
					print ("<DIV id=\"classement_tournoi.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer un nouveau classement de tournoi</DIV>");
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
			}
			
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
