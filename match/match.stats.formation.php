<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Stat.php');
include_once ($RACINE . 'utils/Tableur.php');


if (isset($_POST["formationId"]) && $_POST["formationId"] > 0)
{
	$formation = Formation::recup($_POST["formationId"]);
	$equipe = Equipe::recup($formation->get("equipe_id"));
	$formation_joueurs = $formation->getFormationJoueurs();
	$match = Match::recup($formation->get("match_id"));
  $niveau_stats = $match->get("niveau_stats");
  
	if ($formation_joueurs)
	{
		$ratio = 1;
		if (isset($_POST["tempsDeJeuId"]) && $_POST["tempsDeJeuId"] > 0)
		{
			$temps_de_jeu_id = $_POST["tempsDeJeuId"];
			$match_id = -1;
		}
		else
		{
			$match_id = $match->get("id");
			$temps_de_jeu_id = -1;
			
			if (isset($_POST["modeCalcul"]) && $_POST["modeCalcul"] > 0)
			{
				$match = Match::recup($match->get("id"));
				$ratio = $_POST["modeCalcul"] / $match->recupDuree();
			}
		}
	
		Tableur::dessineTableau($formation_joueurs, true
				  , array(" ", "MIN", "PTS", "FGM", "FGA", "FG%", "3FGM", "3FGA", "3FG%", "FTM", "FTA", "FT%", "OREB", "DREB", "REB", "AST", "STL", "BLK", "PF")
				  , array(
              function ($objet) {
                        global $utilisateur_en_cours;
												$joueur = Joueur::recup($objet["joueur_id"]);
												$retour = $joueur->get("pseudo");
												if (isset($utilisateur_en_cours))
												{
													$retour = $retour . " (" . $joueur->get("prenom") . " " . $joueur->get("nom") . ")";
												}
												return $retour;
											}
						 ,function ($objet) {
												return "-";
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
                        return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("POINT", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
                        return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-REUSSI", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												$denominateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0);
												if ($denominateur == 0 || $niveau_stats < 2)
												{
													return "-";
												}
												$numerateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-REUSSI", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3-REUSSI", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												$denominateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0);
												if ($denominateur == 0 || $niveau_stats < 2)
												{
													return "-";
												}
												$numerateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3-REUSSI", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {												
                        global $temps_de_jeu_id, $match_id, $ratio;
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1-REUSSI", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												$denominateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0);
												if ($denominateur == 0 || $niveau_stats < 2)
												{
													return "-";
												}
												$numerateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1-REUSSI", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("REBOND-1", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("REBOND-2", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("REBOND", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("PASSE", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("CONTRE", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("INTERCEPTION", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("FAUTE", $objet["joueur_id"], -1, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 )
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
				  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
				  , array("", "Nombre de minutes jouées", "Nombre de points marqués", "Nombre de paniers marqués, à 2 et à 3 points", "Nombre de paniers tentés", "Pourcentage de réussite au shoot", "Nombre de paniers marqués à 3 points", "Nombre de paniers tentés à 3 points", "Pourcentage de réussite au shoot à 3 points", "Nombre de lancés francs marqués", "Nombre de lancés francs tentés", "Pourcentage de réussite au lancé franc", "Nombre de rebonds offensifs", "Nombre de rebonds défensifs", "Nombre de rebonds total", "Nombre de passes décisives réalisées", "Nombre d'interceptions de balle réalisées", "Nombre de contres effectués", "Nombre de fautes commises")
				  );
	}
	else
	{
		print ("Aucun joueur inscrit dans l'équipe");
		print ("<BR/>");
	}
	
	Tableur::dessineTableau([$equipe->get("id")], false
		  , null
		  , array(function ($objet) {
                        return "TOTAL";
                      }
					   ,function ($objet) {
											  return "-";
								      }
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
                        return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("POINT", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
                        return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-REUSSI", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												$denominateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0);
												if ($denominateur == 0 || $niveau_stats < 2)
												{
													return "-";
												}
												$numerateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-REUSSI", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3-REUSSI", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												$denominateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0);
												if ($denominateur == 0 || $niveau_stats < 2)
												{
													return "-";
												}
												$numerateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-3-REUSSI", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {												
                        global $temps_de_jeu_id, $match_id, $ratio;
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1-REUSSI", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												$denominateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0);
												if ($denominateur == 0 || $niveau_stats < 2)
												{
													return "-";
												}
												$numerateur = Stat::getSommeOrNullValue(Stat::recupParSpecificite("SHOOT-1-REUSSI", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("REBOND-1", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("REBOND-2", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("REBOND", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("PASSE", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("CONTRE", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $niveau_stats, $match_id, $ratio;
												if ($niveau_stats < 2)
												{
													return "-";
												}
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("INTERCEPTION", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
						 ,function ($objet) {
												global $temps_de_jeu_id, $match_id, $ratio;
												return Tableur::ratio(Stat::getSommeOrNullValue(Stat::recupParSpecificite("FAUTE", -1, $objet, $temps_de_jeu_id, $match_id, -1, -1), 0), $ratio, 1);
											}
				 )
		  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td"
		  , array(5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1));
}
else
{
	print ("<DIV class=\"messageErreur\" >Erreur : Aucun identifiant de formation n'a été passé au serveur</DIV>");
}

?>
	