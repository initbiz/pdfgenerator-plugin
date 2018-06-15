<?php namespace Initbiz\Pdfgenerator;

use File;
use Event;
use Backend;
use Carbon\Carbon;
use System\Classes\PluginBase;

/**
 * pdfgenerator Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Pdfgenerator',
            'description' => 'No description provided yet...',
            'author'      => 'initbiz',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Event::listen('initbiz.pdfgenerator.beforeDownloadPdf', function () {
            foreach (File::files(env('PDF_TMP_DIR', '/tmp/pdfgenerator')) as $file) {
                $path = $file->getRealPath();
                $mTime = File::lastModified($file->getRealPath());
                $carbonTime =  Carbon::now()->subSeconds(env('PDF_RM_OLDER_THAN', 172800))->timestamp;
                if ($mTime  < $carbonTime) {
                    unlink($file->getRealPath());
                }
            }
        });
    }
}
