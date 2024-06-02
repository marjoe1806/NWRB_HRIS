<?php
use setasign\Fpdi\Fpdi;

    require 'fpdf.php';
    require_once 'FPDI-2.3.6/src/autoload.php';

    $pdf = new Fpdi('P','mm','A4');
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile('bir-form.pdf');
    // import page 1
    $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx, 4, 0, 199);
    
    // now write some text above the imported page
    $pdf->SetFont('Helvetica');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(45, 34);
    $pdf->Write(0, '2');
    $pdf->SetXY(51, 34);
    $pdf->Write(0, '0');
    $pdf->SetXY(57, 34);
    $pdf->Write(0, '2');
    $pdf->SetXY(63, 34);
    $pdf->Write(0, '2');
    
    $pdf->Output('I', 'generated.pdf');

?>