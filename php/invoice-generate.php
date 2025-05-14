<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$html = file_get_contents('invoice-template.html');

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfData = $dompdf->output();
$base64 = base64_encode($pdfData);
echo $base64; // Send this to JavaScript
?>
