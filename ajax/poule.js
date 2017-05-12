function afficheMatch(matchId) {
	window.location.href = 'match.php?id=' + matchId + '&pouleId=' + document.getElementById('poule.id').value;
}

function creeMatch(equipe1Id, equipe2Id) {
	if (confirm('Voulez vous créer le match manquant ?')) {
		window.location.href = 'match.creamodi.php?pouleId=' + document.getElementById('poule.id').value + '&equipe1Id=' + equipe1Id + '&equipe2Id=' + equipe2Id;
	}
}

function retourTournoi() {
	window.location.href = 'tournoi.php?id=' + document.getElementById('tournoi.id').value;
}

function valideDepartage() {
	ajaxPost('poule/poule.departage.valide.php', 'pouleId=' + document.getElementById('poule.id').value + '&equipeId=' + document.getElementById('poule.departage.equipe_id').value + '&points=' + document.getElementById('poule.departage.points').value, 'poule.departage.message', 'poule.php?id=' + document.getElementById('poule.id').value);
}

function creeMatchs() {
	if (confirm('Ceci créera automatiquement tous les matchs de la poule : Etes vous sûr ?')) {
		ajaxPost('poule/poule.cree.matchs.php', 'pouleId=' + document.getElementById('poule.id').value, 'poule.message', 'poule.php?id=' + document.getElementById('poule.id').value);
	}
}

function terminePoule() {
	ajaxPost('poule/poule.termine.php', 'pouleId=' + document.getElementById('poule.id').value, 'poule.message', 'poule.php?id=' + document.getElementById('poule.id').value);
}

function fichesDeMatchsPdf() {
	window.open('poule.pdf.php?id=' + document.getElementById('poule.id').value);
}
