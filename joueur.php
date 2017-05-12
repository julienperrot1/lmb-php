<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'utils/Tableur.php');

?>

<SCRIPT src="ajax/joueur.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			<?php
			
			$joueur = Joueur::recup($_GET["id"]);
			print($joueur->get("pseudo"));
			if (isset($utilisateur_en_cours))
			{
				print(" (" . $joueur->get("prenom") . " " . $joueur->get("nom") . ")");
			}
			print ("<BR/>");
			
			$joueur_photo = $joueur->get("photo");
			if ($joueur_photo && $joueur_photo != "")
			{
				print ("<IMG id=\"joueur.photo\" class=\"image_grande\" src=\"" . $IMAGE_UPLOAD_DIR . "/" . $joueur_photo . "\"></IMG>");
			}
			else
			{
				print ("<IMG id=\"joueur.photo\" class=\"image_grande\" src=\"images/joueur_default_" . $joueur->get("sexe") . ".jpg\"></IMG>");
			}
			print ("<INPUT id=\"joueur.id\" type=\"hidden\" value=\"" . $_GET["id"] . "\">");
			
			?>
		</DIV>
	</DIV>
	
	<DIV class="pleineLargeur">
		<DIV class="texte">
			<?php

			print ("<DIV id=\"joueur.stats.joueur\" class=\"texte\">");
			
			
			print ("<DIV class=\"soustitre\">Equipes</DIV>");
			print ("<BR/>");
			
			Tableur::dessineTableau($joueur->getMatchsJoueesParEquipe(), true
				  , array("Equipe", "Matchs joués", "Matchs gagnés", "Matchs nuls", "Matchs perdus", "Matchs en attente", "<B>Total</B>")
				  , array(function ($objet) {	return "<A href=\"equipe.php?id=" . $objet["equipe"]->get("id") . "\" target=\"_blank\"><B><FONT color=\"#" . $objet["equipe"]->get("couleur_base") . "\">" . $objet["equipe"]->get("nom") . "</FONT></B></A>"; }
						 ,function ($objet) {   global $joueur;
												$matchs = $joueur->getMatchsJoueesParEquipe(0, $objet["equipe"]->get("id"));
												return $matchs[0]["nb_matchs"]; }
						 ,function ($objet) {   global $joueur;
												$matchs = $joueur->getMatchsJoueesParEquipe(1, $objet["equipe"]->get("id"));
												return $matchs[0]["nb_matchs"]; }
						 ,function ($objet) {   global $joueur;
												$matchs = $joueur->getMatchsJoueesParEquipe(3, $objet["equipe"]->get("id"));
												return $matchs[0]["nb_matchs"]; }
						 ,function ($objet) {   global $joueur;
												$matchs = $joueur->getMatchsJoueesParEquipe(2, $objet["equipe"]->get("id"));
												return $matchs[0]["nb_matchs"]; }
						 ,function ($objet) {   global $joueur;
												$matchs = $joueur->getMatchsJoueesParEquipe(4, $objet["equipe"]->get("id"));
												return $matchs[0]["nb_matchs"]; }
						 ,function ($objet) {   global $joueur;
												return "<B>" . $objet["nb_matchs"] . "</B>"; }												
					)
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
			);
			print ("<BR/>");
			print ("<BR/>");
	
			print ("<DIV class=\"soustitre\">Statistiques</DIV>");
			print ("<BR/>");
			
			$dureeDeJeuTous = $joueur->tempsJoueParMatch(-1, null, -1);
			$dureeDeJeu1 = $joueur->tempsJoueParMatch(-1, null, 1);
			$dureeDeJeu5 = $joueur->tempsJoueParMatch(-1, null, 5);
			
			Tableur::dessineTableau([$joueur], true
				  , array(" ", "MIN", "PTS", "FGM", "FGM<BR/>/HS", "FGM<BR/>/RS", "FGA<BR/>/RS", "FG%<BR/>/RS", "3FGM", "3FGM<BR/>/HS", "3FGM<BR/>/RS", "3FGA", "3FG%", "FTM", "FTM<BR/>/HS", "FTM<BR/>/RS", "FTA", "FT%", "OREB", "DREB", "REB", "AST", "STL", "BLK", "PF")
				  , array(function ($objet) {
												return "STATS GLOBALES";
											}
						 ,function ($objet) {
												global $dureeDeJeuTous;
												return $dureeDeJeuTous / 60;
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
				  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
				  , array("", "Nombre de minutes jouées", "Nombre de points marqués", "Nombre de paniers marqués, à 2 et à 3 points", "Nombre de paniers marqués, dans le cadre d'un match sans saisie des statistiques", "Nombre de paniers marqués, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers tentés, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au shoot, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers marqués à 3 points", "Nombre de paniers marqués à 3 points, dans le cadre d'un match sans saisie des statistiques", "Nombre de paniers marqués à 3 points, dans le cadre d'un match avec saisie des statistiques", "Nombre de paniers tentés à 3 points, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au shoot à 3 points, dans le cadre d'un match avec saisie des statistiques", "Nombre de lancés francs marqués", "Nombre de lancés francs marqués, dans le cadre d'un match sans saisie des statistiques", "Nombre de lancés francs marqués, dans le cadre d'un match avec saisie des statistiques", "Nombre de lancés francs tentés, dans le cadre d'un match avec saisie des statistiques", "Pourcentage de réussite au lancé franc, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds offensifs, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds défensifs, dans le cadre d'un match avec saisie des statistiques", "Nombre de rebonds total, dans le cadre d'un match avec saisie des statistiques", "Nombre de passes décisives réalisées, dans le cadre d'un match avec saisie des statistiques", "Nombre d'interceptions de balle réalisées, dans le cadre d'un match avec saisie des statistiques", "Nombre de contres effectués, dans le cadre d'un match avec saisie des statistiques", "Nombre de fautes commises"));
			Tableur::dessineTableau([$joueur], false
				  , null
				  , array(function ($objet) {
												return "MOY. PAR MATCH";
											}
						 ,function ($objet) {
												global $dureeDeJeuTous;
												return Tableur::ratio($dureeDeJeuTous / 60 / $objet->recupNbMatchs(0, null, null, null, -1), 1, 0);
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
				  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1));
			Tableur::dessineTableau([$joueur], false
				  , null
				  , array(function ($objet) {
												return "MOY. PAR MATCH DE 40MIN";
											}
						 ,function ($objet) {
												return "40";
											}
						 ,function ($objet) {
												global $dureeDeJeuTous;
												$denominateur = $dureeDeJeuTous / 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio(($objet->nbShootsParMatch(-1, 1, true)
													  + ($objet->nbShootsParMatch(-1, 2, true) * 2)
													  + ($objet->nbShootsParMatch(-1, 3, true) * 3)) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeuTous;
												$denominateur = $dureeDeJeuTous/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 5, true) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu1;
												$denominateur = $dureeDeJeu1/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 5, true, null, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 5, true, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
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
												global $dureeDeJeu1;
												$denominateur = $dureeDeJeu1/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 3, true) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu1;
												$denominateur = $dureeDeJeu1/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 3, true, null, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 3, true, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
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
												global $dureeDeJeu1;
												$denominateur = $dureeDeJeu1/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 1, true) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu1;
												$denominateur = $dureeDeJeu1/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 1, true, null, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbShootsParMatch(-1, 1, true, null, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
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
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbRebondsParMatch(-1, 1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbRebondsParMatch(-1, 2) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbRebondsParMatch(-1, 0) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbPassesParMatch(-1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbInterceptionsParMatch(-1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeu5;
												$denominateur = $dureeDeJeu5/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbContresParMatch(-1) / $denominateur, 1, 1);
											}
						 ,function ($objet) {
												global $dureeDeJeuTous;
												$denominateur = $dureeDeJeuTous/ 2400;
												if ($denominateur == 0)
												{
													return "-";
												}
												return Tableur::ratio($objet->nbFautesParMatch(-1, 1) / $denominateur, 1, 1);
											}
						 )
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1));
			print ("<BR/>");
			print ("<BR/>");
				  
				  
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"joueur.modifie.joueur\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"modifieJoueur();\">Modification du joueur</DIV>");	
			}
			
			print ("<DIV class=\"champ_a_cliquer\" id=\"joueur.retour.joueurs\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourJoueurs();\">Retour à la liste des joueurs</DIV>");	
			
			print ("</DIV>");

			?>
		</DIV>
	</DIV>
	
<?php

include_once 'pied_de_page.php';

?>
