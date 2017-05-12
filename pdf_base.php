<?php

$RACINE = "";

require($RACINE . 'utils/fpdf/fpdf.php');
include_once ($RACINE . 'utils/Bdd.php');
include_once ($RACINE . 'utils/load_session.php');

$BDD = new Bdd();

$pdf = new FPDF();
$pdf->SetAutoPageBreak(false);

function dessine_tableau($pdf, $ligne_entete, $lignes_donnees, $tailles_colonnes, $hauteur_ligne_entete, $hauteur_ligne, $positions_donnees)
{
	for($i = 0; $i < count($ligne_entete); $i++)
	{
		cellule($pdf, $tailles_colonnes[$i], $hauteur_ligne_entete, $ligne_entete[$i], 1, 0, 'C');
	}
	$pdf->Ln();

	for($i = 0; $i < count($lignes_donnees); $i++)
	{
		for($j = 0; $j < count($lignes_donnees[$i]); $j++)
		{
			cellule($pdf, $tailles_colonnes[$j], $hauteur_ligne, $lignes_donnees[$i][$j], 1, 0, $positions_donnees[$j]);
		}
		$pdf->Ln();
	}
	
	cellule($pdf, array_sum($tailles_colonnes), 0, '', 'T', 1, 'C');
}

function cellule($pdf, $largeur, $hauteur, $texte, $bordure, $retour_ligne, $alignement)
{
	$pdf->Cell($largeur, $hauteur, utf8_decode($texte), $bordure, $retour_ligne, $alignement, 1);
}

function espace_largeur($pdf, $largeur, $bordure = 0)
{
	$pdf->Cell($largeur, 1, "", $bordure, 0, "C");
}

function espace_hauteur($pdf, $hauteur)
{
	$pdf->Cell(1, $hauteur, "", 0, 1, "C");
}

?>