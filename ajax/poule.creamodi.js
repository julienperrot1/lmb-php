function creePoule() {
	if (document.getElementById('poule.creamodi.etat.1').checked == true) {
		etat = 1;
	} else if (document.getElementById('poule.creamodi.etat.2').checked == true) {
		etat = 2;
	} else if (document.getElementById('poule.creamodi.etat.3').checked == true) {
		etat = 3;
	} else {
		etat = 0;
	}
	
	ajaxPost('poule/poule.creamodi.valide.php', 'libelle=' + document.getElementById('poule.creamodi.libelle').value + '&points_victoire=' + document.getElementById('poule.creamodi.points_victoire').value + '&points_defaite=' + document.getElementById('poule.creamodi.points_defaite').value + '&points_nul=' + document.getElementById('poule.creamodi.points_nul').value + '&goal_average_ecart_max=' + document.getElementById('poule.creamodi.goal_average_ecart_max').value + '&etat=' + etat + '&phasePoulesId=' + document.getElementById('poule.creamodi.phase_poules_id').value, 'poule.creamodi.message', RACINE + 'tournoi.php?id=' + document.getElementById('poule.creamodi.tournoi_id').value);
}

function modifiePoule() {
	if (document.getElementById('poule.creamodi.etat.1').checked == true) {
		etat = 1;
	} else if (document.getElementById('poule.creamodi.etat.2').checked == true) {
		etat = 2;
	} else if (document.getElementById('poule.creamodi.etat.3').checked == true) {
		etat = 3;
	} else {
		etat = 0;
	}
	
	ajaxPost('poule/poule.creamodi.valide.php', 'id=' + document.getElementById('poule.creamodi.id').value + '&libelle=' + document.getElementById('poule.creamodi.libelle').value + '&points_victoire=' + document.getElementById('poule.creamodi.points_victoire').value + '&points_defaite=' + document.getElementById('poule.creamodi.points_defaite').value + '&points_nul=' + document.getElementById('poule.creamodi.points_nul').value + '&goal_average_ecart_max=' + document.getElementById('poule.creamodi.goal_average_ecart_max').value + '&etat=' + etat + '&phasePoulesId=' + document.getElementById('poule.creamodi.phase_poules_id').value, 'poule.creamodi.message', RACINE + 'tournoi.php?id=' + document.getElementById('poule.creamodi.tournoi_id').value);
}

function retourTournoi() {
	window.location.href = 'tournoi.php?id=' + document.getElementById('poule.creamodi.tournoi_id').value;
}
