<?php

global $RACINE;

include_once ($RACINE . 'utils/Bdd.php');
	
/*
  Classe d'objet générique pour fonction communes)
*/
class Objet
{
	const TYPE 		= "Objet";
	const TABLE 	= "N/A";
	const CHAMPS 	= "";
	  
	// L'objet à t-il été récupéré en base ?
	protected $_vientdelabase = false;
	
	// Champs de l'objet
	protected $_champs;
	
	// Constructeur par champs
	public function __construct($_champs = array(), $_vientdelabase = false)
	{
		$this->_champs = $_champs;
		$this->_vientdelabase = $_vientdelabase;
	}
	
	// Récupérateur générique
	public function get($champ)
	{
		return ($this->_champs[$champ]);
	}
	
	// Mise à jour de champ générique
	public function set($champ, $valeur)
	{
		$classe = get_called_class();
		
		$champs = preg_split("/,/", $classe::CHAMPS);
		if (array_search($champ, $champs) === false)
		{
			return false;
		}
		else
		{
			$this->_champs[$champ] = $valeur;
			if ($this->_vientdelabase)
			{
				$this->enregistre();
			}
			
			return true;
		}
	}
	
	// Permet de récupérer un élément par son identifiant primaire
	public static function recup($id)
	{
		global $BDD;
		$classe = get_called_class();
		
		$requete = "SELECT *"
		         . " FROM " . $classe::TABLE
				 . " WHERE id = " . $id;
		
		$resultat = $BDD->requeteMonoResultat($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			return (new $classe($resultat, true));
		}
	}
	  
	// Permet de récupérer tous les éléments de la table, en les triant si besoin
	public static function recupTous($tri = "")
	{
		global $BDD;
		$classe = get_called_class();
				
		$requete = "SELECT *"
		         . " FROM " . $classe::TABLE;
		if ($tri && $tri != "")
		{
			$requete = $requete
			         . " ORDER BY " . $tri;
		}
		
		$resultat = $BDD->requeteMultiResultats($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_values = array();
			foreach($resultat as $index => $ligne)
			{
				$return_values[] = new $classe($ligne, true);
			}
			
			return $return_values;
		}
	}
	  
	// Permet de récupérer les éléments de la table pour une valeur d'un champ donnée, en les triant si besoin
	public static function recupParChamp($champ, $valeur, $tri = "")
	{
		global $BDD;
		$classe = get_called_class();
				
		$requete = "SELECT *"
		         . " FROM " . $classe::TABLE
		         . " WHERE " . $champ . " = '" . $valeur . "'";
		if ($tri && $tri != "")
		{
			$requete = $requete
			         . " ORDER BY " . $tri;
		}
		
		$resultat = $BDD->requeteMultiResultats($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_values = array();
			foreach($resultat as $index => $ligne)
			{
				$return_values[] = new $classe($ligne, true);
			}
			
			return $return_values;
		}
	}
	  
	// Permet de récupérer les éléments de la table pour une valeur d'un champ donnée, en les triant si besoin
	public static function recupParChampDifferent($champ, $valeur, $tri = "")
	{
		global $BDD;
		$classe = get_called_class();
				
		$requete = "SELECT *"
		         . " FROM " . $classe::TABLE
		         . " WHERE " . $champ . " <> '" . $valeur . "'";
		if ($tri && $tri != "")
		{
			$requete = $requete
			         . " ORDER BY " . $tri;
		}
		
		$resultat = $BDD->requeteMultiResultats($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_values = array();
			foreach($resultat as $index => $ligne)
			{
				$return_values[] = new $classe($ligne, true);
			}
			
			return $return_values;
		}
	}
	
	// Permet de récupérer les éléments de la table par recherche (si le champ contient la chaine recherchée)
	public static function recupParChampPartiel($champ, $recherche, $tri = "")
	{
		global $BDD;
		$classe = get_called_class();
				
		$requete = "SELECT *"
		         . " FROM " . $classe::TABLE
		         . " WHERE " . $champ . " like '%" . $recherche . "%'";
		if ($tri && $tri != "")
		{
			$requete = $requete
			         . " ORDER BY " . $tri;
		}
		
		$resultat = $BDD->requeteMultiResultats($requete);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_values = array();
			foreach($resultat as $index => $ligne)
			{
				$return_values[] = new $classe($ligne, true);
			}
			
			return $return_values;
		}
	}
		
	// Permet d'enregistrer les modification d'un élément en base
	public function enregistre()
	{
		if ($this->_vientdelabase == true) {
			global $BDD;
			$classe = get_called_class();
		
			$requete = "UPDATE " . $classe::TABLE
					 . " SET id = '" . $this->_champs["id"] . "'";
					 
			if ($classe::CHAMPS != "")
			{
				$champs = preg_split("/,/", $classe::CHAMPS);
				foreach ($champs as $champ)
				{
					if (array_key_exists($champ, $this->_champs))
					{
						$requete = $requete . ", " . $champ . " = '" . $this->_champs[$champ] . "'";
					}
					else
					{
						$requete = $requete . ", " . $champ . " = null";
					}
				}
			}
			
			$requete = $requete . " WHERE id = '" . $this->_champs["id"] . "'";
			
			return $BDD->requeteModification($requete);
		} else {
			return false;
		}
	}
	
	// Permet de créer un nouvel élément en base
	public function cree()
	{
		if ($this->_vientdelabase == false) {
			global $BDD;
			$classe = get_called_class();
		
			if ($classe::CHAMPS != "")
			{
				$champs = preg_split("/,/", $classe::CHAMPS);
				
				$requete = "INSERT INTO " . $classe::TABLE
			             . " (" . join(", ", $champs) . ")"
						 . " VALUES"
						 . " (";
				
				foreach ($champs as $champ)
				{
					if (array_key_exists($champ, $this->_champs))
					{
						$requete = $requete . "'" . $this->_champs[$champ] . "', ";
					}
					else
					{
						$requete = $requete . "null, ";
					}
				}
				
				$requete = substr($requete, 0, -2) . ")";
			}
			
			$result = $BDD->requeteInsertion($requete);
			if ($result)
			{
				$this->_champs["id"] = $result;
				$this->_vientdelabase = true;
			}
			return $result;
		}
		else
		{
			return false;
		}	
	}
	
	// Permet de supprimer un élément de la table via son identifiant
	public static function supprime($objet_id)
	{
		global $BDD;
		$classe = get_called_class();
				
		$requete = "DELETE "
		         . " FROM " . $classe::TABLE
		         . " WHERE id = " . $objet_id;

		if ($BDD->requeteSuppression($requete))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// Permet d'afficher un élémént, à but de debug
	public function detail()
	{
		$classe = get_called_class();
		
		$chaine = $classe . "#"
		       . "Type=" . $this::TYPE . "#"
			   . "Table=" . $classe::TABLE . "#"
			   . "Id=" . $this->get("id");
		
		if ($classe::CHAMPS != "")
		{
			$champs = preg_split("/,/", $classe::CHAMPS);
			foreach ($champs as $champ)
			{
				$chaine = $chaine . "<BR/>" . $champ . " = " . $this->get($champ);
			}
		}
		
		return $chaine;
	}
	
	// Défini le système de comparaison par defaut
	public function egal($objet)
    {
		if ($objet)
		{
			if ($objet->get("id") == $this->get("id"))
			{
				return true;
			}
		}
		
		return false;
    }
}

?>