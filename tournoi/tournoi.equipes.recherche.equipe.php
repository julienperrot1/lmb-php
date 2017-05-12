<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Equipe.php');
include_once ($RACINE . 'utils/Tableur.php');


if (isset($_POST["texteRecherche"]) && $_POST["texteRecherche"] != "")
{
	$equipes = Equipe::recupParChampPartiel("nom", $_POST["texteRecherche"], "nom ASC");
	if ($equipes)
	{
		if (sizeof($equipes) > $NB_RESULTATS_RECHERCHE_EQUIPES)
		{
			print ("Trop de résultats (" . sizeof($equipes) . ") : Affinez votre recherche");
			print ("<BR/>");
			$equipes = array_slice($equipes, 0, $NB_RESULTATS_RECHERCHE_EQUIPES); 
		}

		Tableur::dessineTableau($equipes, false
				  , array("Nom", "")
				  , array(function ($objet) { return ($objet->get("nom")); }
				        , function ($objet) { return ("<DIV class=\"champ_a_cliquer\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"ajouteEquipe(" . $objet->get("id") . ");\">Ajouter au tournoi</DIV>"); }
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
	