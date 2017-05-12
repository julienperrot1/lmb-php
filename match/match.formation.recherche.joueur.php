<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Joueur.php');
include_once ($RACINE . 'utils/Tableur.php');


if (isset($_POST["texteRecherche"]) && $_POST["texteRecherche"] != "")
{
	$joueurs = Joueur::cherche($_POST["texteRecherche"], "pseudo ASC");
	if ($joueurs)
	{
		if (sizeof($joueurs) > $NB_RESULTATS_RECHERCHE_JOUEURS)
		{
			print ("Trop de résultats (" . sizeof($joueurs) . ") : Affinez votre recherche");
			print ("<BR/>");
			$joueurs = array_slice($joueurs, 0, $NB_RESULTATS_RECHERCHE_JOUEURS); 
		}

		Tableur::dessineTableau($joueurs, false
				  , array("Nom", "")
				  , array(function ($objet) {	global $utilisateur_en_cours;
												$retour = $objet->get("pseudo");
												if (isset($utilisateur_en_cours))
												{
													$retour = $retour . " (" . $objet->get("prenom") . " " . $objet->get("nom") . ")";
												}
												return $retour;
				    }
				        , function ($objet) {
												return ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"ajouteJoueurAFormation(" . $objet->get("id") . ", " . $_POST["formationId"] . ");\">Ajouter à la formation</DIV>");
											}
						 )
				  , "tableau_recherche", "tableau_recherche_tr", "tableau_recherche_th", "tableau_recherche_td");
	}
	else
	{
		print ("Aucun résultat trouvé");
		print ("<BR/>");
	}
}
else
{
	print ("Saississez votre recherche dans le champ ci dessus");
	print ("<BR/>");
}

?>
	