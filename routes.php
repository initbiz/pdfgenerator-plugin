<?php

declare(strict_types=1);

namespace Initbiz\Pdfgenerator;

use Route;
use Initbiz\Pdfgenerator\Classes\PdfGenerator;

/**
 * Download PDF using this routing, but if you generated file to different path than in config than this won't work
 * @var Response
 */
Route::get('initbiz/pdfgenerator/download/{filename}/{token?}', function ($filename, $token) {
    return PdfGenerator::prepareDownloadResponse($filename, $token);
});
