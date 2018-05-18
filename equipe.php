<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Ligue.php');
include_once ($RACINE . 'utils/Tableur.php');

?>

<SCRIPT src="ajax/equipe.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			<?php
			$equipe = Equipe::recup($_GET["id"]);
			print ("<B><FONT color=\"#" . $equipe->get("couleur_base") . "\">" . $equipe->get("nom") . "</FONT></B>");
			print ("<BR/>");
			
			$equipe_photo = $equipe->get("photo");
			if ($equipe_photo && $equipe_photo != "")
			{
				print ("<IMG id=\"equipe.photo\" class=\"image_grande\" src=\"" . $IMAGE_UPLOAD_DIR . "/" . $equipe_photo . "\"></IMG>");
			}
			else
			{
				print ("<IMG id=\"equipe.photo\" class=\"image_grande\" src=\"images/equipe_default.jpg\"></IMG>");
			}
			print ("<INPUT id=\"equipe.id\" type=\"hidden\" value=\"" . $_GET["id"] . "\">");
			
			?>
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php

			print ("<DIV id=\"equipe.stats.equipe\" class=\"texte\">");
			
			print ("Nombre de participation à un tournoi : " . $equipe->recupNbTournois());
			print ("<BR/>");
			print ("<BR/>");
			
			
			print ("<DIV class=\"soustitre\">Matchs</DIV>");
			print ("<BR/>");
			
			Tableur::dessineTableau(Equipe::recupParChampDifferent("id", $equipe->get("id"), "nom ASC"), true
				  , array("Equipe adverse", "Joués", "Gagnés", "Nuls", "Perdus", "En attente", "<B>Total</B>")
				  , array(function ($objet) {	return "<A href=\"equipe.php?id=" . $objet->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $objet->get("couleur_base") . "\">" . $objet->get("nom") . "</FONT></B></A>"; }
						 ,function ($objet) {   global $equipe;
												return $equipe->recupNbMatchs(0, $objet); }
						 ,function ($objet) {   global $equipe;
												return $equipe->recupNbMatchs(1, $objet); }
						 ,function ($objet) {   global $equipe;
												return $equipe->recupNbMatchs(3, $objet); }
						 ,function ($objet) {   global $equipe;
												return $equipe->recupNbMatchs(2, $objet); }
						 ,function ($objet) {   global $equipe;
												return $equipe->recupNbMatchs(4, $objet); }
						 ,function ($objet) {   global $equipe;
												return "<B>" . $equipe->recupNbMatchs(-1, $objet) . "</B>"; }												
					)
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				  , array(4, 1, 1, 1, 1, 1, 1)
			);
			Tableur::dessineTableau([$equipe], false
				  , null
				  , array(function ($objet) {	return "<B>TOTAL</B>"; }
						 ,function ($objet) {   return "<B>" . $objet->recupNbMatchs(0) . "</B>"; }
						 ,function ($objet) {   return "<B>" . $objet->recupNbMatchs(1) . "</B>"; }
						 ,function ($objet) {   return "<B>" . $objet->recupNbMatchs(3) . "</B>"; }
						 ,function ($objet) {   return "<B>" . $objet->recupNbMatchs(2) . "</B>"; }
						 ,function ($objet) {   return "<B>" . $objet->recupNbMatchs(4) . "</B>"; }
						 ,function ($objet) {   return "<B>" . $objet->recupNbMatchs(-1) . "</B>"; }												
					)
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				  , array(4, 1, 1, 1, 1, 1, 1)
			);
			print ("<BR/>");
			print ("<BR/>");

	
			$joueurs_matchs = $equipe->recupJoueursNbMatch(0);
			if ($joueurs_matchs)
			{
				print ("<DIV class=\"soustitre\">Joueurs</DIV>");
				print ("<BR/>");
				
				Tableur::dessineTableau($joueurs_matchs, true
				  , array("Joueur", "Nombre de match(s)")
				  , array(function ($objet) {   global $utilisateur_en_cours;
												$retour = $objet["joueur"]->get("pseudo");
												if (isset($utilisateur_en_cours))
												{
													$retour = $retour . " (" . $objet["joueur"]->get("prenom") . " " . $objet["joueur"]->get("nom") . ")";
												}
												return $retour;
											}
						 ,function ($objet) {   return $objet["nb_matchs"]; }										
					)
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				);
			}
			else
			{
				print ("Aucun joueur n'a pour l'instant joué avec cette équipe");
			}
			print ("<BR/>");
			print ("<BR/>");
			
	
			/*
      print ("<DIV class=\"soustitre\">Statistiques</DIV>");
			print ("<BR/>");
			
			Tableur::dessineTableau([$equipe], true
				  , array("STATS TOTALES", "MATCHS", "MIN", "PTS", "FGM", "FGM<BR/>/HS", "FGM<BR/>/RS", "FGA<BR/>/RS", "FG%<BR/>/RS", "3FGM", "3FGM<BR/>/HS", "3FGM<BR/>/RS", "3FGA", "3FG%", "FTM", "FTM<BR/>/HS", "FTM<BR/>/RS", "FTA", "FT%", "OREB", "DREB", "REB", "AST", "STL", "BLK", "PF")
				  , array(function ($objet) {
												return "GLOBAL";
											}
						 ,function ($objet) {
												return $objet->recupNbMatchs(0, null, null, null, -1);
											}
						 ,function ($objet) {
												return $objet->recupTempsDeJeu(0, null, null, null);
											}
						 ,function ($objet) {
												return ($objet->nbShootsParMatch(-1, 1, true)
													  + ($objet->nbShootsParMatch(-1, 2, true) * 2)
													  + ($objet->nbShootsParMatch(-1, 3, true) * 3));
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 5, true);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 5, true, null, 0);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 5, true, null, 1);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 5, null, null, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->nbShootsParMatch(-1, 5, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $objet->nbShootsParMatch(-1, 5, true, null, 1);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 3, true);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 3, true, null, 0);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 3, true, null, 1);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 3, null, null, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->nbShootsParMatch(-1, 3, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $objet->nbShootsParMatch(-1, 3, true, null, 1);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 1, true);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 1, true, null, 0);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 1, true, null, 1);
											}
						 ,function ($objet) {
												return $objet->nbShootsParMatch(-1, 1, null, null, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->nbShootsParMatch(-1, 1, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $objet->nbShootsParMatch(-1, 1, true, null, 1);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												return $objet->nbRebondsParMatch(-1, 1);
											}
						 ,function ($objet) {
												return $objet->nbRebondsParMatch(-1, 2);
											}
						 ,function ($objet) {
												return $objet->nbRebondsParMatch(-1, 0);
											}
						 ,function ($objet) {
												return $objet->nbPassesParMatch(-1);
											}
						 ,function ($objet) {
												return $objet->nbInterceptionsParMatch(-1);
											}
						 ,function ($objet) {
												return $objet->nbContresParMatch(-1);
											}
						 ,function ($objet) {
												return $objet->nbFautesParMatch(-1, 1);
												return " ";
											}
						 )
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
				  , array("", "Nombre de matchs joués", "Nombre de minutes jouées", "Nombre de points marqués", "Nombre de paniers marqués, à 2 et à 3 points", "Nombre de paniers marqués, dans le cadre d'un match sans saisie des statistiques", "Nombre de paniers marqués, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers tentés, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au shoot, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers marqués à 3 points", "Nombre de paniers marqués à 3 points, dans le cadre d'un match sans saisie des statistiques", "Nombre de paniers marqués à 3 points, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers tentés à 3 points, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au shoot à 3 points, dans le cadre d'un match avec saisie des statistiques", "Nombre de lancés francs marqués", "Nombre de lancés francs marqués, dans le cadre d'un match sans saisie des statistiques", "Nombre de lancés francs marqués, dans le cadre d'un match avec saisie des statistiques", "Nombre de lancés francs tentés, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au lancé franc, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds offensifs, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds défensifs, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds total, dans le cadre d'un match avec saisie des statistiques", "Nombre de passes décisives réalisées, dans le cadre d'un match avec saisie des statistiques", "Nombre d'interceptions de balle réalisées, dans le cadre d'un match avec saisie des statistiques", "Nombre de contres effectués, dans le cadre d'un match avec saisie des statistiques", "Nombre de fautes commises")
			);*/
			
			
			/*$tournois = $equipe->recupTournois();
			  
			Tableur::dessineTableau($tournois, false
			, null
				  , array(function ($objet) {
												$ligue = Ligue::recup($objet->get("ligue_id"));
												return $objet->get("libelle") . " de " . $ligue->get("libelle");
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->recupNbMatchs(0, null, $objet, null, -1);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->recupTempsDeJeu(0, null, $objet, null);
											}
						 ,function ($objet) {
												global $equipe;
												return ($equipe->nbShootsParMatch(-1, 1, true, null, -1, null, null, $objet)
													  + ($equipe->nbShootsParMatch(-1, 2, true, null, -1, null, null, $objet) * 2)
													  + ($equipe->nbShootsParMatch(-1, 3, true, null, -1, null, null, $objet) * 3));
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 5, true, null, -1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 5, true, null, 0, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 5, true, null, 1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 5, null, null, 1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->nbShootsParMatch(-1, 5, null, null, 1, null, null, $objet);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $equipe->nbShootsParMatch(-1, 5, true, null, 1, null, null, $objet);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 3, true, null, -1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 3, true, null, 0, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 3, true, null, 1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 3, null, null, 1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->nbShootsParMatch(-1, 3, null, null, 1, null, null, $objet);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $equipe->nbShootsParMatch(-1, 3, true, null, 1, null, null, $objet);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 1, true, null, -1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 1, true, null, 0, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 1, true, null, 1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbShootsParMatch(-1, 1, null, null, 1, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->nbShootsParMatch(-1, 1, null, null, 1, null, null, $objet);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $equipe->nbShootsParMatch(-1, 1, true, null, 1, null, null, $objet);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbRebondsParMatch(-1, 1, null, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbRebondsParMatch(-1, 2, null, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbRebondsParMatch(-1, 0, null, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbPassesParMatch(-1, null, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbInterceptionsParMatch(-1, null, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbContresParMatch(-1, null, null, null, $objet);
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->nbFautesParMatch(-1, 1, null, null, null, $objet);
												return " ";
											}
						 )
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
			);
			
			
			Tableur::dessineTableau([$equipe], true
				  , array("PAR MATCH", "MATCHS", "MIN", "PTS", "FGM", "FGM<BR/>/HS", "FGM<BR/>/RS", "FGA<BR/>/RS", "FG%<BR/>/RS", "3FGM", "3FGM<BR/>/HS", "3FGM<BR/>/RS", "3FGA", "3FG%", "FTM", "FTM<BR/>/HS", "FTM<BR/>/RS", "FTA", "FT%", "OREB", "DREB", "REB", "AST", "STL", "BLK", "PF")
				  , array(function ($objet) {
												return "GLOBAL";
											}
						 ,function ($objet) {
												return $objet->recupNbMatchs(0, null, null, null, -1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->recupTempsDeJeu(0, null, null, null) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio(($objet->nbShootsParMatch(-1, 1, true)
													  + ($objet->nbShootsParMatch(-1, 2, true) * 2)
													  + ($objet->nbShootsParMatch(-1, 3, true) * 3)) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 5, true) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 5, true, null, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 5, true, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 5, null, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->nbShootsParMatch(-1, 5, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $objet->nbShootsParMatch(-1, 5, true, null, 1);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 3, true) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 3, true, null, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 3, true, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 3, null, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->nbShootsParMatch(-1, 3, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $objet->nbShootsParMatch(-1, 3, true, null, 1);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 1, true) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 1, true, null, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 1, true, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 1, null, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->nbShootsParMatch(-1, 1, null, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $objet->nbShootsParMatch(-1, 1, true, null, 1);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbRebondsParMatch(-1, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbRebondsParMatch(-1, 2) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbRebondsParMatch(-1, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbPassesParMatch(-1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbInterceptionsParMatch(-1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbContresParMatch(-1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												$denominateur = $objet->recupNbMatchs(0, null, null, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbFautesParMatch(-1, 1) / $denominateur, 1, 1);
											}
						 )
					  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
					  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
				  	  , array("", "Nombre de matchs joués", "Nombre de minutes jouées", "Nombre de points marqués", "Nombre de paniers marqués, à 2 et à 3 points", "Nombre de paniers marqués, dans le cadre d'un match sans saisie des statistiques", "Nombre de paniers marqués, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers tentés, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au shoot, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers marqués à 3 points", "Nombre de paniers marqués à 3 points, dans le cadre d'un match sans saisie des statistiques", "Nombre de paniers marqués à 3 points, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers tentés à 3 points, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au shoot à 3 points, dans le cadre d'un match avec saisie des statistiques", "Nombre de lancés francs marqués", "Nombre de lancés francs marqués, dans le cadre d'un match sans saisie des statistiques", "Nombre de lancés francs marqués, dans le cadre d'un match avec saisie des statistiques", "Nombre de lancés francs tentés, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au lancé franc, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds offensifs, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds défensifs, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds total, dans le cadre d'un match avec saisie des statistiques", "Nombre de passes décisives réalisées, dans le cadre d'un match avec saisie des statistiques", "Nombre d'interceptions de balle réalisées, dans le cadre d'un match avec saisie des statistiques", "Nombre de contres effectués, dans le cadre d'un match avec saisie des statistiques", "Nombre de fautes commises")
				);
				  
				  
				  $tournois = $equipe->recupTournois();
				  
				  Tableur::dessineTableau($tournois, false
				  , null
				  , array(function ($objet) {
												$ligue = Ligue::recup($objet->get("ligue_id"));
												return $objet->get("libelle") . " de " . $ligue->get("libelle");
											}
						 ,function ($objet) {
												global $equipe;
												return $equipe->recupNbMatchs(0, null, $objet, null, -1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->recupTempsDeJeu(0, null, $objet, null) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio(($equipe->nbShootsParMatch(-1, 1, true, null, -1, null, null, $objet)
													  + ($equipe->nbShootsParMatch(-1, 2, true, null, -1, null, null, $objet) * 2)
													  + ($equipe->nbShootsParMatch(-1, 3, true, null, -1, null, null, $objet) * 3)) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 5, true, null, -1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 5, true, null, 0, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 5, true, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 5, null, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->nbShootsParMatch(-1, 5, null, null, 1, null, null, $objet);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $equipe->nbShootsParMatch(-1, 5, true, null, 1, null, null, $objet);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 3, true, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 3, true, null, 0, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 3, true, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 3, null, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->nbShootsParMatch(-1, 3, null, null, 1, null, null, $objet);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $equipe->nbShootsParMatch(-1, 3, true, null, 1, null, null, $objet);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 1, true, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 1, true, null, 0, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 1, true, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbShootsParMatch(-1, 1, null, null, 1, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->nbShootsParMatch(-1, 1, null, null, 1, null, null, $objet);
												if ($denominateur == 0)
												{
													return "-";
												}
												$numerateur = $equipe->nbShootsParMatch(-1, 1, true, null, 1, null, null, $objet);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbRebondsParMatch(-1, 1, null, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbRebondsParMatch(-1, 2, null, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbRebondsParMatch(-1, 0, null, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbPassesParMatch(-1, null, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbInterceptionsParMatch(-1, null, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, 5);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbContresParMatch(-1, null, null, null, $objet) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $equipe;
												$denominateur = $equipe->recupNbMatchs(0, null, $objet, null, -1);
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($equipe->nbFautesParMatch(-1, 1, null, null, null, $objet) / $denominateur, 1, 1);
											}
						 )
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
			);
			print ("<BR/>");
			print ("<BR/>");
				  */
				  
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"equipe.modifie.equipe\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieEquipe();\">Modification de l'équipe</DIV>");	
			}
			
			print ("<DIV class=\"champ_a_cliquer\" id=\"equipe.retour.equipes\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourEquipes();\">Retour à la liste des équipes</DIV>");	
			
			print ("</DIV>");

			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
