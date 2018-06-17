<?php namespace Initbiz\Pdfgenerator;

use Config;

/**
 * Download PDF using this routings, but if you generated file to different path than in config this won't work
 * @var Response
 */
Route::get('initbiz/pdfgenerator/download/{filename}/{token?}', function ($filename, $token) {
    $tokenize = Config::get('initbiz.pdfgenerator::config.pdf.tokenize');

    if ($token && $tokenize) {
        $localFileName = $this->directory.'/'.$filename.'_'.$token.'.pdf';
    } else {
        $localFileName = $this->directory.'/'.$filename.'.pdf';
    }

    $rmAfterDownload = Config::get('initbiz.pdfgenerator::config.pdf.rm_after_download');

    return \Response::download($localFileName, $filename.'.pdf')->deleteFileAfterSend($rmAfterDownload);
});
