function creeMatch() {
	if (document.getElementById('match.creamodi.poule_id'))
	{
		phaseSpecifique = '&pouleId=' + document.getElementById('match.creamodi.poule_id').value;
		redirection = RACINE + 'poule.php?id=' + document.getElementById('match.creamodi.poule_id').value;
	}
	else if (document.getElementById('match.creamodi.phase_tableau_id'))
	{
		phaseSpecifique = '&phaseTableauId=' + document.getElementById('match.creamodi.phase_tableau_id').value;
		phaseSpecifique = phaseSpecifique + '&nb_periode_match=' + document.getElementById('match.creamodi.nb_periode_match').value + '&duree_periode_match_minutes=' + document.getElementById('match.creamodi.duree_periode_match_minutes').value + '&duree_periode_match_secondes=' + document.getElementById('match.creamodi.duree_periode_match_secondes').value;
		redirection = RACINE + 'tournoi.php?id=' + document.getElementById('match.creamodi.tournoi_id').value;
	}
	else
	{	
		phaseSpecifique = '';
		redirection = RACINE + 'index.php';
	}

	if (document.getElementById('match.creamodi.formation1.precedente').checked == true) {
		formation1 = 1;
	} else {
		formation1 = 0;
	}
	if (document.getElementById('match.creamodi.formation2.precedente').checked == true) {
		formation2 = 1;
	} else {
		formation2 = 0;
	}
	
	ajaxPost('match/match.creamodi.valide.php', 'libelle=' + document.getElementById('match.creamodi.libelle').value + '&date=' + document.getElementById('match.creamodi.date_annee').value + '-' + document.getElementById('match.creamodi.date_mois').value + '-' + document.getElementById('match.creamodi.date_jour').value + '&equipe1Id=' + document.getElementById('match.creamodi.equipe1_id').value + '&equipe2Id=' + document.getElementById('match.creamodi.equipe2_id').value + '&formation1=' + formation1 + '&formation2=' + formation2 + phaseSpecifique, 'match.creamodi.message', redirection);
}

function modifieMatch() {
	if (document.getElementById('match.creamodi.poule_id'))
	{
		redirection = RACINE + 'poule.php?id=' + document.getElementById('match.creamodi.poule_id').value;
	}
	if (document.getElementById('match.creamodi.tournoi_id'))
	{
		redirection = RACINE + 'tournoi.php?id=' + document.getElementById('match.creamodi.tournoi_id').value;
	}
	else
	{	
		redirection = RACINE + 'index.php';
	}
	
	ajaxPost('match/match.creamodi.valide.php', 'id=' + document.getElementById('match.creamodi.id').value + '&libelle=' + document.getElementById('match.creamodi.libelle').value + '&date=' + document.getElementById('match.creamodi.date_annee').value + '-' + document.getElementById('match.creamodi.date_mois').value + '-' + document.getElementById('match.creamodi.date_jour').value, 'match.creamodi.message', redirection);
}

function retourPoule() {
	window.location.href = 'poule.php?id=' + document.getElementById('match.creamodi.poule_id').value;
}

function retourTournoi() {
	window.location.href = 'tournoi.php?id=' + document.getElementById('match.creamodi.tournoi_id').value;
}
