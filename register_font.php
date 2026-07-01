<?php
require_once 'vendor/autoload.php';

$options = new Dompdf\Options();
$options->setRootDir(realpath('.'));
$options->setFontDir(realpath('vendor/dompdf/dompdf/lib/fonts'));
$options->setFontCache(realpath('vendor/dompdf/dompdf/lib/fonts'));

$dompdf = new Dompdf\Dompdf($options);
$fontMetrics = $dompdf->getFontMetrics();

// Download font directly
$fontUrl = "https://github.com/google/fonts/raw/main/ofl/hindsiliguri/HindSiliguri-Regular.ttf";
$fontPath = "vendor/dompdf/dompdf/lib/fonts/HindSiliguri-Regular.ttf";

// Use wget instead
echo "Downloading font...\n";
$content = file_get_contents("https://fonts.gstatic.com/s/hindsiliguri/v18/ijwOs5juQtsyLLR5jN4cxBEofJvQxuk.ttf");
file_put_contents($fontPath, $content);
echo "Font size: " . filesize($fontPath) . " bytes\n";

$fontMetrics->registerFont(
    ['family' => 'HindSiliguri', 'style' => 'normal', 'weight' => 'normal'],
    $fontPath,
    null
);

echo "Font registered!\n";
