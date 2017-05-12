<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/PhasePoules.php');
include_once ($RACINE . 'modele/Poule.php');
include_once ($RACINE . 'utils/Regle.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	$phase_poules = PhasePoules::recup($_POST["phasePoulesId"]);
	$poules = Poule::recupParChamp("phase_poules_id", $phase_poules->get("id"));

	$message = "";
	if ($poules) 
	{
		foreach ($poules as $poule)
		{
			$poule_equipes = $poule->getEquipes();
			
			if ($poule_equipes) 
			{
				foreach ($poule_equipes as $poule_equipe)
				{
					if ($poule_equipe["equipe_id"] == -1)
					{
						$equipe_id = Regle::recupEquipeDepuisRegle($poule_equipe["regle"]);
						
						if ($equipe_id)
						{
							if (is_numeric($equipe_id))
							{
								$poule->enleveEquipe(-1, $poule_equipe["regle"]);
								$poule->ajouteEquipe($equipe_id);
							}	
						}
						else
						{
							$message = $message . $equipe_id . "<BR/>";
						}
					}
				}
			}
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
	