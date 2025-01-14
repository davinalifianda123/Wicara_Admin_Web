<?php

$html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @page {
                background-image: url("../assets/WadahInformasiCatatanAspirasi&RatingAkademikWICARA.png");
                background-image-resize: 6;
            }
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                color: #fff;
            }
            .poster {
                margin: 0 auto;
                text-align: center;
                width: 100%;
            }
            .title h1 {
                margin-top: 45px;
                font-size: 54px;
                margin-bottom: 5px;
            }
            .title h2 {
                font-size: 36px;
                margin: 0;
            }
            .qr-code {
                margin-top: 36px;
            }
            .instructions {
                margin-top: 36px;
            }
            .footer {
                color: #fff;
                padding: 10px;
                text-align: center;
                margin-top: 45px;
            }
            .footer p {
                margin: 5px;
            }
        </style>
    </head>
    <body>
        <div class="poster">
            <img width="180" src="../assets/Polines.png" alt="Logo Polines">
            <div class="title">
                <h1>POLIKLINIK</h1>
                <h2>SCAN HERE TO RATE</h2>
            </div>
            <img class="qr-code" width="320" src=".." alt="QR Code">
            <img class="instructions" width="75%" src="../assets/instructions.png" alt="Instructions">
            <div class="footer">
                <p>Powered by</p>
                <img width="150" src="../assets/logo wicara.png" alt="Logo Wicara">
            </div>
        </div>
    </body>
    </html>
    ';
echo $html; die;

$path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
require_once $path . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
	'format' => 'A4', 
	'margin_left' => 20,
	'margin_right' => 15,
	'margin_top' => 48,
	'margin_bottom' => 25,
	'margin_header' => 10,
	'margin_footer' => 10
]);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Acme Trading Co. - Invoice");
$mpdf->SetAuthor("Acme Trading Co.");
$mpdf->SetWatermarkText("Paid");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($html);

$mpdf->Output();
