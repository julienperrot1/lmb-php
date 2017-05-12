<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Joueur.php');

$valide = true;

if (isset($utilisateur_en_cours) && ($utilisateur_en_cours->get("droits") >= 1))
{	
	if (!isset($_POST["pseudo"]) || $_POST["pseudo"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un pseudo pour le joueur</DIV>");
		$valide = false;
	}

	if (!isset($_POST["nom"]) || $_POST["nom"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un nom pour le joueur</DIV>");
		$valide = false;
	}

	if (!isset($_POST["prenom"]) || $_POST["prenom"] == "")
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifier un prénom pour le joueur</DIV>");
		$valide = false;
	}

	if (isset($_POST["nom_utilisateur"]) && $_POST["nom_utilisateur"] != "")
	{
		$joueur = Joueur::recupParChamp("nom_utilisateur", $_POST["nom_utilisateur"]);
		if ($joueur[0] && (!isset($_POST["id"]) || $joueur[0]->get("id") != $_POST["id"]))
		{
			print ("<DIV class=\"messageErreur\">Ce nom d'utilisateur existe déjà</DIV>");
			$valide = false;
		}
	}

	if (!isset($_POST["naissance"]) || !preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/i", $_POST["naissance"]))
	{
		print ("<DIV class=\"messageErreur\">Veuillez spécifiez une date de naissance correcte (jouer et mois sur 1 ou 2 chiffre(s), année sur 4 chiffres</DIV>");
		$valide = false;
	}
	else
	{	
		list($annee, $mois, $jour) = explode('-', $_POST["naissance"]);
		if (! checkdate($mois, $jour, $annee))
		{
			print ("<DIV class=\"messageErreur\">La date de naissance choisie n'existe pas</DIV>");
			$valide = false;
		}
	}

	if (!isset($_POST["sexe"]) || ($_POST["sexe"] != "M" && $_POST["sexe"] != "F"))
	{
		print ("<DIV class=\"messageErreur\">Veuillez selectionner le sexe du joueur</DIV>");
		$valide = false;
	}

	if (!isset($_POST["id"]) && isset($_POST["nom_utilisateur"]) && $_POST["nom_utilisateur"] != "" && (!isset($_POST["mdp"]) || $_POST["mdp"] == ""))
	{
		print ("<DIV class=\"messageErreur\">Veuillez renseigner un mot de passe avec le nom utilisateur</DIV>");
		$valide = false;
	}

	if (!isset($_POST["droits"]) || $_POST["droits"] < 0)
	{
		print ("<DIV class=\"messageErreur\">Veuillez choisir les droits de l'utilisateur</DIV>");
		$valide = false;
	}

	if (isset($_POST["mdp"]) && $_POST["mdp"] != "" && (!isset($_POST["mdp_bis"]) || $_POST["mdp_bis"] == "" || $_POST["mdp"] != $_POST["mdp_bis"]))
	{
		print ("<DIV class=\"messageErreur\">Les 2 mots de passes renseignés doivent être identiques</DIV>");
		$valide = false;
	}

	if ($valide)
	{
		if (isset($_POST["id"]))
		{
			$joueur = Joueur::recup($_POST["id"]);
			$joueur->set("pseudo", $_POST["pseudo"]);
			$joueur->set("nom", $_POST["nom"]);
			$joueur->set("prenom", $_POST["prenom"]);
			$joueur->set("naissance", $_POST["naissance"]);
			$joueur->set("sexe", $_POST["sexe"]);
			$joueur->set("photo", $_POST["photo"]);
			
			if (isset($_POST["nom_utilisateur"]) && $_POST["nom_utilisateur"] != "")
			{
				$joueur->set("nom_utilisateur", $_POST["nom_utilisateur"]);
			}
			
			if (isset($_POST["mdp"]) && $_POST["mdp"] != "")
			{
				$joueur->set("md5_mdp", md5($_POST["mdp"]));
			}
			
			$joueur->set("droits", $_POST["droits"]);
			$resultat = $joueur->enregistre();	
		}
		else
		{
			$joueur = new Joueur();
			$joueur->set("pseudo", $_POST["pseudo"]);
			$joueur->set("nom", $_POST["nom"]);
			$joueur->set("prenom", $_POST["prenom"]);
			$joueur->set("naissance", $_POST["naissance"]);
			$joueur->set("sexe", $_POST["sexe"]);
			$joueur->set("photo", $_POST["photo"]);
			
			if (isset($_POST["nom_utilisateur"]) && $_POST["nom_utilisateur"] != "")
			{
				$joueur->set("nom_utilisateur", $_POST["nom_utilisateur"]);
				$joueur->set("md5_mdp", md5($_POST["mdp"]));
			}
			
			$joueur->set("droits", $_POST["droits"]);
			$resultat = $joueur->cree();
		}
		
		if ($resultat)
		{
			print ("#REDIRECT#");
		}
		else
		{
			print ("<DIV class=\"messageErreur\">Une erreur est survenue lors de l'enregistrement de l'objet en base de données</DIV>");
		}
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour éditer un joueur</DIV>");
}
	