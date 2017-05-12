function valideSaisie() {
	specifique = '';
	for (index = 1; index <= 6; index++)
	{
		if (document.getElementById('match.saisie.rapide.points1.' + index)) {
			specifique = specifique + '&points1_' + index + '=' + document.getElementById('match.saisie.rapide.points1.' + index).value + '&points2_' + index + '=' + document.getElementById('match.saisie.rapide.points2.' + index).value + '&fautes1_' + index + '=' + document.getElementById('match.saisie.rapide.fautes1.' + index).value + '&fautes2_' + index + '=' + document.getElementById('match.saisie.rapide.fautes2.' + index).value;
		}
	}
	
	if (document.getElementById('match.saisie.rapide.poule_id')) {
		ajaxPost('match/match.saisie.rapide.valide.php', 'matchId=' + document.getElementById('match.saisie.rapide.match_id').value + specifique, 'match.saisie.rapide.message', RACINE + 'match.php?id=' + document.getElementById('match.saisie.rapide.match_id').value + '&pouleId=' + document.getElementById('match.saisie.rapide.poule_id').value);
	}
	if (document.getElementById('match.saisie.rapide.tournoi_id')) {
		ajaxPost('match/match.saisie.rapide.valide.php', 'matchId=' + document.getElementById('match.saisie.rapide.match_id').value + specifique, 'match.saisie.rapide.message', RACINE + 'match.php?id=' + document.getElementById('match.saisie.rapide.match_id').value + '&tournoiId=' + document.getElementById('match.saisie.rapide.tournoi_id').value);
	}
}

function retourMatch() {
	if (document.getElementById('match.saisie.rapide.poule_id')) {
		window.location.href = 'match.php?id=' + document.getElementById('match.saisie.rapide.match_id').value + '&pouleId=' + document.getElementById('match.saisie.rapide.poule_id').value;
	}
	if (document.getElementById('match.saisie.rapide.tournoi_id')) {
		window.location.href = 'match.php?id=' + document.getElementById('match.saisie.rapide.match_id').value + '&tournoiId=' + document.getElementById('match.saisie.rapide.tournoi_id').value;
	}
}