function creeTempsDeJeu() {
	if (document.getElementById('temps_de_jeu.creamodi.poule_id')) {
		ajaxPost('temps_de_jeu/temps_de_jeu.creamodi.valide.php', 'libelle=' + document.getElementById('temps_de_jeu.creamodi.libelle').value + '&dureeMinutes=' + document.getElementById('temps_de_jeu.creamodi.duree_minutes').value + '&dureeSecondes=' + document.getElementById('temps_de_jeu.creamodi.duree_secondes').value + '&matchId=' + document.getElementById('temps_de_jeu.creamodi.match_id').value , 'temps_de_jeu.creamodi.message', RACINE + 'match.php?id=' + document.getElementById('temps_de_jeu.creamodi.match_id').value + '&pouleId=' + document.getElementById('temps_de_jeu.creamodi.poule_id').value);
	}
	if (document.getElementById('temps_de_jeu.creamodi.tournoi_id')) {
		ajaxPost('temps_de_jeu/temps_de_jeu.creamodi.valide.php', 'libelle=' + document.getElementById('temps_de_jeu.creamodi.libelle').value + '&dureeMinutes=' + document.getElementById('temps_de_jeu.creamodi.duree_minutes').value + '&dureeSecondes=' + document.getElementById('temps_de_jeu.creamodi.duree_secondes').value + '&matchId=' + document.getElementById('temps_de_jeu.creamodi.match_id').value , 'temps_de_jeu.creamodi.message', RACINE + 'match.php?id=' + document.getElementById('temps_de_jeu.creamodi.match_id').value + '&tournoiId=' + document.getElementById('temps_de_jeu.creamodi.tournoi_id').value);
	}
}

function modifieTempsDeJeu() {
	if (document.getElementById('temps_de_jeu.creamodi.poule_id')) {
		ajaxPost('temps_de_jeu/temps_de_jeu.creamodi.valide.php', 'id=' + document.getElementById('temps_de_jeu.creamodi.id').value + '&libelle=' + document.getElementById('temps_de_jeu.creamodi.libelle').value + '&dureeMinutes=' + document.getElementById('temps_de_jeu.creamodi.duree_minutes').value + '&dureeSecondes=' + document.getElementById('temps_de_jeu.creamodi.duree_secondes').value + '&matchId=' + document.getElementById('temps_de_jeu.creamodi.match_id').value , 'temps_de_jeu.creamodi.message', RACINE + 'match.php?id=' + document.getElementById('temps_de_jeu.creamodi.match_id').value + '&pouleId=' + document.getElementById('temps_de_jeu.creamodi.poule_id').value);
	}
	if (document.getElementById('temps_de_jeu.creamodi.tournoi_id')) {
		ajaxPost('temps_de_jeu/temps_de_jeu.creamodi.valide.php', 'id=' + document.getElementById('temps_de_jeu.creamodi.id').value + '&libelle=' + document.getElementById('temps_de_jeu.creamodi.libelle').value + '&dureeMinutes=' + document.getElementById('temps_de_jeu.creamodi.duree_minutes').value + '&dureeSecondes=' + document.getElementById('temps_de_jeu.creamodi.duree_secondes').value + '&matchId=' + document.getElementById('temps_de_jeu.creamodi.match_id').value , 'temps_de_jeu.creamodi.message', RACINE + 'match.php?id=' + document.getElementById('temps_de_jeu.creamodi.match_id').value + '&tournoiId=' + document.getElementById('temps_de_jeu.creamodi.tournoi_id').value);
	}
}

function retourMatch() {
	if (document.getElementById('temps_de_jeu.creamodi.poule_id')) {
		window.location.href = 'match.php?id=' + document.getElementById('temps_de_jeu.creamodi.match_id').value + '&pouleId=' + document.getElementById('temps_de_jeu.creamodi.poule_id').value;
	}
	if (document.getElementById('temps_de_jeu.creamodi.tournoi_id')) {
		window.location.href = 'match.php?id=' + document.getElementById('temps_de_jeu.creamodi.match_id').value + '&tournoiId=' + document.getElementById('temps_de_jeu.creamodi.tournoi_id').value;
	}
}
