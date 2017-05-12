function chargeListeLigues() {
	ajaxPost('ligues/ligues.recherche.ligue.php', 'texteRecherche=' + document.getElementById('ligues.recherche.ligue').value, 'ligues.liste');
}

function creeLigue() {
	window.location.href = 'ligue.creamodi.php';
}

function modifieLigue(ligueId) {
	window.location.href = 'ligue.creamodi.php?id=' + ligueId;
}

function supprimeLigue(ligueId) {
	if (confirm('Attention, ceci supprimera definitvement la ligue choisie (' + ligueId + ')')) {
		ajaxPost('ligues/ligues.supprime.ligue.php', 'id=' + ligueId, 'ligues.message');
	}
}

function afficheLigue(ligueId) {
	window.location.href = 'ligue.php?id=' + ligueId;
}