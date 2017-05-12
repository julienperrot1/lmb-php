<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'utils/Tableur.php');


if (isset($_POST["formationId"]) && $_POST["formationId"] > 0)
{
	$formation = Formation::recup($_POST["formationId"]);
	$equipe = Equipe::recup($formation->get("equipe_id"));
	$formation_joueurs = $formation->getFormationJoueurs();
	$match = Match::recup($formation->get("match_id"));
	if ($formation_joueurs)
	{
		$ratio = 1;
		if (isset($_POST["tempsDeJeuId"]) && $_POST["tempsDeJeuId"] > 0)
		{
			$temps_de_jeu_id = $_POST["tempsDeJeuId"];
		}
		else
		{
			$temps_de_jeu_id = null;
			
			if (isset($_POST["modeCalcul"]) && $_POST["modeCalcul"] > 0)
			{
				$match = Match::recup($match->get("id"));
				$ratio = $_POST["modeCalcul"] / $match->recupDuree();
			}
		}
	
		Tableur::dessineTableau($formation_joueurs, true
				  , array(" ", "MIN", "PTS", "FGM", "FGA", "FG%", "3FGM", "3FGA", "3FG%", "FTM", "FTA", "FT%", "OREB", "DREB", "REB", "AST", "STL", "BLK", "PF")
				  , array(function ($objet) {	global $utilisateur_en_cours;
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
												global $match, $temps_de_jeu_id, $ratio;
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio(($joueur->nbShootsParMatch($match->get("id"), 1, true, $temps_de_jeu_id)
												      + ($joueur->nbShootsParMatch($match->get("id"), 2, true, $temps_de_jeu_id) * 2)
													  + ($joueur->nbShootsParMatch($match->get("id"), 3, true, $temps_de_jeu_id) * 3)), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbShootsParMatch($match->get("id"), 5, true, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbShootsParMatch($match->get("id"), 5, null, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												$joueur = Joueur::recup($objet["joueur_id"]);
												$denominateur = $joueur->nbShootsParMatch($match->get("id"), 5, null, $temps_de_jeu_id);
												if ($denominateur == 0 || $match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$numerateur = $joueur->nbShootsParMatch($match->get("id"), 5, true, $temps_de_jeu_id);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbShootsParMatch($match->get("id"), 3, true, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbShootsParMatch($match->get("id"), 3, null, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												$joueur = Joueur::recup($objet["joueur_id"]);
												$denominateur = $joueur->nbShootsParMatch($match->get("id"), 3, null, $temps_de_jeu_id);
												if ($denominateur == 0 || $match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$numerateur = $joueur->nbShootsParMatch($match->get("id"), 3, true, $temps_de_jeu_id);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbShootsParMatch($match->get("id"), 1, true, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbShootsParMatch($match->get("id"), 1, null, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												$joueur = Joueur::recup($objet["joueur_id"]);
												$denominateur = $joueur->nbShootsParMatch($match->get("id"), 1, null, $temps_de_jeu_id);
												if ($denominateur == 0 || $match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$numerateur = $joueur->nbShootsParMatch($match->get("id"), 1, true, $temps_de_jeu_id);
												return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbRebondsParMatch($match->get("id"), 1, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbRebondsParMatch($match->get("id"), 2, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbRebondsParMatch($match->get("id"), 0, $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbPassesParMatch($match->get("id"), $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbInterceptionsParMatch($match->get("id"), $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio;
												if ($match->get("niveau_stats") < 2)
												{
													return "-";
												}
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbContresParMatch($match->get("id"), $temps_de_jeu_id), $ratio, 1);
											}
						 ,function ($objet) {
												global $match, $temps_de_jeu_id, $ratio, $ACTION_TYPE_FAUTE;
												$joueur = Joueur::recup($objet["joueur_id"]);
												return Tableur::ratio($joueur->nbFautesParMatch($match->get("id"), 1, $temps_de_jeu_id), $ratio, 1);
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
	
	Tableur::dessineTableau([$equipe], false
		  , null
		  , array(function ($objet) {
										return "TOTAL";
									}
					 ,function ($objet) {
											return "-";
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio(($objet->nbShootsParMatch($match->get("id"), 1, true, $temps_de_jeu_id)
												  + ($objet->nbShootsParMatch($match->get("id"), 2, true, $temps_de_jeu_id) * 2)
												  + ($objet->nbShootsParMatch($match->get("id"), 3, true, $temps_de_jeu_id) * 3)), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbShootsParMatch($match->get("id"), 5, true, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbShootsParMatch($match->get("id"), 5, null, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											$denominateur = $objet->nbShootsParMatch($match->get("id"), 5, null, $temps_de_jeu_id);
											if ($denominateur == 0)
											{
												return "-";
											}
											$numerateur = $objet->nbShootsParMatch($match->get("id"), 5, true, $temps_de_jeu_id);
											return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbShootsParMatch($match->get("id"), 3, true, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbShootsParMatch($match->get("id"), 3, null, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											$denominateur = $objet->nbShootsParMatch($match->get("id"), 3, null, $temps_de_jeu_id);
											if ($denominateur == 0)
											{
												return "-";
											}
											$numerateur = $objet->nbShootsParMatch($match->get("id"), 3, true, $temps_de_jeu_id);
											return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbShootsParMatch($match->get("id"), 1, true, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbShootsParMatch($match->get("id"), 1, null, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											$denominateur = $objet->nbShootsParMatch($match->get("id"), 1, null, $temps_de_jeu_id);
											if ($denominateur == 0)
											{
												return "-";
											}
											$numerateur = $objet->nbShootsParMatch($match->get("id"), 1, true, $temps_de_jeu_id);
											return Tableur::ratio($numerateur / $denominateur * 100, 1, 1) . "%";
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbRebondsParMatch($match->get("id"), 1, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbRebondsParMatch($match->get("id"), 2, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbRebondsParMatch($match->get("id"), 0, $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbPassesParMatch($match->get("id"), $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbInterceptionsParMatch($match->get("id"), $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio;
											return Tableur::ratio($objet->nbContresParMatch($match->get("id"), $temps_de_jeu_id), $ratio, 1);
										}
					 ,function ($objet) {
											global $match, $temps_de_jeu_id, $ratio, $ACTION_TYPE_FAUTE;
											return Tableur::ratio($objet->nbFautesParMatch($match->get("id"), 1, $temps_de_jeu_id), $ratio, 1);
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
	