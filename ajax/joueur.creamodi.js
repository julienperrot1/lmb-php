function creeJoueur() {
	if (document.getElementById('joueur.creamodi.sexe.M').checked == true) {
		sexe = "M";
	} else {
		sexe = "F";
	}
	
	ajaxPost('joueur/joueur.creamodi.valide.php', 'pseudo=' + document.getElementById('joueur.creamodi.pseudo').value + '&nom=' + document.getElementById('joueur.creamodi.nom').value + '&prenom=' + document.getElementById('joueur.creamodi.prenom').value + '&nom_utilisateur=' + document.getElementById('joueur.creamodi.nom_utilisateur').value + '&droits=' + document.getElementById('joueur.creamodi.droits').value + '&naissance=' + document.getElementById('joueur.creamodi.naissance_annee').value + '-' + document.getElementById('joueur.creamodi.naissance_mois').value + '-' + document.getElementById('joueur.creamodi.naissance_jour').value + '&sexe=' + sexe + '&photo=' + document.getElementById('joueur.creamodi.photo.fichier').value + '&mdp=' + document.getElementById('joueur.creamodi.mdp').value + '&mdp_bis=' + document.getElementById('joueur.creamodi.mdp_bis').value, 'joueur.creamodi.message', RACINE + 'joueur.creamodi.php');
}

function modifieJoueur() {
	if (document.getElementById('joueur.creamodi.sexe.M').checked == true) {
		sexe = "M";
	} else {
		sexe = "F";
	}
	
	ajaxPost('joueur/joueur.creamodi.valide.php', 'id=' + document.getElementById('joueur.creamodi.id').value + '&pseudo=' + document.getElementById('joueur.creamodi.pseudo').value + '&nom=' + document.getElementById('joueur.creamodi.nom').value + '&prenom=' + document.getElementById('joueur.creamodi.prenom').value + '&nom_utilisateur=' + document.getElementById('joueur.creamodi.nom_utilisateur').value + '&droits=' + document.getElementById('joueur.creamodi.droits').value + '&naissance=' + document.getElementById('joueur.creamodi.naissance_annee').value + '-' + document.getElementById('joueur.creamodi.naissance_mois').value + '-' + document.getElementById('joueur.creamodi.naissance_jour').value + '&sexe=' + sexe + '&photo=' + document.getElementById('joueur.creamodi.photo.fichier').value + '&mdp=' + document.getElementById('joueur.creamodi.mdp').value + '&mdp_bis=' + document.getElementById('joueur.creamodi.mdp_bis').value, 'joueur.creamodi.message', RACINE + 'joueurs.php');
}

function retourJoueurs() {
	window.location.href = 'joueurs.php';
}

function demarreUpload(){
    document.getElementById('joueur.upload.loading').hidden = false;
    document.getElementById('joueur.upload.formulaire').hidden = true;
    return true;
}

function termineUpload(result){
    if (isNaN(result)) {
		document.getElementById('joueur.creamodi.photo.fichier').value = result;
		document.getElementById('joueur.creamodi.photo').src = IMAGEUPLOADDIR + "/" + result;
    }
    if (result == 1) {
		alert("Erreur lors du chargement de la photo");
    }
    if (result == 2) {
		alert("Une photo existe déjà avec le même nom");
    }
	  
    document.getElementById('joueur.upload.loading').hidden = true;
    document.getElementById('joueur.upload.formulaire').hidden = false;
    return true;   
}