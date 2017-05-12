<?php
define('FPDF_FONTPATH','.');
require('../fpdf.php');

$pdf=new FPDF();
$pdf->AddFont('Calligrapher','','calligra.php');
$pdf->AddPage();
$pdf->SetFont('Calligrapher','',35);
cell_utf8($pdf, 0,10,'Changez de police avec FPDF !');
$pdf->Output();
?>
