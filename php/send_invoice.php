<?php
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Path to your image
$imagePath = 'D:/AppServ/www/hmijregistration/earlyregistration/img/Add a heading.png';
$imageData = base64_encode(file_get_contents($imagePath));
$imageBase64 = 'data:image/png;base64,' . $imageData;

// HTML content (your invoice)
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Invoice</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; color: #000; }
    .header { text-align: center; margin-bottom: 20px; }
    .header img { width: 100%; max-width: 600px; }
    h1 { text-align: center; margin: 0; }
    .registrar-copy { text-align: right; margin-top: 5px; margin-bottom: 20px; }
    .registrar-copy span {
      display: inline-block;
      background-color: #fff8c4;
      border: 1px solid #000;
      padding: 6px 12px;
      font-weight: bold;
      font-size: 14px;
    }
    .invoice-details, .bill-to {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }
    .invoice-details td, .bill-to td {
      padding: 6px 10px;
      vertical-align: top;
    }
    .invoice-details td.label { font-weight: bold; width: 150px; }
    .bill-to td { border: 1px solid #ccc; }
    .bill-to td.label { background: #f0f0f0; font-weight: bold; width: 150px; }
    .subject-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }
    .subject-table th, .subject-table td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
    }
    .subject-table th { background-color: #f0f0f0; }
    .total-units-row td { font-weight: bold; }
    @media print { body { margin: 0; } }
  </style>
</head>
<body>
  <div class="header">
    <img src="' . $imageBase64 . '" alt="School Header">
  </div>
  <h1>INVOICE</h1>
  <div class="registrar-copy">
    <span>Registrar\'s Copy</span>
  </div>
  <table class="invoice-details">
    <tr><td class="label">Date:</td><td>April 11, 2025</td></tr>
    <tr><td class="label">Invoice No.:</td><td>INV-20250411-001</td></tr>
  </table>
  <table class="bill-to">
    <tr><td class="label">Student Name:</td><td>Juan Dela Cruz</td></tr>
    <tr><td class="label">Student ID:</td><td>202310123</td></tr>
    <tr><td class="label">Program:</td><td>BS Information Technology</td></tr>
    <tr><td class="label">Year Level:</td><td>2nd Year</td></tr>
    <tr><td class="label">Semester:</td><td>2nd Semester</td></tr>
    <tr><td class="label">School Year:</td><td>2024-2025</td></tr>
  </table>
  <table class="subject-table">
    <thead>
      <tr>
        <th>Course Code</th>
        <th>Course Description</th>
        <th>Unit</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>IT101</td><td>Introduction to Computing</td><td>3</td></tr>
      <tr><td>IT102</td><td>Computer Programming 1</td><td>3</td></tr>
      <tr><td>IT103</td><td>Digital Logic</td><td>3</td></tr>
      <tr><td>IT104</td><td>Discrete Mathematics</td><td>3</td></tr>
      <tr><td>PE2</td><td>Physical Education 2</td><td>2</td></tr>
      <tr><td>NSTP2</td><td>National Service Training Program 2</td><td>3</td></tr>
      <tr><td>GE2</td><td>Understanding the Self</td><td>3</td></tr>
      <tr><td>GE3</td><td>Purposive Communication</td><td>3</td></tr>
      <tr class="total-units-row"><td colspan="2" style="text-align:right;">Total Units</td><td>23</td></tr>
    </tbody>
  </table>
</body>
</html>
';

// Create PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save to file
$pdfOutput = $dompdf->output();
$pdfFilePath = __DIR__ . '/invoice.pdf';
file_put_contents($pdfFilePath, $pdfOutput);

// Email via PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // your SMTP host
    $mail->SMTPAuth = true;
    $mail->Username = 'hmij.foundation.pic98@gmail.com'; // your email
    $mail->Password = 'xpts qmab xqaw vngt'; // use App Password if using Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('hmij.foundation.pic98@gmail.com', 'Registrar Office');
    $mail->addAddress('jordanamilasan789@gmail.com', 'Student Name'); // change to actual recipient
    $mail->addAttachment($pdfFilePath);

    $mail->isHTML(true);
    $mail->Subject = 'Your Student Invoice';
    $mail->Body    = 'Please find attached your invoice.';

    $mail->send();
    echo 'Invoice has been emailed.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
