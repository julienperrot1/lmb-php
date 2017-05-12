var chronoTimer;
var actionJoueurSourceId = null;
var actionFormationSourceId = null;
var actionJoueurCibleId = null;
var actionFormationCibleId = null;
var actionTempsMinutes = null;
var actionTempsSecondes = null;
var actionType = null;
var actionDetail = null;
var actionReussite = null;


function retourPoule() {
	window.location.href = 'poule.php?id=' + document.getElementById('poule.id').value;
}

function retourTournoi() {
	window.location.href = 'tournoi.php?id=' + document.getElementById('tournoi.id').value;
}

function chargeFormation1() {
	ajaxPost('match/match.formation.php', 'formationId=' + document.getElementById('match.formation1.id').value + '&regle=' + document.getElementById('match.formation1.regle').value, 'match.formation1');
}

function chargeFormation2() {
	ajaxPost('match/match.formation.php', 'formationId=' + document.getElementById('match.formation2.id').value + '&regle=' + document.getElementById('match.formation2.regle').value, 'match.formation2');
}

function chargeArbitres() {
	ajaxPost('match/match.arbitres.php', 'matchId=' + document.getElementById('match.id').value, 'match.arbitres');
}

function chargeChronometre() {
	ajaxPost('match/match.chronometre.php', 'tempsDeJeuId=' + document.getElementById('match.tempsDeJeu').value, 'match.chronometre');
}

function chargeScores() {
	ajaxPost('match/match.scores.php', 'matchId=' + document.getElementById('match.id').value + '&tempsDeJeuId=' + document.getElementById('match.tempsDeJeu').value , 'match.scores');
}

function chargeActionEnCours() {
	ajaxPost('match/match.action.php', 'joueurSourceId=' + actionJoueurSourceId + '&formationSourceId=' + actionFormationSourceId + '&joueurCibleId=' + actionJoueurCibleId + '&formationCibleId=' + actionFormationCibleId + '&tempsDeJeuId=' + document.getElementById('match.tempsDeJeu').value + '&tempsMinutes=' + actionTempsMinutes + '&tempsSecondes=' + actionTempsSecondes + '&actionType=' + actionType + '&actionDetail=' + actionDetail + '&actionReussite=' + actionReussite, 'match.action_en_cours');
}

function chargeResume() {
	ajaxPost('match/match.resume.php', 'tempsDeJeuId=' + document.getElementById('match.tempsDeJeu').value, 'match.resume');
}

function demarreChronometre() {
	chronoTimer = setTimeout(diminueChronometre, CHRONORAFRAICHISSEMENT * 1000, CHRONORAFRAICHISSEMENT);
	
	document.getElementById('match.chronometre.bouton').innerHTML = 'Stop';
	document.getElementById('match.chronometre.bouton').onclick = arreteChronometre;
}

function ajouteTempsDeJeu() {
	if (confirm('Etes vous sûr de vouloir quitter cette page ?')) {
		if (document.getElementById('poule.id') != null) {
			window.location.href = 'temps_de_jeu.creamodi.php?matchId=' + document.getElementById('match.id').value + '&pouleId=' + document.getElementById('poule.id').value;
		}
		if (document.getElementById('tournoi.id') != null) {
			window.location.href = 'temps_de_jeu.creamodi.php?matchId=' + document.getElementById('match.id').value + '&tournoiId=' + document.getElementById('tournoi.id').value;
		}
	}
}

function arreteChronometre() {
	clearTimeout(chronoTimer);
	
	document.getElementById('match.chronometre.bouton').innerHTML = 'Go';
	document.getElementById('match.chronometre.bouton').onclick = demarreChronometre;
}

function diminueChronometre(tempsADiminuer) {
	ajaxPost('match/match.chronometre.php', 'tempsDeJeuId=' + document.getElementById('match.tempsDeJeu').value + '&tempsADiminuer=' + tempsADiminuer, 'match.chronometre');
	chronoTimer = setTimeout(diminueChronometre, CHRONORAFRAICHISSEMENT * 1000, CHRONORAFRAICHISSEMENT);
}

function choixJoueur(formationId, joueurId) {
	if (!actionTempsMinutes && !actionTempsSecondes) {
		actionTempsMinutes = document.getElementById('match.chronometre.minutes').value;
		actionTempsSecondes = document.getElementById('match.chronometre.secondes').value;
	}
	
	if (actionJoueurSourceId == null) {
		actionJoueurSourceId = joueurId;
		actionFormationSourceId = formationId;
	} else if (actionJoueurCibleId == null) {
		actionJoueurCibleId = joueurId;
		actionFormationCibleId = formationId;
	}
	
	chargeActionEnCours();
}

function choixAction(type, detail, reussite) {
	if (!actionTempsMinutes && !actionTempsSecondes) {
		actionTempsMinutes = document.getElementById('match.chronometre.minutes').value;
		actionTempsSecondes = document.getElementById('match.chronometre.secondes').value;
	}
	
	actionType = type;
	actionDetail = detail;
	actionReussite = reussite;
	
	chargeActionEnCours();
}

function videAction() {
	actionJoueurSourceId = null;
	actionFormationSourceId = null;
	actionJoueurCibleId = null;
	actionFormationCibleId = null;
	actionTempsMinutes = null;
	actionTempsSecondes = null;
	actionType = null;
	actionDetail = null;
	actionReussite = null;
}

