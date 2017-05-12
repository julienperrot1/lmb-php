<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'utils/Tableur.php');

?>

<SCRIPT src="ajax/poule.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			<?php
			
			$poule = Poule::recup($_GET["id"]);
			$phase_poules = PhasePoules::recup($poule->get("phase_poules_id"));
			$phase = Phase::recup($phase_poules->get("phase_id"));
			print ($poule->get("libelle") . " de la phase \"" . $phase->get("libelle") . "\"");
			print ("<INPUT id=\"poule.id\" type=\"hidden\" value=\"" . $_GET["id"] . "\">");
			print ("<INPUT id=\"tournoi.id\" type=\"hidden\" value=\"" . $phase->get("tournoi_id") . "\">");
			
			?>
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="colonne50">
			Classement de la poule :
			<?php
			
			$classement = $poule->getClassement();
			if ($classement)
			{
				Tableur::dessineTableau($classement, true
										  , array("Cl.", "Equipe", "Jou", "Vic", "Def", "Nul", "Pts", "Avg", "Dep")
										  , array(function ($objet) { return $objet["classement"]; }
												, function ($objet) { $equipe = Equipe::recup($objet["equipe_id"]);
																	  return "<B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B>"; }
												, function ($objet) { return $objet["matchs_joues"]; }
												, function ($objet) { return $objet["matchs_gagnes"]; }
												, function ($objet) { return $objet["matchs_perdus"]; }
												, function ($objet) { return $objet["matchs_nuls"]; }
												, function ($objet) { return $objet["points"]; }
												, function ($objet) { return $objet["goal_average"]; }
												, function ($objet) { if ($objet["departage"] == 0)
																	  {
																		  return "-";
																	  }
																	  else
																	  {
																		  return $objet["departage"];
																	  }
																	}
											)
										  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");
			}
			else
			{
				print ("Aucune équipe inscrite pour cette poule");
			}
			print ("<BR/>");
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("Ajouter ou modifier un départage : Pour ");
				print ("<SELECT id=\"poule.departage.equipe_id\">");
				foreach($poule_equipes as $poule_equipe)
				{			
					$equipe = Equipe::recup($poule_equipe["equipe_id"]);
					print ("<OPTION value=\"" . $equipe->get("id") . "\">" . $equipe->get("nom") . "</OPTION>");
				}
				print ("</SELECT>");
				print (" de ");
				print ("<INPUT class=\"champTexte\" id=\"poule.departage.points\" type=\"text\" maxlength=\"2\" size=\"3\">");	
				print (" points ");
				print ("<DIV class=\"champ_a_cliquer\" id=\"poule.departage.valide\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"valideDepartage();\">OK</DIV>");	
				
				print ("<DIV id=\"poule.departage.message\" class=\"texte\"></DIV>");
			}
			
			?>
		</DIV>
		
		<DIV class="pleineLargeur">
			Tableau des matchs :
			
			<?php

			$poule_equipes = $poule->getEquipes();
			if ($poule_equipes)
			{
				print ("<TABLE class=\"tableau_recherche\">");
				print ("<TR class=\"tableau_recherche_tr\">");
				print ("<TD></TD>");
				foreach ($poule_equipes as $poule_equipe)
				{
					$equipe = Equipe::recup($poule_equipe["equipe_id"]);
					print ("<TD class=\"tableau_recherche_th\"><B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B></TD>");
				}
				print ("</TR>");
				
				foreach ($poule_equipes as $poule_equipe_gauche)
				{
					$equipe_gauche = Equipe::recup($poule_equipe_gauche["equipe_id"]);
					
					print ("<TR class=\"tableau_recherche_tr\">");
					print ("<TD class=\"tableau_recherche_th\"><B><FONT color=\"#" . $equipe_gauche->get("couleur_base") . "\">" . $equipe_gauche->get("nom") . "</FONT></B></TD>");
					
					foreach ($poule_equipes as $poule_equipe_haut)
					{
						$equipe_haut = Equipe::recup($poule_equipe_haut["equipe_id"]);
						
						if ($equipe_haut->get("id") == $equipe_gauche->get("id"))
						{
							print ("<TD class=\"tableau_recherche_td_vide\"></TD>");
						}
						else
						{
							$match = $poule->getMatch($equipe_haut->get("id"), $equipe_gauche->get("id"));
							
							if ($match)
							{
								if (!is_numeric($match))
								{
									print ("<TD class=\"tableau_recherche_td\">");
									print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"afficheMatch(" . $match->get("id") . ");\">");
									print ("<B>" . $match->get("libelle") . "</B><BR/>");
									
									if ($match->get("resultat") == $MATCH_RESULTAT_AJOUER)
									{
										print ("Match à jouer");
									}
									else if ($match->get("resultat") == $MATCH_RESULTAT_NUL)
									{
										print ("Match nul<BR/>");
										print ($match->get("score1") . " - " . $match->get("score2"));
									}
									else
									{
										$formation1 = Formation::recup($match->get("formation1_id"));
										$formation2 = Formation::recup($match->get("formation2_id"));
										
										if ($formation1->get("equipe_id") == $equipe_gauche->get("id"))
										{
											if ($match->get("resultat") == $MATCH_RESULTAT_EQUIPE1)
											{
												print ("Vainqueur : <B><FONT color=\"#" . $equipe_gauche->get("couleur_base") . "\">" . $equipe_gauche->get("nom") . "</FONT></B><BR/>");
												print ("<B>" . $match->get("score1") . "</B> - " . $match->get("score2"));
											}
											else if ($match->get("resultat") == $MATCH_RESULTAT_EQUIPE2)
											{
												print ("Vainqueur : <B><FONT color=\"#" . $equipe_haut->get("couleur_base") . "\">" . $equipe_haut->get("nom") . "</FONT></B><BR/>");
												print ($match->get("score1") . " - <B>" . $match->get("score2") . "</B>");
											}
										}
										else if ($formation2->get("equipe_id") == $equipe_gauche->get("id"))
										{
											if ($match->get("resultat") == $MATCH_RESULTAT_EQUIPE2)
											{
												print ("Vainqueur : <B><FONT color=\"#" . $equipe_gauche->get("couleur_base") . "\">" . $equipe_gauche->get("nom") . "</FONT></B><BR/>");
												print ("<B>" . $match->get("score2") . "</B> - " . $match->get("score1"));
											}
											else if ($match->get("resultat") == $MATCH_RESULTAT_EQUIPE1)
											{
												print ("Vainqueur : <B><FONT color=\"#" . $equipe_haut->get("couleur_base") . "\">" . $equipe_haut->get("nom") . "</FONT></B><BR/>");
												print ($match->get("score2") . " - <B>" . $match->get("score1") . "</B>");
											}
										}
									}
									
									print ("</DIV>");
									print ("</TD>");
								}
								else
								{
									print ("<TD class=\"tableau_recherche_td\">Erreur : Plusieurs match entre ces 2 équipes pour cette poule</TD>");
								}
							}
							else
							{
								if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
								{
									print ("<TD class=\"tableau_recherche_td\"><DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeMatch(" . $equipe_gauche->get("id") . ", " . $equipe_haut->get("id") . ");\">Match inexistant</DIV></TD>");
								}
								else
								{
									print ("<TD class=\"tableau_recherche_td\">Match inexistant</TD>");
								}
							}
						}
					}
					print ("</TR>");
				}
				
				print ("</TABLE>");
			}
			else
			{
				print ("Aucune équipe inscrite pour cette poule");
				print ("<BR/>");
			}
			
			?>
			
		</DIV>

		<DIV class="pleineLargeur">
			<?php
		
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				if ($poule->get("etat") < 3)
				{
					if ($poule->getNbMatchs() == 0 && $poule->get("etat") < 2)
					{
						print ("<DIV class=\"champ_a_cliquer\" id=\"poule.creer.matchs\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeMatchs();\">Créer automatiquement tous les matchs</DIV>");	
					}
					else
					{
						print ("<DIV class=\"champ_a_cliquer\" id=\"poule.creer.matchs\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"creeMatchs();\">Créer automatiquement les matchs manquants</DIV>");	
						
						if ($poule->getNbMatchs(1) == 0 && $poule->get("etat") == 2)
						{
							print ("<DIV class=\"champ_a_cliquer\" id=\"poule.fin\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"terminePoule();\">Terminer cette poule</DIV>");	
						}
					}
				}
			}
			
			print ("<DIV class=\"champ_a_cliquer\" id=\"poule.feuilles_matchs.pdf\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"fichesDeMatchsPdf();\">Imprimer les feuilles de match</DIV>");	
			
			print ("<DIV class=\"champ_a_cliquer\" id=\"poule.retour.tournoi\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Retour au tournoi</DIV>");	
		
			print ("<DIV id=\"poule.message\" class=\"texte\"></DIV>");
			
			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>


<!-- Chargement des diverses parties de la page -->

