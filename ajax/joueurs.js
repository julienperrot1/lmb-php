function chargeListeJoueurs() {
	ajaxPost('joueurs/joueurs.recherche.joueur.php', 'texteRecherche=' + document.getElementById('joueurs.recherche.joueur').value, 'joueurs.liste');
}

function creeJoueur() {
	window.location.href = 'joueur.creamodi.php';
}

function modifieJoueur(joueurId) {
	window.location.href = 'joueur.creamodi.php?id=' + joueurId;
}

function supprimeJoueur(joueurId) {
	if (confirm('Attention, ceci supprimera definitivement le joueur choisi (' + joueurId + ')')) {
		ajaxPost('joueurs/joueurs.supprime.joueur.php', 'id=' + joueurId, 'joueurs.message');
	}
}

function afficheJoueur(joueurId) {
	window.location.href = 'joueur.php?id=' + joueurId;
}