function chargeListeTournois() {
	ajaxPost('ligue/ligue.recherche.tournoi.php', 'ligueId=' + document.getElementById('ligue.id').value, 'tournois.liste');
}

function creeTournoi() {
	window.location.href = 'tournoi.creamodi.php?ligueId=' + document.getElementById('ligue.id').value;
}

function modifieTournoi(tournoiId) {
	window.location.href = 'tournoi.creamodi.php?id=' + tournoiId;
}

function supprimeTournoi(tournoiId) {
	if (confirm('Attention, ceci supprimera definitvement le tournoi choisi (' + tournoiId + ')')) {
		ajaxPost('ligue/ligue.supprime.tournoi.php', 'id=' + tournoiId, 'ligue.tournois.message');
	}
}

function afficheTournoi(tournoiId) {
	window.location.href = 'tournoi.php?id=' + tournoiId;
}

function modifieLigue(ligueId) {
	window.location.href = 'ligue.creamodi.php?id=' + ligueId;
}

function retourLigues() {
	window.location.href = 'ligues.php';
}