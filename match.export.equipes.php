<?php


include_once 'menu.php';

global $RACINE;

include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'modele/Joueur.php');



$match = Match::recup($_GET["id"]);
$formation1 = Formation::recup($match->get("formation1_id"));
$formation2 = Formation::recup($match->get("formation2_id"));
$equipe1 = Equipe::recup($formation1->get("equipe_id"));
$equipe2 = Equipe::recup($formation2->get("equipe_id"));

print ('{																	<BR/>');
print ('	"equipes" : [													<BR/>');
print ('		{															<BR/>');
print ('			"id" : "' . $equipe1->get("id") . '",					<BR/>');
print ('			"couleur" : "' . $equipe1->get("couleur_base") . '",	<BR/>');
print ('			"photo" : "' . $equipe1->get("photo") . '",				<BR/>');
print ('			"nom" : "' . $equipe1->get("nom") . '",					<BR/>');
print ('			"Joueurs" : [											<BR/>');

$formation_joueurs = $formation1->getFormationJoueurs();
$derniere_cle = array_pop(array_keys($formation_joueurs));
foreach ($formation_joueurs as $cle => $formation_joueur)
{
	$joueur = Joueur::recup($formation_joueur["joueur_id"]);
	
	print ('			{													<BR/>');
	print ('				"id" : "' . $joueur->get("id") . '",			<BR/>');
	print ('				"nom" : "' . $joueur->get("nom") . '",			<BR/>');
	print ('				"prenom" : "' . $joueur->get("prenom") . '",	<BR/>');
	print ('				"pseudo" : "' . $joueur->get("pseudo") . '",	<BR/>');
	print ('			}													<BR/>');
	
	if ($cle !== $derniere_cle)
	{
		print ('		,													<BR/>');
	}
}

print ('			]														<BR/>');
print ('		}															<BR/>');
print ('		,															<BR/>');




print ('		{															<BR/>');
print ('			"id" : "' . $equipe2->get("id") . '",					<BR/>');
print ('			"couleur" : "' . $equipe2->get("couleur_base") . '",	<BR/>');
print ('			"photo" : "' . $equipe2->get("photo") . '",				<BR/>');
print ('			"nom" : "' . $equipe2->get("nom") . '",					<BR/>');
print ('			"Joueurs" : [											<BR/>');

$formation_joueurs = $formation2->getFormationJoueurs();
$derniere_cle = array_pop(array_keys($formation_joueurs));
foreach ($formation_joueurs as $cle => $formation_joueur)
{
	$joueur = Joueur::recup($formation_joueur["joueur_id"]);
	
	print ('			{													<BR/>');
	print ('				"id" : "' . $joueur->get("id") . '",			<BR/>');
	print ('				"nom" : "' . $joueur->get("nom") . '",			<BR/>');
	print ('				"prenom" : "' . $joueur->get("prenom") . '",	<BR/>');
	print ('				"pseudo" : "' . $joueur->get("pseudo") . '",	<BR/>');
	print ('			}													<BR/>');
	
	if ($cle !== $derniere_cle)
	{
		print ('		,													<BR/>');
	}
}

print ('			]														<BR/>');
print ('		}															<BR/>');
print ('	]																<BR/>');
print ('}																	<BR/>');
		
?>
	