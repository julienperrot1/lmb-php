function creeEquipe() {
	ajaxPost('equipe/equipe.creamodi.valide.php', 'nom=' + document.getElementById('equipe.creamodi.nom').value + '&couleur_base=' + document.getElementById('equipe.creamodi.couleur_base').value + '&photo=' + document.getElementById('equipe.creamodi.photo.fichier').value , 'equipe.creamodi.message', RACINE + 'equipes.php');
}

function modifieEquipe() {
	ajaxPost('equipe/equipe.creamodi.valide.php', 'id=' + document.getElementById('equipe.creamodi.id').value + '&nom=' + document.getElementById('equipe.creamodi.nom').value + '&couleur_base=' + document.getElementById('equipe.creamodi.couleur_base').value + '&photo=' + document.getElementById('equipe.creamodi.photo.fichier').value , 'equipe.creamodi.message', RACINE + 'equipes.php');
}

function retourEquipes() {
	window.location.href = 'equipes.php';
}

function changeCouleur(couleur) {
	document.getElementById('equipe.creamodi.couleur_base').value = couleur;
	coloreChamp();
}

function coloreChamp() {
	document.getElementById('equipe.creamodi.couleur_base').style = "color: #" + document.getElementById('equipe.creamodi.couleur_base').value + ";" ;
}

function demarreUpload(){
    document.getElementById('equipe.upload.loading').hidden = false;
    document.getElementById('equipe.upload.formulaire').hidden = true;
    return true;
}

function termineUpload(result){
    if (isNaN(result)) {
		document.getElementById('equipe.creamodi.photo.fichier').value = result;
		document.getElementById('equipe.creamodi.photo').src = IMAGEUPLOADDIR + "/" + result;
    }
    if (result == 1) {
		alert("Erreur lors du chargement de la photo");
    }
    if (result == 2) {
		alert("Une photo existe déjà avec le même nom");
    }
	  
    document.getElementById('equipe.upload.loading').hidden = true;
    document.getElementById('equipe.upload.formulaire').hidden = false;
    return true;   
}