function supprimeAction(actionId) {
	if (confirm('Attention, ceci supprimera definitvement l\'action choisie (' + actionId + ')')) {
		ajaxPost('match/match.action.supprime.php', 'actionId=' + actionId, 'match.resume');
	}
}

function rechercheJoueur(formationId) {
	ajaxPost('match/match.formation.recherche.joueur.php', 'texteRecherche=' + document.getElementById('match.formation' + formationId + '.recherche.joueur').value + '&formationId=' + formationId, 'match.formation' + formationId + '.recherche.resultat');
}

function ajouteJoueurAFormation(joueurId, formationId) {
	numero = prompt('Choississez le numéro à attribuer au joueur :', '');
	if (numero == null) {
		numero == '';
	}
	if (numero.length > 16) {
		alert('Le numéro choisi est trop long (16 caractères maximum)');
		return;
	}
	
	ajaxPost('match/match.formation.ajoute.joueur.php', 'formationId=' + formationId + '&joueurId=' + joueurId + '&numero=' + numero, 'match.formation' + formationId + '.recherche.resultat');
}

function modifieNumeroJoueur(formationId, joueurId) {
	numero = prompt('Choississez le numéro à attribuer au joueur :', '');
	if (numero == null) {
		numero == '';
	}
	if (numero.length > 16) {
		alert('Le numéro choisi est trop long (16 caractères maximum)');
		return;
	}
	
	ajaxPost('match/match.formation.modifie.numero.joueur.php', 'formationId=' + formationId + '&joueurId=' + joueurId + '&numero=' + numero, 'match.formation' + formationId + '.recherche.resultat');
}
	
function enleveJoueurAFormation(joueurId, formationId) {
	ajaxPost('match/match.formation.enleve.joueur.php', 'formationId=' + formationId + '&joueurId=' + joueurId, 'match.formation' + formationId + '.recherche.resultat');
}

function afficheValideMatch() {
	arreteChronometre();
	document.getElementById('match.scores.actuel').hidden = true;
	document.getElementById('match.validation.match').hidden = false;
}

function annuleValideMatch() {
	document.getElementById('match.scores.actuel').hidden = false;
	document.getElementById('match.validation.match').hidden = true;
}

function valideMatch() {
	if (document.getElementById('match.validation.vainqueur.equipe1').checked == true) {
		resultat = MATCHRESULTATEQUIPE1;
	} else if (document.getElementById('match.validation.vainqueur.equipe2').checked == true) {
		resultat = MATCHRESULTATEQUIPE2;
	} else {
		resultat = MATCHRESULTATNUL;
	}
	
	ajaxPost('match/match.score.valide.php', 'matchId=' + document.getElementById('match.id').value + '&score1=' + document.getElementById('match.validation.score1').value + '&score2=' + document.getElementById('match.validation.score2').value + '&resultat=' + resultat, 'match.scores');
}

function supprimeMatch() {
	if (document.getElementById('poule.id')) {
		redirection = RACINE + 'poule.php?id=' + document.getElementById('poule.id').value;
	} else {	
		redirection = RACINE + 'index.php';
	}
	
	if (confirm('Attention, ceci supprimera définitivement le match !')) {
		ajaxPost('match/match.supprime.php', 'matchId=' + document.getElementById('match.id').value, 'match.message', redirection);
	}
}

function ficheDeMatchPdf() {
	window.open('match.pdf.php?id=' + document.getElementById('match.id').value);
}

function rechercheArbitre() {
	ajaxPost('match/match.arbitres.recherche.arbitre.php', 'texteRecherche=' + document.getElementById('match.arbitres.recherche.arbitre').value, 'match.arbitres.recherche.resultat');
}

function ajouteArbitre(joueurId) {
	ajaxPost('match/match.arbitres.ajoute.arbitre.php', 'matchId=' + document.getElementById('match.id').value + '&joueurId=' + joueurId, 'match.arbitres.recherche.resultat');
}

function enleveArbitre(numero) {
	ajaxPost('match/match.arbitres.enleve.arbitre.php', 'matchId=' + document.getElementById('match.id').value + '&numero=' + numero, 'match.arbitres.recherche.resultat');
}

function dupliqueFormationPhase(formationId) {
	if (confirm('Cette action dupliquera cette formation pour tous les matchs non-démarrés de la phase !')) {
		ajaxPost('formation/formation.duplique.phase.php', 'formationId=' + formationId, 'match.formation' + formationId + '.duplique.phase.resultat');
	}
}

function saisieRapide() {
	if (document.getElementById('poule.id') != null) {
		window.location.href = 'match.saisie.rapide.php?id=' + document.getElementById('match.id').value + '&pouleId=' + document.getElementById('poule.id').value;
	}
	if (document.getElementById('tournoi.id') != null) {
		window.location.href = 'match.saisie.rapide.php?id=' + document.getElementById('match.id').value + '&tournoiId=' + document.getElementById('tournoi.id').value;
	}
}

function statsMatch() {
	if (document.getElementById('poule.id') != null) {
		window.location.href = 'match.stats.php?id=' + document.getElementById('match.id').value + '&pouleId=' + document.getElementById('poule.id').value;
	}
	if (document.getElementById('tournoi.id') != null) {
		window.location.href = 'match.stats.php?id=' + document.getElementById('match.id').value + '&tournoiId=' + document.getElementById('tournoi.id').value;
	}
}