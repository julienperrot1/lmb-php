<?php

global $racine;
include_once ($racine . 'config.php');

include_once ($racine . 'utils/Bdd.php');

/*
  Représente un ensemble de droits pouvant être affecté à un utilisateur du site
*/
class Droit
{
	const DENOM = "DROIT";
	
	// Serial
	private $_id;
	  
	// Libéllé de l'ensemble de droit
	private $_libelle;
	  
	// Code du droit pour les pages front
	private $_code;
	
	private function __construct($id, $libelle, $code)
	{
		$this->_id = $id;
		$this->_libelle = $libelle;
		$this->_code = $code;
	}
	
	public function id()
	{
		return $this->_id;
	}
	  
	public function libelle()
	{
		return $this->_libelle;
	}
	  
	public function code()
	{
		return $this->_code;
	}
		
	public function versString()
	{
		return $this->_code . " - " . $this->_libelle;
	}
	  
	public static function recup($id)
	{
		global $bdd, $TABLE_DROIT;
		
		$resultat = $bdd->requeteMonoResultat("SELECT * FROM " . $TABLE_DROIT . " WHERE id = " . $id);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_value = new Droit($id, $resultat["libelle"], $resultat["code"]);
			return $return_value;
		}
	}
	
	public static function recupParCode($code)
	{
		global $bdd, $TABLE_DROIT;
		
		$resultat = $bdd->requeteMonoResultat("SELECT * FROM " . $TABLE_DROIT . " WHERE code = '" . $code . "'");
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_value = new Droit($resultat["id"], $resultat["libelle"], $code);
			return $return_value;
		}
	}
	
	public static function recupTous()
	{
		global $bdd, $TABLE_DROIT;
		
		$resultat = $bdd->requeteMultiResultats("SELECT * FROM " . $TABLE_DROIT . "");
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_values = array();
			foreach($resultat as $index => $ligne)
			{
				$return_values[] = new Droit($ligne["id"], $ligne["libelle"], $ligne["code"]);
			}
			
			return $return_values;
		}
	}
	
	public static function exporte()
	{
		global $bdd, $TABLE_DROIT, $SIMPLE_SEP, $DOUBLE_SEP;
		
		$resultat = $bdd->requeteMultiResultats("SELECT * FROM " . $TABLE_DROIT);
		if (!$resultat)
		{
			return null;
		}
		else
		{
			$return_value = "";
			foreach($resultat as $index => $ligne)
			{
				$return_value = $return_value . Droit::DENOM . $DOUBLE_SEP . $ligne["id"] . $SIMPLE_SEP . $ligne["libelle"] . $SIMPLE_SEP . $ligne["code"] . "\r\n";
			}
			
			return $return_value;
		}
	}
	
	public static function importe($element)
	{
		global $bdd, $TABLE_DROIT, $SIMPLE_SEP;
		
		$element_tab = preg_split("/" . $SIMPLE_SEP . "/", $element);
		$existant = Droit::recup($element_tab[0]);
		
		if ($existant)
		{
			if (! $bdd->requeteModification("UPDATE " . $TABLE_DROIT . " SET libelle = '" . $element_tab[1] . "', code = '" . $element_tab[2] . "' WHERE id = '" . $element_tab[0] . "'") )
			{
				return null;
			}
		}
		else
		{
			if (! $bdd->requeteInsertion("INSERT INTO " . $TABLE_DROIT . " (id, libelle, code) VALUES ('" . $element_tab[0] . "', '" . $element_tab[1] . "', '" . $element_tab[2] . "')") )
			{
				return null;
			}
		}
		
		return true;
	}
	
	public static function recree_table()
	{
		global $bdd, $TABLE_DROIT;
		
		$resultat = $bdd->requeteSansResultat("DROP TABLE IF EXISTS " . $TABLE_DROIT . ";");
		if (!$resultat)
		{
			return null;
		}
		
		$resultat = $bdd->requeteSansResultat(
			"CREATE TABLE IF NOT EXISTS " . $TABLE_DROIT . " (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `libelle` varchar(255) NOT NULL,
			  `code` varchar(32) NOT NULL,
			  PRIMARY KEY (`id`));"
		);
		if (!$resultat)
		{
			return null;
		}
		
		return true;
	}
}
?>