<?php namespace Initbiz\Pdfgenerator\Classes;

use File;
use Event;
use Carbon\Carbon;
use Initbiz\Pdfgenerator\Models\Settings;

class Helpers
{
    /**
     * Based on config vars remove pdfs older than specified
     * @return void
     */
    public static function removeOldPdfs()
    {
        $rmOldFiles = Settings::get('pdf_rm_old', false);
        $rmOldFiles = ($rmOldFiles === "") ? false : $rmOldFiles;

        if ($rmOldFiles) {
            $pdfDir = Settings::get('pdf_dir', temp_path());
            $pdfDir = ($pdfDir === "") ? temp_path() : $pdfDir;

            $olderThan = Settings::get('pdf_rm_older_than', 172800);
            $olderThan = ($olderThan === "") ? 172800 : $olderThan;

            foreach (File::files($pdfDir) as $file) {
                $mTime = File::lastModified($file->getRealPath());
                $carbonTime =  Carbon::now()->subSeconds($olderThan)->timestamp;
                if ($mTime < $carbonTime) {
                    unlink($file->getRealPath());
                }
            }
        }
    }
}
