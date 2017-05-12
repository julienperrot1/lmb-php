function chargeListePhases() {
	ajaxPost('tournoi/tournoi.phases.php', 'tournoiId=' + document.getElementById('tournoi.id').value, 'tournoi.phases.liste');
}

function chargeClassement() {
	ajaxPost('tournoi/tournoi.classement.php', 'tournoiId=' + document.getElementById('tournoi.id').value, 'tournoi.classement');
}

function chargeListeEquipes() {
	ajaxPost('tournoi/tournoi.equipes.php', 'tournoiId=' + document.getElementById('tournoi.id').value, 'tournoi.equipes.liste');
}

function creePhase() {
	window.location.href = 'phase.creamodi.php?tournoiId=' + document.getElementById('tournoi.id').value;
}

function modifieTournoi() {
	window.location.href = 'tournoi.creamodi.php?id=' + document.getElementById('tournoi.id').value;
}

function retourLigue() {
	window.location.href = 'ligue.php?id=' + document.getElementById('ligue.id').value;
}

function rechercheEquipe() {
	ajaxPost('tournoi/tournoi.equipes.recherche.equipe.php', 'texteRecherche=' + document.getElementById('tournoi.equipes.recherche.equipe').value, 'tournoi.equipes.recherche.equipe.resultat');
}

function ajouteEquipe(equipeId) {
	ajaxPost('tournoi/tournoi.equipes.ajoute.equipe.php', 'tournoiId=' + document.getElementById('tournoi.id').value + '&equipeId=' + equipeId, 'tournoi.equipes.recherche.equipe.resultat');
}
	
function enleveEquipe(equipeId) {
	if (confirm('Attention, ceci supprimera la participation de l\'équipe au tournoi')) {
		ajaxPost('tournoi/tournoi.equipes.enleve.equipe.php', 'tournoiId=' + document.getElementById('tournoi.id').value + '&equipeId=' + equipeId, 'tournoi.equipes.recherche.equipe.resultat');
	}
}

function ajouteEquipePoule(pouleId) {
	ajaxPost('poule/poule.equipes.ajoute.equipe.php', 'pouleId=' + pouleId + '&equipeId=' + document.getElementById('tournoi.phases.poule.' + pouleId + '.equipe.ajoute').value, 'tournoi.phases.message');
}

function ajouteReglePoule(pouleId) {
	ajaxPost('poule/poule.equipes.ajoute.regle.php', 'pouleId=' + pouleId + '&regle=' + document.getElementById('tournoi.phases.poule.' + pouleId + '.regle.ajoute').value, 'tournoi.phases.message');
}

function enleveEquipePoule(pouleId, equipeId) {
	ajaxPost('poule/poule.equipes.enleve.equipe.php', 'pouleId=' + pouleId + '&equipeId=' + equipeId, 'tournoi.phases.message');
}

function enleveReglePoule(pouleId, regle) {
	ajaxPost('poule/poule.equipes.enleve.regle.php', 'pouleId=' + pouleId + '&regle=' + regle, 'tournoi.phases.message');
}

function resoutReglesPoule(phasePoulesId) {
	if (confirm('Attention, ceci transformera définitvement toutes les régles de la phase en fonction des résultats actuels des phases précédentes')) {
		ajaxPost('phasePoules/phasePoules.regles.resout.php', 'phasePoulesId=' + phasePoulesId, 'tournoi.phases.message');
	}
}

function creePoule(phasePoulesId) {
	window.location.href = 'poule.creamodi.php?tournoiId=' + document.getElementById('tournoi.id').value + '&phasePoulesId=' + phasePoulesId;
}

function modifiePoule(pouleId, phasePoulesId) {
	window.location.href = 'poule.creamodi.php?id=' + pouleId + '&tournoiId=' + document.getElementById('tournoi.id').value + '&phasePoulesId=' + phasePoulesId;
}

function supprimePoule(pouleId) {
	if (confirm('Attention, ceci supprimera definitivement la poule choisie (' + pouleId + ')')) {
		ajaxPost('poule/poule.supprime.php', 'id=' + pouleId, 'tournoi.phases.message');
	}
}

function affichePoule(pouleId) {
	window.location.href = 'poule.php?id=' + pouleId;
}

