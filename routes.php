<?php namespace Initbiz\Pdfgenerator;

use Route;
use Redirect;
use Initbiz\Pdfgenerator\Models\Settings;

/**
 * Download PDF using this routing, but if you generated file to different path than in config than this won't work
 * @var Response
 */
Route::get('initbiz/pdfgenerator/download/{filename}/{token?}', function ($filename, $token) {
    $tokenize = Settings::get('pdf_tokenize', true);
    $tokenize = ($tokenize === "") ? true : $tokenize;

    $directory = Settings::get('pdf_dir', temp_path());
    $directory = ($directory === "") ? temp_path() : $directory;

    if ($token && $tokenize) {
        $localFileName = $directory.'/'.$filename.'_'.$token.'.pdf';
    } else {
        $localFileName = $directory.'/'.$filename.'.pdf';
    }

    $rmAfterDownload = Settings::get('pdf_rm_after_download', true);
    $rmAfterDownload = ($rmAfterDownload === "") ? true : $rmAfterDownload;

    if (!file_exists($localFileName)) {
        return Redirect::to('/404');
    }

    return \Response::download($localFileName, $filename.'.pdf')->deleteFileAfterSend($rmAfterDownload);
});
