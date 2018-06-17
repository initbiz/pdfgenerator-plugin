<?php namespace Initbiz\Pdfgenerator;

use Initbiz\Pdfgenerator\Classes\Helpers;

/**
 * After generating PDF remove all old files
 */
Event::listen('initbiz.pdfgenerator.afterGeneratePdf', function () {
    Helpers::removeOldPdfs();
});