function creePhase() {
	window.location.href = 'phase.creamodi.php?tournoiId=' + document.getElementById('tournoi.id').value;
}

function supprimePhase(phaseId) {
	if (confirm('Attention, ceci supprimera definitivement la phase choisie (' + phaseId + ')')) {
		ajaxPost('phase/phase.supprime.php', 'id=' + phaseId, 'tournoi.phases.message');
	}
}

function modifiePhase(phaseId) {
	window.location.href = 'phase.creamodi.php?tournoiId=' + document.getElementById('tournoi.id').value + '&id=' + phaseId;
}

function afficheMatch(matchId) {
	window.location.href = 'match.php?id=' + matchId + '&tournoiId=' + document.getElementById('tournoi.id').value;
}

function creeMatch(phaseTableauId) {
	window.location.href = 'match.creamodi.php?phaseTableauId=' + phaseTableauId + '&tournoiId=' + document.getElementById('tournoi.id').value;
}

function supprimeMatch(matchId) {
	if (confirm('Attention, ceci supprimera définitivement le match !')) {
		ajaxPost('match/match.supprime.php', 'matchId=' + matchId, 'tournoi.phases.message', 'tournoi.php?id=' + document.getElementById('tournoi.id').value);
	}
}

function modifieMatch(matchId) {
	window.location.href = 'match.creamodi.php?tournoiId=' + document.getElementById('tournoi.id').value + '&id=' + matchId;
}

function ajouteRegleMatch(matchId, numFormation) {
	ajaxPost('match/match.equipes.ajoute.regle.php', 'matchId=' + matchId + '&regle=' + document.getElementById('tournoi.phases.match.' + matchId + '.regle.ajoute').value + '&numFormation=' + numFormation, 'tournoi.phases.message');
}

function enleveRegleMatch(matchId, numFormation) {
	ajaxPost('match/match.equipes.enleve.regle.php', 'matchId=' + matchId + '&numFormation=' + numFormation, 'tournoi.phases.message');
}

function resoutReglesTableau(phaseTableauId) {
	if (confirm('Attention, ceci transformera définitvement les régles de la phase en fonction des résultats actuels des phases précédentes')) {
		ajaxPost('phaseTableau/phaseTableau.regles.resout.php', 'phaseTableauId=' + phaseTableauId, 'tournoi.phases.message');
	}
}

function creeClassementTournoi() {
	window.location.href = 'classement_tournoi.creamodi.php?tournoiId=' + document.getElementById('tournoi.id').value;
}

function supprimeClassementTournoi(classementTournoiId) {
	if (confirm('Attention, ceci supprimera definitivement le classement choisi (' + classementTournoiId + ')')) {
		ajaxPost('classement_tournoi/classement_tournoi.supprime.php', 'id=' + classementTournoiId, 'tournoi.classement.message');
	}
}

function modifieClassementTournoi(classementTournoiId) {
	window.location.href = 'classement_tournoi.creamodi.php?tournoiId=' + document.getElementById('tournoi.id').value + '&id=' + classementTournoiId;
}

function ajouteRegleClassement(classementTournoiId) {
	ajaxPost('classement_tournoi/classement_tournoi.ajoute.regle.php', 'id=' + classementTournoiId + '&regle=' + document.getElementById('tournoi.classement.' + classementTournoiId + '.regle.ajoute').value, 'tournoi.classement.message');
}

function enleveRegleClassement(classementTournoiId) {
	ajaxPost('classement_tournoi/classement_tournoi.enleve.regle.php', 'id=' + classementTournoiId, 'tournoi.classement.message');
}

function enleveEquipeClassement(classementTournoiId) {
	ajaxPost('classement_tournoi/classement_tournoi.enleve.equipe.php', 'id=' + classementTournoiId, 'tournoi.classement.message');
}

function resoutReglesClassement() {
	if (confirm('Attention, ceci transformera définitvement les régles du classement du tournoi en fonction des résultats actuels')) {
		ajaxPost('classement_tournoi/classement_tournoi.regles.resout.php', 'tournoiId=' + document.getElementById('tournoi.id').value, 'tournoi.classement.message');
	}
}

function fichesDeMatchsPdf(phaseId) {
	window.open('phase.pdf.php?id=' + phaseId);
}

