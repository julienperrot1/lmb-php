function creeTournoi() {
	ajaxPost('tournoi/tournoi.creamodi.valide.php', 'libelle=' + document.getElementById('tournoi.creamodi.libelle').value + '&lieu=' + document.getElementById('tournoi.creamodi.lieu').value + '&nb_equipe_max=' + document.getElementById('tournoi.creamodi.nb_equipe_max').value + '&ligue_id=' + document.getElementById('tournoi.creamodi.ligue_id').value , 'tournoi.creamodi.message', RACINE + 'ligue.php?id=' + document.getElementById('tournoi.creamodi.ligue_id').value);
}

function modifieTournoi() {
	ajaxPost('tournoi/tournoi.creamodi.valide.php', 'id=' + document.getElementById('tournoi.creamodi.id').value + '&libelle=' + document.getElementById('tournoi.creamodi.libelle').value + '&lieu=' + document.getElementById('tournoi.creamodi.lieu').value + '&nb_equipe_max=' + document.getElementById('tournoi.creamodi.nb_equipe_max').value + '&ligue_id=' + document.getElementById('tournoi.creamodi.ligue_id').value , 'tournoi.creamodi.message', RACINE + 'ligue.php?id=' + document.getElementById('tournoi.creamodi.ligue_id').value);
}

function retourLigue() {
	window.location.href = 'ligue.php?id=' + document.getElementById('tournoi.creamodi.ligue_id').value;
}
