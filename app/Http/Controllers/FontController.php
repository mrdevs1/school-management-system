<?php
namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

class FontController extends Controller
{
    public static function loadFonts()
    {
        $fontDir = storage_path('fonts');
        $fontMetrics = $fontDir . '/dompdf_font_family_cache.php';

        if (!file_exists($fontMetrics)) {
            $options = new Options();
            $options->setChroot(base_path());
            $options->setFontDir($fontDir);
            $options->setFontCache($fontDir);

            $dompdf = new Dompdf($options);
            $fontFace = $dompdf->getFontMetrics();
            $fontFace->registerFont(
                ['family'=>'HindSiliguri','style'=>'normal','weight'=>'normal'],
                storage_path('fonts/HindSiliguri-Regular.ttf')
            );
            $fontFace->registerFont(
                ['family'=>'HindSiliguri','style'=>'normal','weight'=>'bold'],
                storage_path('fonts/HindSiliguri-Bold.ttf')
            );
        }
    }
}
