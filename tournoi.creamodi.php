<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Tournoi.php');
include_once ($RACINE . 'modele/Ligue.php');

?>

<SCRIPT src="ajax/tournoi.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'une tournoi
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			$tournoi = null;
			$tournoi_libelle = "";
			$tournoi_lieu = "";
			$tournoi_nb_equipe_max = null;
			$tournoi_ligue_id = null;
			if (isset($_GET["ligueId"]))
			{
				$tournoi_ligue_id = $_GET["ligueId"];
			}
			if (isset($_GET["id"]))
			{
				$tournoi = Tournoi::recup($_GET["id"]);
				$tournoi_libelle = $tournoi->get("libelle");
				$tournoi_lieu = $tournoi->get("lieu");
				$tournoi_nb_equipe_max = $tournoi->get("nb_equipe_max");
				$tournoi_ligue_id = $tournoi->get("ligue_id");
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{	
				print ("Libellé du tournoi : ");
				print ("<INPUT class=\"champTexte\" id=\"tournoi.creamodi.libelle\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $tournoi_libelle . "\">");	
				print ("<BR/>");
				
				print ("Lieu de déroulement : ");
				print ("<INPUT class=\"champTexte\" id=\"tournoi.creamodi.lieu\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $tournoi_lieu . "\">");	
				print ("<BR/>");

				print ("Nombre maximum d'équipe inscrites au tournoi : ");
				print ("<INPUT class=\"champTexte\" id=\"tournoi.creamodi.nb_equipe_max\" type=\"text\" maxlength=\"2\" size=\"5\" value=\"" . $tournoi_nb_equipe_max . "\">");	
				print ("<BR/>");
				
				print ("Ligue : ");
				print ("<SELECT id=\"tournoi.creamodi.ligue_id\" STYLE=\"width:50%\">");
				foreach(Ligue::recupTous("id DESC") as $ligue)
				{			
					print ("<OPTION value=\"" . $ligue->get("id") . "\"");
					if ($tournoi_ligue_id && $tournoi_ligue_id == $ligue->get("id"))
					{
						print (" selected=\"true\"");
					}
					print (">" . $ligue->get("libelle") . "</OPTION>");
				}
				print ("</SELECT>");
				print ("<BR/>");
				
				if ($tournoi)
				{
					print ("<INPUT id=\"tournoi.creamodi.id\" type=\"hidden\" value=\"" . $tournoi->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieTournoi();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeTournoi();\">Créer le tournoi</DIV>");	
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourLigue();\">Annuler</DIV>");	
				
				print ("<DIV id=\"tournoi.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($tournoi_ligue_id)
				{
					print ("<INPUT id=\"tournoi.creamodi.ligue_id\" type=\"hidden\" value=\"" . $tournoi_ligue_id . "\">");
				}
			
				if ($tournoi)
				{
					print ("<DIV id=\"tournoi.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer un tournoi</DIV>");
				}
				else
				{
					print ("<DIV id=\"tournoi.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer un nouveau tournoi</DIV>");
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourLigue();\">Annuler</DIV>");	
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
