function creeClassementTournoi() {
	ajaxPost('classement_tournoi/classement_tournoi.creamodi.valide.php', 'place=' + document.getElementById('classement_tournoi.creamodi.place').value + '&regle_equipe=' + document.getElementById('classement_tournoi.creamodi.regle_equipe').value + '&tournoi_id=' + document.getElementById('classement_tournoi.creamodi.tournoi_id').value, 'classement_tournoi.creamodi.message', RACINE + 'tournoi.php?id=' + document.getElementById('classement_tournoi.creamodi.tournoi_id').value);
}

function modifieClassementTournoi() {
	ajaxPost('classement_tournoi/classement_tournoi.creamodi.valide.php', 'id=' + document.getElementById('classement_tournoi.creamodi.id').value + '&place=' + document.getElementById('classement_tournoi.creamodi.place').value + '&regle_equipe=' + document.getElementById('classement_tournoi.creamodi.regle_equipe').value + '&points=' + document.getElementById('classement_tournoi.creamodi.points').value + '&tournoi_id=' + document.getElementById('classement_tournoi.creamodi.tournoi_id').value, 'classement_tournoi.creamodi.message', RACINE + 'tournoi.php?id=' + document.getElementById('classement_tournoi.creamodi.tournoi_id').value);
}

function retourTournoi() {
	window.location.href = 'tournoi.php?id=' + document.getElementById('classement_tournoi.creamodi.tournoi_id').value;
}
