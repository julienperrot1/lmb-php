function creePhase() {
	if (document.getElementById('phase.creamodi.type.1').checked == true) {
		type = 1;
		specifique = '&nb_poules=' + document.getElementById('phase.creamodi.specifique.1.nb_poules').value + '&nb_periode_match=' + document.getElementById('phase.creamodi.specifique.1.nb_periode_match').value + '&duree_periode_match_minutes=' + document.getElementById('phase.creamodi.specifique.1.duree_periode_match_minutes').value + '&duree_periode_match_secondes=' + document.getElementById('phase.creamodi.specifique.1.duree_periode_match_secondes').value;
	} else if (document.getElementById('phase.creamodi.type.2').checked == true) {
		type = 2;
		
		if (document.getElementById('phase.creamodi.specifique.2.matchs.victoires').checked == true) {
			matchs = 1;
		} else if (document.getElementById('phase.creamodi.specifique.2.matchs.victoiresPF').checked == true) {
			matchs = 2;
		}  else if (document.getElementById('phase.creamodi.specifique.2.matchs.tous').checked == true) {
			matchs = 3;
		}
		
		if (document.getElementById('phase.creamodi.specifique.2.etat.1').checked == true) {
			etat = 1;
		} else if (document.getElementById('phase.creamodi.specifique.2.etat.2').checked == true) {
			etat = 2;
		}  else if (document.getElementById('phase.creamodi.specifique.2.etat.3').checked == true) {
			etat = 3;
		}
		
		specifique = '&matchs=' + matchs + '&nb_equipes=' + document.getElementById('phase.creamodi.specifique.2.nb_equipes').value + '&etat=' + etat;
		for (index = 1; index <= 6; index++)
		{
			specifique = specifique + '&nb_periode_match_' + index + '=' + document.getElementById('phase.creamodi.specifique.2.nb_periode_match_' + index).value + '&duree_periode_match_minutes_' + index + '=' + document.getElementById('phase.creamodi.specifique.2.duree_periode_match_minutes_' + index).value + '&duree_periode_match_secondes_' + index + '=' + document.getElementById('phase.creamodi.specifique.2.duree_periode_match_secondes_' + index).value;
		}
	} else if (document.getElementById('phase.creamodi.type.3').checked == true) {
		type = 3;
	} else {
		type = 0;
	}
	
	ajaxPost('phase/phase.creamodi.valide.php', 'tournoiId=' + document.getElementById('phase.creamodi.tournoi_id').value + '&libelle=' + document.getElementById('phase.creamodi.libelle').value + '&date=' + document.getElementById('phase.creamodi.date_annee').value + '-' + document.getElementById('phase.creamodi.date_mois').value + '-' + document.getElementById('phase.creamodi.date_jour').value + '&type=' + type + specifique, 'phase.creamodi.message', RACINE + 'tournoi.php?id=' + document.getElementById('phase.creamodi.tournoi_id').value);
}

function modifiePhase() {
	if (document.getElementById('phase.creamodi.type.1').checked == true) {
		type = 1;
		specifique = '&nb_poules=' + document.getElementById('phase.creamodi.specifique.1.nb_poules').value + '&nb_periode_match=' + document.getElementById('phase.creamodi.specifique.1.nb_periode_match').value + '&duree_periode_match_minutes=' + document.getElementById('phase.creamodi.specifique.1.duree_periode_match_minutes').value + '&duree_periode_match_secondes=' + document.getElementById('phase.creamodi.specifique.1.duree_periode_match_secondes').value;
	} else if (document.getElementById('phase.creamodi.type.2').checked == true) {
		type = 2;
		
		if (document.getElementById('phase.creamodi.specifique.2.etat.1').checked == true) {
			etat = 1;
		} else if (document.getElementById('phase.creamodi.specifique.2.etat.2').checked == true) {
			etat = 2;
		}  else if (document.getElementById('phase.creamodi.specifique.2.etat.3').checked == true) {
			etat = 3;
		}
		
		specifique = '&etat=' + etat;
	} else if (document.getElementById('phase.creamodi.type.3').checked == true) {
		type = 3;
	} else {
		type = 0;
	}
	
	ajaxPost('phase/phase.creamodi.valide.php', 'id=' + document.getElementById('phase.creamodi.id').value + '&tournoiId=' + document.getElementById('phase.creamodi.tournoi_id').value + '&libelle=' + document.getElementById('phase.creamodi.libelle').value + '&date=' + document.getElementById('phase.creamodi.date_annee').value + '-' + document.getElementById('phase.creamodi.date_mois').value + '-' + document.getElementById('phase.creamodi.date_jour').value + '&type=' + type + specifique, 'phase.creamodi.message', RACINE + 'tournoi.php?id=' + document.getElementById('phase.creamodi.tournoi_id').value);
}

function retourTournoi() {
	window.location.href = 'tournoi.php?id=' + document.getElementById('phase.creamodi.tournoi_id').value;
}

function montreTypeSpecifique(type) {
	document.getElementById('phase.creamodi.specifique.1').hidden = true;
	document.getElementById('phase.creamodi.specifique.2').hidden = true;
	document.getElementById('phase.creamodi.specifique.3').hidden = true;
	
	document.getElementById('phase.creamodi.specifique.' + type).hidden = false;
}
