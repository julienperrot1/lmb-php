<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/PhaseTableau.php');
include_once ($RACINE . 'modele/Match.php');
include_once ($RACINE . 'modele/Formation.php');
include_once ($RACINE . 'utils/Regle.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$phase_tableau = PhaseTableau::recup($_POST["phaseTableauId"]);
	$matchs = Match::recupParChamp("phase_id", $phase_tableau->get("phase_id"));

	$message = "";
	if ($matchs) 
	{
		foreach ($matchs as $match)
		{
			$equipe1_id = Regle::recupEquipeDepuisRegle($match->get("regle_formation1"));
						
			if ($equipe1_id)
			{
				if (is_numeric($equipe1_id))
				{
					$formation1 = new Formation();
					$formation1->set("equipe_id", $equipe1_id);
					$formation1->set("match_id", $match->get("id"));
					if (!$formation1->cree())
					{
						$message_erreur = $message_erreur . "Erreur de création de la formation 1<BR/>";
					}
					else
					{
						$formation1->dupliquePlusRecente($equipe1_id);
						$match->set("formation1_id", $formation1->get("id"));
						$match->set("regle_formation1", null);
					}
				}
				else
				{
					$message_erreur = $message_erreur . $equipe1_id . "<BR/>";
				}
			}
			
			
			$equipe2_id = Regle::recupEquipeDepuisRegle($match->get("regle_formation2"));
						
			if ($equipe2_id)
			{
				if (is_numeric($equipe2_id))
				{
					$formation2 = new Formation();
					$formation2->set("equipe_id", $equipe2_id);
					$formation2->set("match_id", $match->get("id"));
					if (!$formation2->cree())
					{
						$message_erreur = $message_erreur . "Erreur de création de la formation 2<BR/>";
					}
					else
					{
						$formation2->dupliquePlusRecente($equipe2_id);
						$match->set("formation2_id", $formation2->get("id"));
						$match->set("regle_formation2", null);
					}
				}
				else
				{
					$message_erreur = $message_erreur . $equipe2_id . "<BR/>";
				}
			}
				
				
			$match->enregistre();
		}
	}


	if ($message == "")
	{
		print ("<SCRIPT>chargeListePhases()</SCRIPT>");
	}
	else
	{
		print ("<DIV class=\"messageErreur\" >" . $message . "</DIV>");
	}
}		
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour résoudre les régles d'une phase</DIV>");
}

?>
	