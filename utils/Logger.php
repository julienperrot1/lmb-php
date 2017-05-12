<?php

global $RACINE;
include_once ($RACINE . 'config.php');

class Logger
{
	public static $NIVEAUX = array("DEBUG" => 0, "INFO" => 1, "WARN" => 2, "ERROR" => 3);
	public static $NIVEAUX_INV = array(0 => "DEBUG", 1 => "INFO", 2 => "WARN", 3 => "ERROR");
	
    private $_nom_classe;
    private $_niveau;

	public function __construct($nom_classe, $niveau)
	{
	    $this->_nom_classe = $nom_classe;
		$this->_niveau = $niveau;
	}
	
	public function log($niveau, $log)
	{
		global $LOGGER_SORTIE;
		if ($niveau >= $this->_niveau)
		{
			$descripteur = fopen($LOGGER_SORTIE, "a");
			fwrite($descripteur, "[" . date("Y-m-d H:i:s") . "][" . $this->_nom_classe . "][" . Logger::$NIVEAUX_INV[$niveau] . "] " . $log . "\n");
			fclose($descripteur);
		}
		
		return 0;
	}
}

?>