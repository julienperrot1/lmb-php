function chargeLogin() {
	ajaxPost('menu/menu.login.php', '', 'menu.login');
}

function seLogge() {
	ajaxPost('menu/menu.login.php', 'nomUtilisateur=' + document.getElementById('menu.login.nom_utilisateur').value + '&mdp=' + document.getElementById('menu.login.mdp').value, 'menu.login');
}

function seDelogge() {
	ajaxPost('menu/menu.login.php', 'delogge=true', 'menu.login');
}