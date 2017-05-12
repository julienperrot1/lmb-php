function chargeListeEquipes() {
	ajaxPost('equipes/equipes.recherche.equipe.php', 'texteRecherche=' + document.getElementById('equipes.recherche.equipe').value, 'equipes.liste');
}

function creeEquipe() {
	window.location.href = 'equipe.creamodi.php';
}

function modifieEquipe(equipeId) {
	window.location.href = 'equipe.creamodi.php?id=' + equipeId;
}

function supprimeEquipe(equipeId) {
	if (confirm('Attention, ceci supprimera definitvement l\'équipe choisie (' + equipeId + ')')) {
		ajaxPost('equipes/equipes.supprime.equipe.php', 'id=' + equipeId, 'equipes.message');
	}
}

function afficheEquipe(equipeId) {
	window.location.href = 'equipe.php?id=' + equipeId;
}