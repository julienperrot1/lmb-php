<?php

global $RACINE;
include_once ($RACINE . 'config.php');

include_once ($RACINE . 'utils/Logger.php');

class Bdd
{
	private $_connection;
	private $_logger;

	public function __construct()
	{
		global $BDD_HOST, $BDD_USER, $BDD_PASS, $BDD_DATABASE, $LOGGER_NIVEAU;
		$this->_logger = new Logger("Bdd", Logger::$NIVEAUX[$LOGGER_NIVEAU]);
		
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Ouverture de la BDD en cours");
		
		$this->_connection = mysqli_connect($BDD_HOST, $BDD_USER, $BDD_PASS, $BDD_DATABASE);
		if ($this->_connection)
		{
			$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Ouverture de la BDD réussie");
			$this->_connection->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
			return $this;
		}
		else
		{
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Erreur lors de l'ouverture de la BDD (" . $BDD_HOST . ", " . $BDD_USER . ", " . $BDD_PASS . ");");
			return null;
		}
	}

	public function __destruct()
	{
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Fermeture de la BDD");
		
		if (mysqli_close($this->_connection))
		{
			return 1;
		}
		else
		{
			return null;
		}
	}
	
	public function requeteMonoResultat($sql)
	{
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Execution de la requête à un seul résultat : " . $sql);
		
		$sql_resultat = mysqli_query($this->_connection, $sql);
		if (!$sql_resultat)
		{
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Erreur d'execution de la requete SQL : " . $sql);
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Retour de la BDD : " . mysqli_error($this->_connection));
			return null;
		}
		
		if (mysqli_num_rows($sql_resultat) == 1)
		{
			return mysqli_fetch_assoc($sql_resultat);
		}
		else
		{
			$this->_logger->log(Logger::$NIVEAUX["INFO"], "La requête " . $sql . " à retourné " . mysqli_num_rows($sql_resultat) . " alors qu'on en attend que 1");
			return null;
		}
	}
	
	public function requeteMultiResultats($sql)
	{
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Execution de la requête à plusieurs résultats : " . $sql);
		
		$sql_resultat = mysqli_query($this->_connection, $sql);
		if (!$sql_resultat)
		{
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Erreur d'execution de la requete SQL : " . $sql);
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Retour de la BDD : " . mysqli_error($this->_connection));
			return null;
		}
		
		$lignes = array();
		while($ligne = mysqli_fetch_assoc($sql_resultat))
		{
			$lignes[] = $ligne;
		} 
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "La requête a retourné " . count($lignes) . " résultats");
		
		return $lignes;
	}
	
	public function requeteSansResultat($sql)
	{
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Execution de la requête sans résultat : " . $sql);
		
		if (mysqli_query($this->_connection, $sql))
		{
			return 1;
		}
		else
		{
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Erreur d'execution de la requete SQL : " . $sql);
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Retour de la BDD : " . mysqli_error($this->_connection));
			return null;
		}
	}
	
	public function requeteInsertion($sql)
	{
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Execution de la requête d'insertion : " . $sql);
		
		if (mysqli_query($this->_connection, $sql))
		{
			return mysqli_insert_id($this->_connection);
		}
		else
		{
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Erreur d'execution de la requete SQL : " . $sql);
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Retour de la BDD : " . mysqli_error($this->_connection));
			return null;
		}
	}
	
	public function requeteModification($sql)
	{
		$this->_logger->log(Logger::$NIVEAUX["DEBUG"], "Execution de la requête de modification : " . $sql);
		
		if (mysqli_query($this->_connection, $sql))
		{
			return true;
		}
		else
		{
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Erreur d'execution de la requete SQL : " . $sql);
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Retour de la BDD : " . mysqli_error($this->_connection));
			return false;
		}
	}
	
	public function requeteSuppression($sql)
	{
		$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Execution de la requête de suppression : " . $sql);
		
		if (mysqli_query($this->_connection, $sql))
		{
			return true;
		}
		else
		{
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Erreur d'execution de la requete SQL : " . $sql);
			$this->_logger->log(Logger::$NIVEAUX["ERROR"], "Retour de la BDD : " . mysqli_error($this->_connection));
			return false;
		}
	}
}

?>