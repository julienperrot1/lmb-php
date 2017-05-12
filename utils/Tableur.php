<?php

global $RACINE;

include_once ($RACINE . 'config.php');

class Tableur
{
	static public function dessineTableau($liste_objets, $afficher_titres, $titres = null, $templates_champs = null, $classe_table = "", $classe_tr = "", $classe_th = "", $classe_td = "", $tailles_colonnes = null, $titres_infobulles = null)
	{
		if ($liste_objets && sizeof($liste_objets) > 0)
		{
			if (is_object($liste_objets[0]))
			{
				$classe = get_class($liste_objets[0]);
				$champs = preg_split("/,/", $classe::CHAMPS);
				if ($titres == null) {
					$titres = $champs;
				}
			}
			
			$taille_totale;
			if ($tailles_colonnes != null)
			{
				$taille_totale = 0;
				foreach ($tailles_colonnes as $taille_colonne)
				{
					$taille_totale = $taille_totale + $taille_colonne;
				}
			}
			
			print ("<TABLE class=\"" . $classe_table . "\">");
			
			if ($afficher_titres)
			{
				print ("<TR class=\"" . $classe_tr . "\">");
				foreach ($titres as $index_titre => $titre)
				{
					print ("<TH class=\"" . $classe_th . "\"");
					if (isset($taille_totale))
					{
						$taille_pourcent = $tailles_colonnes[$index_titre] / $taille_totale * 100;
						print (" width=" . $taille_pourcent . "%");
						print (" max-width=" . $taille_pourcent . "%");
					}
					if ($titres_infobulles)
					{
						print (" title=\"" . $titres_infobulles[$index_titre] . "\"");
					}
					print (">");
					print ($titre);
					print ("</TH>");
				}
				print ("</TR>");
			}
			
			foreach ($liste_objets as $index_objet => $objet)
			{
				print ("<TR class=\"" . $classe_tr . "\">");
				
				if ($templates_champs != null)
				{
					foreach ($templates_champs as $index_template => $template_champ)
					{
						print ("<TD class=\"" . $classe_td . "\"");
						if (isset($taille_totale))
						{
							$taille_pourcent = $tailles_colonnes[$index_template] / $taille_totale * 100;
							print (" width=" . $taille_pourcent . "%");
							print (" max-width=" . $taille_pourcent . "%");
						}
						print (">");
						print ($template_champ($objet));
						print ("</TD>");
					}
				}
				else
				{
					foreach ($champs as $index_champ => $champ)
					{
						print ("<TD class=\"" . $classe_td . "\"");
						if (isset($taille_totale))
						{
							$taille_pourcent = $tailles_colonnes[$index_champ] / $taille_totale * 100;
							print (" width=" . $taille_pourcent . "%");
							print (" max-width=" . $taille_pourcent . "%");
						}
						print (">");
						print ($objet->get($champ));
						print ("</TD>");
					}
				}
				print ("</TR>");
			}
			
			print ("</TABLE>");
			
			return true;
		}
	}
	
	static public function ratio($valeur, $ratio, $precision)
	{
		return sprintf("%.g", sprintf("%." . $precision . "f", $valeur * $ratio));
	}
}

?>