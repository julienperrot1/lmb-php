function retourMatch() {
	if (document.getElementById('match.stats.poule_id')) {
		window.location.href = 'match.php?id=' + document.getElementById('match.stats.match_id').value + '&pouleId=' + document.getElementById('match.stats.poule_id').value;
	}
	if (document.getElementById('match.stats.tournoi_id')) {
		window.location.href = 'match.php?id=' + document.getElementById('match.stats.match_id').value + '&tournoiId=' + document.getElementById('match.stats.tournoi_id').value;
	}
}

function chargeFormation1() {
	ajaxPost('match/match.stats.formation.php', 'formationId=' + document.getElementById('match.stats.formation1.id').value + '&tempsDeJeuId=' + document.getElementById('match.stats.tempsDeJeu').value + '&modeCalcul=' + document.getElementById('match.stats.modeCalcul').value, 'match.stats.formation1');
}

function chargeFormation2() {
	ajaxPost('match/match.stats.formation.php', 'formationId=' + document.getElementById('match.stats.formation2.id').value + '&tempsDeJeuId=' + document.getElementById('match.stats.tempsDeJeu').value + '&modeCalcul=' + document.getElementById('match.stats.modeCalcul').value, 'match.stats.formation2');
}

function activeModeCalcul() {
	if (document.getElementById('match.stats.tempsDeJeu').value == -1) {
		document.getElementById('match.stats.modeCalcul').disabled = false;
	} else {
		document.getElementById('match.stats.modeCalcul').disabled = true;
	} 
}
