<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/TempsDeJeu.php');

?>

<SCRIPT src="ajax/temps_de_jeu.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'un temps de jeu
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("<INPUT id=\"temps_de_jeu.creamodi.match_id\" type=\"hidden\" value=\"" . $_GET["matchId"] . "\">");
			if (isset($_GET["pouleId"]))
			{
				print ("<INPUT id=\"temps_de_jeu.creamodi.poule_id\" type=\"hidden\" value=\"" . $_GET["pouleId"] . "\">");
			}
			if (isset($_GET["tournoiId"]))
			{
				print ("<INPUT id=\"temps_de_jeu.creamodi.tournoi_id\" type=\"hidden\" value=\"" . $_GET["tournoiId"] . "\">");
			}
			
			$temps_de_jeu = null;
			$temps_de_jeu_libelle = "";
			$temps_de_jeu_duree_minutes = 10;
			$temps_de_jeu_duree_secondes = 0;
			$temps_de_jeu_match_id = $_GET["matchId"];

			if (isset($_GET["id"]))
			{
				$temps_de_jeu = TempsDeJeu::recup($_GET["id"]);
				$temps_de_jeu_libelle = $temps_de_jeu->get("libelle");
				$temps_de_jeu_duree = $temps_de_jeu->get("duree");
				$temps_de_jeu_duree_minutes = floor($temps_de_jeu_duree / 60);
				$temps_de_jeu_duree_secondes = $temps_de_jeu_duree - ($temps_de_jeu_duree_minutes * 60);
				$temps_de_jeu_match_id = $temps_de_jeu->get("matchId");
			}
			
			print ("Libellé : ");
			print ("<INPUT class=\"champTexte\" id=\"temps_de_jeu.creamodi.libelle\" type=\"text\" maxlength=\"255\" size=\"40\" value=\"" . $temps_de_jeu_libelle . "\">");	
			print ("<BR/>");
			
			print ("Durée du temps de jeu : ");
			print ("<INPUT class=\"champTexte\" id=\"temps_de_jeu.creamodi.duree_minutes\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"" . $temps_de_jeu_duree_minutes . "\">");	
			print (" min ");
			print ("<INPUT class=\"champTexte\" id=\"temps_de_jeu.creamodi.duree_secondes\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"" . $temps_de_jeu_duree_secondes . "\">");	
			print ("<BR/>");

			if ($temps_de_jeu)
			{
				print ("<INPUT id=\"temps_de_jeu.creamodi.id\" type=\"hidden\" value=\"" . $temps_de_jeu->get("id") . "\">");
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieTempsDeJeu();\">Enregistrer les modifications</DIV>");	
			}
			else
			{
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeTempsDeJeu();\">Créer le temps de jeu</DIV>");	
			}
			
			print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourMatch();\">Annuler</DIV>");	
			
			print ("<DIV id=\"temps_de_jeu.creamodi.message\" class=\"texte\"></DIV>");
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
