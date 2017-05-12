<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'utils/Regle.php');

?>

<SCRIPT src="ajax/match.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'un match
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			$match = null;
			$match_libelle = "";
			$match_date_jour = "";
			$match_date_mois = "";
			$match_date_annee = "";
			$match_equipe1_id = null;
			$match_equipe1_editable = "";
			$match_equipe2_id = null;
			$match_equipe2_editable = "";
			$match_poule_id = null;
			$match_phase_tableau_id = null;
			
			if (isset($_GET["pouleId"]))
			{
				$match_poule_id = $_GET["pouleId"];
				$poule = Poule::recup($match_poule_id);
				$match_libelle = "Match " . ($poule->getNbMatchs() + 1);
				print ("<INPUT id=\"match.creamodi.poule_id\" type=\"hidden\" value=\"" . $match_poule_id . "\">");
			}
			
			if (isset($_GET["phaseTableauId"]))
			{
				$match_phase_tableau_id = $_GET["phaseTableauId"];
				print ("<INPUT id=\"match.creamodi.phase_tableau_id\" type=\"hidden\" value=\"" . $match_phase_tableau_id . "\">");
			}
			
			if (isset($_GET["tournoiId"]))
			{
				$match_tournoi_id = $_GET["tournoiId"];
				print ("<INPUT id=\"match.creamodi.tournoi_id\" type=\"hidden\" value=\"" . $match_tournoi_id . "\">");
			}
			
			if (isset($_GET["equipe1Id"]))
			{
				$match_equipe1_id = $_GET["equipe1Id"];
				$match_equipe1_editable = " disabled";
			}
			if (isset($_GET["equipe2Id"]))
			{
				$match_equipe2_id = $_GET["equipe2Id"];
				$match_equipe2_editable = " disabled";
			}
			
			if (isset($_GET["id"]))
			{
				$match = Match::recup($_GET["id"]);
				$match_libelle = $match->get("libelle");
				$match_date = explode("-", $match->get("date"));
				$match_date_jour = $match_date[2];
				$match_date_mois = $match_date[1];
				$match_date_annee = $match_date[0];
				$formation1 = Formation::recup($match->get("formation1_id"));
				if ($formation1)
				{
					$match_equipe1_id = $formation1->get("equipe_id");
				}
				$regle_formation1 = $match->get("regle_formation1");
				$match_equipe1_editable = " disabled";
				$formation2 = Formation::recup($match->get("formation2_id"));
				if ($formation2)
				{
					$match_equipe2_id = $formation2->get("equipe_id");
				}
				$regle_formation2 = $match->get("regle_formation2");
				$match_equipe2_editable = " disabled";
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("Libellé du match : ");
				print ("<INPUT class=\"champTexte\" id=\"match.creamodi.libelle\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $match_libelle . "\">");	
				print ("<BR/>");

				print ("Date du match : ");
				print ("<INPUT class=\"champTexte\" id=\"match.creamodi.date_jour\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . $match_date_jour . "\">");	
				print (" / ");
				print ("<INPUT class=\"champTexte\" id=\"match.creamodi.date_mois\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . $match_date_mois . "\">");	
				print (" / ");
				print ("<INPUT class=\"champTexte\" id=\"match.creamodi.date_annee\" type=\"text\" maxlength=\"4\" size=\"2\" value=\"" . $match_date_annee . "\">");	
				print ("<BR/>");
				
				print ("Equipe 1 : ");
				if (!$regle_formation1 || $regle_formation1 == "")
				{
					print ("<SELECT id=\"match.creamodi.equipe1_id\" style=\"width:50%\"" . $match_equipe1_editable . ">");
					foreach(Equipe::recupTous("nom ASC") as $equipe1)
					{			
						print ("<OPTION value=\"" . $equipe1->get("id") . "\"");
						if ($match_equipe1_id && $match_equipe1_id == $equipe1->get("id"))
						{
							print (" selected=\"true\"");
						}
						print (">" . $equipe1->get("nom") . "</OPTION>");
					}
					print ("</SELECT>");
				}
				else
				{
					print (Regle::versString($regle_formation1));
				}
				print ("<BR/>");
				
				if (!$match)
				{
					print ("Formation de l'équipe 1 : ");
					print ("<INPUT id=\"match.creamodi.formation1.precedente\" name=\"match.creamodi.formation1\" type=\"radio\" checked /> Utiliser la dernière formation connue de cette équipe ");
					print ("<INPUT id=\"match.creamodi.formation1.vide\" name=\"match.creamodi.formation1\" type=\"radio\" /> Repartir d'une formation vide");
					print ("<BR/>");
				}
				print ("<BR/>");
				
				print ("Equipe 2 : ");
				if (!$regle_formation2 || $regle_formation2 == "")
				{
					print ("<SELECT id=\"match.creamodi.equipe2_id\" style=\"width:50%\"" . $match_equipe2_editable . ">");
					foreach(Equipe::recupTous("nom ASC") as $equipe2)
					{			
						print ("<OPTION value=\"" . $equipe2->get("id") . "\"");
						if ($match_equipe2_id && $match_equipe2_id == $equipe2->get("id"))
						{
							print (" selected=\"true\"");
						}
						print (">" . $equipe2->get("nom") . "</OPTION>");
					}
					print ("</SELECT>");
				}
				else
				{
					print (Regle::versString($regle_formation2));
				}
				print ("<BR/>");
				
				if (!$match)
				{
					print ("Formation de l'équipe 2 : ");
					print ("<INPUT id=\"match.creamodi.formation2.precedente\" name=\"match.creamodi.formation2\" type=\"radio\" checked /> Utiliser la dernière formation connue de cette équipe ");
					print ("<INPUT id=\"match.creamodi.formation2.vide\" name=\"match.creamodi.formation2\" type=\"radio\" /> Repartir d'une formation vide");
					print ("<BR/>");
				}
				print ("<BR/>");
				
				if ($match_phase_tableau_id)
				{
					print ("Périodes : <INPUT class=\"champTexte\" id=\"match.creamodi.nb_periode_match\" type=\"text\" maxlength=\"1\" size=\"2\" value=\"\">");
					print ("<BR/>");
					print ("Durée : <INPUT class=\"champTexte\" id=\"match.creamodi.duree_periode_match_minutes\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"\">");	
					print (" min ");
					print ("<INPUT class=\"champTexte\" id=\"match.creamodi.duree_periode_match_secondes\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"\">");	
					print ("<BR/>");
				}
				
				if ($match)
				{
					print ("<INPUT id=\"match.creamodi.id\" type=\"hidden\" value=\"" . $match->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieMatch();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeMatch();\">Créer le match</DIV>");	
				}
				
				if ($match_poule_id)
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourPoule();\">Annuler</DIV>");	
				}
				
				if ($match_tournoi_id)
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
				}
				
				print ("<DIV id=\"match.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($match)
				{
					print ("<DIV id=\"match.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer un match</DIV>");
				}
				else
				{
					print ("<DIV id=\"match.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer un nouveau match</DIV>");
				}
				
				if ($match_poule_id)
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourPoule();\">Annuler</DIV>");	
				}
				
				if ($match_tournoi_id)
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
				}
			}
				
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
