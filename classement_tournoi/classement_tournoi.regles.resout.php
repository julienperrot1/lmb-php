<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/ClassementTournoi.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'utils/Regle.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$classements_tournoi = ClassementTournoi::recupParChamp("tournoi_id", $_POST["tournoiId"]);

	$message = "";
	if ($classements_tournoi) 
	{
		foreach ($classements_tournoi as $classement_tournoi)
		{
			$equipe_id = Regle::recupEquipeDepuisRegle($classement_tournoi->get("regle_equipe"));

			if ($equipe_id)
			{
				if (is_numeric($equipe_id))
				{
					$classement_tournoi->set("regle_equipe", null);
					$classement_tournoi->set("equipe_id", $equipe_id);
					$resultat = $classement_tournoi->enregistre();
					
					if (!$resultat)
					{
						$message = $message . "<DIV class=\"messageErreur\">Une erreur est survenue lors de l'enregistrement de l'objet en base de données</DIV>";
					}
				}
				else
				{
					$message = $message . $equipe_id . "<BR/>";
				}
			}
		}
	}


	if ($message == "")
	{
		print ("<SCRIPT>chargeClassement()</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >" . $message . "</DIV>");
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour résoudre les régles de classement d'un tournoi</DIV>");
}

?>
	