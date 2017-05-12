<?php
require('../fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
cell_utf8($pdf, 40,10,'Hello World !');
$pdf->Output();
?>
