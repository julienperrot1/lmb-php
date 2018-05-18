<?php

include_once ('../ajax/ajax_base.php');

include_once ($RACINE . 'modele/Action.php');


if (isset($utilisateur_en_cours) && $utilisateur_en_cours->get("droits") >= 3)
{
	if (isset($_POST["actionId"]))
	{
    /* Récupération des informations de l'action en cours de suppression */
    $action = Action::recup($_POST["actionId"]);
    if ($action->supprimeStat())
    {
      /* Suppression de l'action */
      if ($action->supprimeAvecSpecifique())
      {
        print ("<SCRIPT>chargeFormation1(); chargeFormation2(); chargeScores(); chargeResume();</SCRIPT>");
      }		
      else
      {
        print ("<DIV class=\"messageErreur\" >Erreur lors de la suppression de l'action. Attention, stat faussée !!!</DIV>");
      }
    }
		else
		{
			print ("<DIV class=\"messageErreur\" >Erreur lors de la suppression des stats de l'action/DIV>");
		}
	}
}
else
{
	print ("<DIV class=\"messageErreur\">Vous n'avez pas les droits pour supprimer une action</DIV>");
}
