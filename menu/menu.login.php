<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Joueur.php');


$message = "";
if (isset($_POST["nomUtilisateur"]) && $_POST["nomUtilisateur"] != "")
{
	$joueur = Joueur::recupParChamp("nom_utilisateur", $_POST["nomUtilisateur"]);
	if (!$joueur[0])
	{
		$message = "Nom d'utilisateur inconnu";
	}
	else
	{
		if ($joueur[0]->verifieMdp($_POST["mdp"]))
		{
			$utilisateur_en_cours = $joueur[0];
			$_SESSION['utilisateur_id'] = $joueur[0]->get("id");
			print ("<SCRIPT>window.location.reload();</SCRIPT>");
		}
		else
		{
			$message = "Mot de passe éronné";
		}
	}
}

if (isset($_POST["delogge"]))
{
	$utilisateur_en_cours = null;
	$_SESSION['utilisateur_id'] = null;
	print ("<SCRIPT>window.location.reload();</SCRIPT>");
}

if (isset($utilisateur_en_cours))
{
	print ("Connecté en temps que " . $utilisateur_en_cours->get("nom_utilisateur"));
	print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"seDelogge();\">Se déconnecter</DIV>");	
}
else
{
	print ("Utilisateur : ");
	print ("<INPUT class=\"champTexte\" id=\"menu.login.nom_utilisateur\" type=\"text\" maxlength=\"255\" size=\"10\">");	
	print (" Mot de passe : ");
	print ("<INPUT class=\"champTexte\" id=\"menu.login.mdp\" type=\"password\" maxlength=\"255\" size=\"10\">");	
	print ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"seLogge();\">Se connecter</DIV>");	
}

if ($message != "")
{
	print ("<DIV class=\"messageErreur\">" . $message . "</DIV>");
}

?>
	