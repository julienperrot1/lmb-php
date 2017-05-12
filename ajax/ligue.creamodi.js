function creeLigue() {
	ajaxPost('ligue/ligue.creamodi.valide.php', 'libelle=' + document.getElementById('ligue.creamodi.libelle').value + '&type=' + document.getElementById('ligue.creamodi.type').value + '&nb_tournoi_class=' + document.getElementById('ligue.creamodi.nb_tournoi_class').value, 'ligue.creamodi.message', RACINE + 'ligues.php');
}

function modifieLigue() {
	ajaxPost('ligue/ligue.creamodi.valide.php', 'id=' + document.getElementById('ligue.creamodi.id').value + '&libelle=' + document.getElementById('ligue.creamodi.libelle').value + '&type=' + document.getElementById('ligue.creamodi.type').value + '&nb_tournoi_class=' + document.getElementById('ligue.creamodi.nb_tournoi_class').value, 'ligue.creamodi.message', RACINE + 'ligues.php');
}

function retourLigues() {
	window.location.href = 'ligues.php';
}
