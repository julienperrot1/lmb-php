<?php

include_once 'menu.php';

include_once ($RACINE . 'modele/TempsDeJeu.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'modele/Phase.php');
include_once ($RACINE . 'modele/PhasePoules.php');

?>

<SCRIPT src="ajax/match.js" type="text/javascript"></SCRIPT>

<DIV class="corps">
	<DIV class="pleineLargeur">
		<DIV class="titre">
			<?php
			
			$match = Match::recup($_GET["id"]);
			
			if ($match->get("formation1_id") && $match->get("formation1_id") > 0)
			{
				print ("<INPUT id=\"match.formation1.id\" type=\"hidden\" value=\"" . $match->get("formation1_id") . "\">");
			}
			else
			{
				print ("<INPUT id=\"match.formation1.id\" type=\"hidden\" value=\"-1\">");
			}
			
			if ($match->get("regle_formation1") && $match->get("regle_formation1") != "")
			{
				print ("<INPUT id=\"match.formation1.regle\" type=\"hidden\" value=\"" . $match->get("regle_formation1") . "\">");
			}
			else
			{
				print ("<INPUT id=\"match.formation1.regle\" type=\"hidden\" value=\"-1\">");
			}
			
			if ($match->get("formation2_id") && $match->get("formation2_id") > 0)
			{
				print ("<INPUT id=\"match.formation2.id\" type=\"hidden\" value=\"" . $match->get("formation2_id") . "\">");
			}
			else
			{
				print ("<INPUT id=\"match.formation2.id\" type=\"hidden\" value=\"-1\">");
			}
			
			if ($match->get("regle_formation2") && $match->get("regle_formation2") != "")
			{
				print ("<INPUT id=\"match.formation2.regle\" type=\"hidden\" value=\"" . $match->get("regle_formation2") . "\">");
			}
			else
			{
				print ("<INPUT id=\"match.formation2.regle\" type=\"hidden\" value=\"-1\">");
			}
			
			if (isset($_GET["pouleId"]))
			{
				$poule = Poule::recup($_GET["pouleId"]);
				$phase_poules = PhasePoules::recup($poule->get("phase_poules_id"));
				$phase = Phase::recup($phase_poules->get("phase_id"));
				print ($phase->get("libelle") . " - " . $poule->get("libelle") . " - ");
				print ("<INPUT id=\"poule.id\" type=\"hidden\" value=\"" . $_GET["pouleId"] . "\">");
			}
			
			if (isset($_GET["tournoiId"]))
			{
				print ("<INPUT id=\"tournoi.id\" type=\"hidden\" value=\"" . $_GET["tournoiId"] . "\">");
			}
			
			print ($match->get("libelle") . " (" . $match->get("date") . ")");
			print ("<INPUT id=\"match.id\" type=\"hidden\" value=\"" . $match->get("id") . "\">");
			print ("<BR/>");
			
			if (isset($_GET["pouleId"]))
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"match.retour.poule\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourPoule();\">Retour à la poule</DIV>");	
			}
			
			if (isset($_GET["tournoiId"]))
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"match.retour.tournoi\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"retourTournoi();\">Retour au tournoi</DIV>");	
			}
		
					
			print ("<DIV class=\"champ_a_cliquer\" id=\"match.feuille_match.pdf\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"ficheDeMatchPdf();\">Imprimer la fiche de match</DIV>");	
			
			print ("<DIV class=\"champ_a_cliquer\" id=\"match.stats\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"statsMatch();\">Statistiques</DIV>");	
			
			if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"match.saisie.rapide\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"saisieRapide();\">Saisie rapide des résultats</DIV>");	
									
				print ("<DIV class=\"champ_a_cliquer\" id=\"match.supprime\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"supprimeMatch();\">Supprimer le match</DIV>");	
			}
			
			print ("<BR/>");
			print ("<BR/>");
			
			?>
			
			<DIV id="match.message" class="texte">
			</DIV>
		</DIV>
	</DIV>
	
	<DIV class="colonne25">
		<DIV class="soustitre">Equipe 1</DIV>
		<BR/>
		<DIV id="match.formation1" class="texte">
		</DIV>
		<BR/>
		<BR/>
		
		<DIV class="soustitre">Arbitres</DIV>
		<DIV id="match.arbitres" class="texte">
		</DIV>
	</DIV>
	
	<DIV class="colonne50">
		<DIV class="texte">
			<?php
			
			$duree = $match->recupDuree();
			$duree_minutes = floor($duree / 60);
			$duree_secondes = $duree - ($duree_minutes * 60);
			print ("Match de " . $duree_minutes . " minutes");
			if ($duree_secondes > 0)
			{
				print (" et " . $duree_secondes . " secondes");
			}
			
			?>
		</DIV>
			
		<DIV class="texte">
			Temps de jeu en cours : 
			<?php
			
			$temps_de_jeux = TempsDeJeu::recupParChamp("match_id", $match->get("id"), "ordre_temporel ASC");
			if ($temps_de_jeux)
			{
				print ("<SELECT id=\"match.tempsDeJeu\" onchange=\"chargeChronometre(); arreteChronometre(); chargeScores(); videAction(); chargeActionEnCours(); chargeResume()\">");
				foreach($temps_de_jeux as $temps_de_jeu)
				{			
					print ("<OPTION value=\"" . $temps_de_jeu->get("id") . "\"");
					print (">" . $temps_de_jeu->get("libelle") . "</OPTION>");
				}
			}
			else
			{
				print ("Aucun temps de jeu n'a été défini pour ce match");
			}

			?>
			</SELECT>
			<BR/>
			<BR/>
		</DIV>
		<DIV id="match.chronometre" class="texte" style="height: 30px;">
		</DIV>
		<?php
		
		if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
		{
			if ($match->get("resultat") == $MATCH_RESULTAT_AJOUER && $temps_de_jeux)
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"match.chronometre.bouton\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"demarreChronometre();\">Go</DIV>");
			}
			else
			{
				print ("<DIV class=\"champ_a_cliquer\" id=\"match.chronometre.bouton\" onmouseover=\"this.style.cursor='pointer'\" hidden>Go</DIV>");
			}
			print ("<BR/>");
			print ("<DIV class=\"champ_a_cliquer\" id=\"match.ajoute.temps_de_jeu\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"ajouteTempsDeJeu();\">Ajouter un temps de jeu</DIV>");
		}
		
		?>
		<DIV id="match.scores" class="texte">
		</DIV>
		<BR/>
		
		<DIV id="match.action_en_cours" class="texte">
		</DIV>
		<BR/>
		
		<DIV id="match.resume" class="texte">
		</DIV>
	</DIV>
	
	<DIV class="colonne25">
		<DIV class="soustitre">Equipe 2</DIV>
		<BR/>
		<DIV id="match.formation2" class="texte">
		</DIV>
	</DIV>

	
<?php

include_once 'pied_de_page.php';

?>


<!-- Chargement des diverses parties de la page -->
<SCRIPT type="text/javascript">chargeFormation1();</SCRIPT>
<SCRIPT type="text/javascript">chargeFormation2();</SCRIPT>
<SCRIPT type="text/javascript">chargeArbitres();</SCRIPT>
<SCRIPT type="text/javascript">chargeChronometre();</SCRIPT>
<SCRIPT type="text/javascript">chargeScores();</SCRIPT>
<SCRIPT type="text/javascript">chargeActionEnCours();</SCRIPT>
<SCRIPT type="text/javascript">chargeResume();</SCRIPT>