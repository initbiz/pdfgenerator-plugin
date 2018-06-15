<?php namespace Initbiz\Pdfgenerator;

use File;
use Event;
use Config;
use Carbon\Carbon;

Event::listen('initbiz.pdfgenerator.afterGeneratePdf', function () {
    $rmOldFiles = Config::get('initbiz.pdfgenerator::config.pdf.rm_old_files');
    if ($rmOldFiles) {
        $pdfDir = Config::get('initbiz.pdfgenerator::config.pdf.pdf_dir');
        $olderThan = Config::get('initbiz.pdfgenerator::config.pdf.rm_older_than');

        foreach (File::files($pdfDir) as $file) {
            $mTime = File::lastModified($file->getRealPath());
            $carbonTime =  Carbon::now()->subSeconds($olderThan)->timestamp;
            if ($mTime < $carbonTime) {
                unlink($file->getRealPath());
            }
        }
    }
});
