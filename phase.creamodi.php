<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/PhaseTableau.php');

?>

<SCRIPT src="ajax/phase.creamodi.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			Création / Edition d'une phase
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php
			
			print ("<INPUT id=\"phase.creamodi.tournoi_id\" type=\"hidden\" value=\"" . $_GET["tournoiId"] . "\">");
			
			$phase = null;
			$phase_libelle = "";
			$phase_date_jour = "";
			$phase_date_mois = "";
			$phase_date_annee = "";
			$phase_type = 1;
			$phase_nb_periode_match = 4;
			$phase_duree_periode_match_minutes = 10;
			$phase_duree_periode_match_secondes = 0;
			$phase_tableau_etat = 1;
			
			if (isset($_GET["id"]))
			{
				$phase = Phase::recup($_GET["id"]);
				$phase_libelle = $phase->get("libelle");
				$phase_date = explode("-", $phase->get("date"));
				$phase_date_jour = $phase_date[2];
				$phase_date_mois = $phase_date[1];
				$phase_date_annee = $phase_date[0];
				$phase_type = $phase->get("type");
				
				if ($phase_type == 1)
				{
					$phase_poules = PhasePoules::recup($phase->get("specifique_id"));
					$phase_nb_periode_match = $phase_poules->get("nb_periode_match");
					$phase_duree_periode_match = $phase_poules->get("duree_periode_match");
					$phase_duree_periode_match_minutes = floor($phase_duree_periode_match / 60);
					$phase_duree_periode_match_secondes = $phase_duree_periode_match - ($phase_duree_periode_match_minutes * 60);
				}
				
				if ($phase_type == 2)
				{
					$phase_tableau = PhaseTableau::recup($phase->get("specifique_id"));
					$phase_tableau_etat = $phase_tableau->get("etat");
				}
			}
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{	
				print ("Libelle : ");
				print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.libelle\" type=\"text\" maxlength=\"255\" size=\"50\" value=\"" . $phase_libelle . "\">");	
				print ("<BR/>");
				
				print ("Date : ");
				print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.date_jour\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . $phase_date_jour . "\">");	
				print (" / ");
				print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.date_mois\" type=\"text\" maxlength=\"2\" size=\"1\" value=\"" . $phase_date_mois . "\">");	
				print (" / ");
				print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.date_annee\" type=\"text\" maxlength=\"4\" size=\"2\" value=\"" . $phase_date_annee . "\">");	
				print ("<BR/>");

				print ("Type : ");
				foreach ($PHASE_TYPE_DESC as $type => $type_desc)
				{
					print ("<INPUT id=\"phase.creamodi.type." . $type . "\" name=\"phase.creamodi.type\" type=\"radio\" value=\"" . $type . "\" onchange=\"montreTypeSpecifique(" . $type . ");\"");
					if ($phase_type == $type)
					{
						print (" checked");
					}
					if ($phase)
					{
						print (" disabled");
					}
					print ("> " . $type_desc . " ");	
				}
				print ("<BR/>");
				
				
				print ("<DIV id=\"phase.creamodi.specifique.1\" class=\"texte\" hidden>");
				
				if (!$phase)
				{
					print ("Nombre de poules : ");
					print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.1.nb_poules\" type=\"text\" maxlength=\"2\" size=\"4\" value=\"\">");	
					print ("<BR/>");
				}
				
				print ("Nombre de périodes d'un match : ");
				print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.1.nb_periode_match\" type=\"text\" maxlength=\"1\" size=\"2\" value=\"" . $phase_nb_periode_match . "\">");	
				print ("<BR/>");
				
				print ("Durée d'une période d'un match : ");
				print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.1.duree_periode_match_minutes\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"" . $phase_duree_periode_match_minutes . "\">");	
				print (" min ");
				print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.1.duree_periode_match_secondes\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"" . $phase_duree_periode_match_secondes . "\">");	
				print ("<BR/>");
				
				print ("</DIV>");
				
				
				print ("<DIV id=\"phase.creamodi.specifique.2\" class=\"texte\" hidden>");
						
				print ("Etat actuel : ");
				foreach ($ETAT_DESC as $etat => $etat_desc)
				{
					print ("<INPUT id=\"phase.creamodi.specifique.2.etat." . $etat . "\" name=\"phase.creamodi.etat\" type=\"radio\" value=\"" . $etat . "\"");
					if ($phase_tableau_etat == $etat)
					{
						print (" checked");
					}
					print ("> " . $etat_desc . " ");	
				}
				print ("<BR/>");	
				
				if (!$phase)
				{
					print ("Matchs joués : ");
					print ("<INPUT id=\"phase.creamodi.specifique.2.matchs.victoires\" name=\"phase.creamodi.specifique.2.matchs\" type=\"radio\" value=\"1\" checked> Victoires ");
					print ("<INPUT id=\"phase.creamodi.specifique.2.matchs.victoiresPF\" name=\"phase.creamodi.specifique.2.matchs\" type=\"radio\" value=\"2\"> Victoires + Petite finale ");
					print ("<INPUT id=\"phase.creamodi.specifique.2.matchs.tous\" name=\"phase.creamodi.specifique.2.matchs\" type=\"radio\" value=\"3\"> Tous");
					print ("<BR/>");

					print ("Nombre d'équipes dans le tableau : ");
					print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.2.nb_equipes\" type=\"text\" maxlength=\"2\" size=\"4\" value=\"\">");	
					print ("<BR/>");
					
					print ("Nombre et durée des périodes des matchs : ");
					print ("<BR/><TABLE>");
					for ($index = 1; $index <= 6; $index++)
					{
						print ("<TR><TD>" . $MATCHS_TABLEAU_DESC[$index] . "</TD>");
						print ("<TD>Périodes : <INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.2.nb_periode_match_" . $index . "\" type=\"text\" maxlength=\"1\" size=\"2\" value=\"\"></TD>");
						print ("<TD>Durée : <INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.2.duree_periode_match_minutes_" . $index . "\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"\">");	
						print (" min ");
						print ("<INPUT class=\"champTexte\" id=\"phase.creamodi.specifique.2.duree_periode_match_secondes_" . $index . "\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"\"></TD></TR>");	
					}
					print ("</TABLE><BR/>");
				}
				
				print ("</DIV>");
				
				
				print ("<DIV id=\"phase.creamodi.specifique.3\" class=\"texte\" hidden>");
				print ("TODO");
				print ("</DIV>");
				
				
				
				
				if ($phase)
				{
					print ("<INPUT id=\"phase.creamodi.id\" type=\"hidden\" value=\"" . $phase->get("id") . "\">");
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifiePhase();\">Enregistrer les modifications</DIV>");	
				}
				else
				{
					print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creePhase();\">Créer la phase</DIV>");	
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
								
				print ("<DIV id=\"phase.creamodi.message\" class=\"texte\"></DIV>");
			}
			else
			{
				if ($phase)
				{
					print ("<DIV id=\"phase.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour éditer une phase</DIV>");
				}
				else
				{
					print ("<DIV id=\"phase.creamodi.message\" class=\"messageErreur\">Vous n'avez pas les droits pour créer une nouvelle phase</DIV>");
				}
				
				print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Annuler</DIV>");	
			}
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

print ("<SCRIPT type=\"text/javascript\">montreTypeSpecifique(" . $phase_type . ");</SCRIPT>");

?>
