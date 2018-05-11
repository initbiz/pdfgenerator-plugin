<?php namespace Initbiz\Pdfgenerator;

use Backend;
use Event;
use System\Classes\PluginBase;
use File;
use Carbon\Carbon;

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
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
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

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Initbiz\Pdfgenerator\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'initbiz.pdfgenerator.some_permission' => [
                'tab' => 'pdfgenerator',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'pdfgenerator' => [
                'label'       => 'pdfgenerator',
                'url'         => Backend::url('initbiz/pdfgenerator/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['initbiz.pdfgenerator.*'],
                'order'       => 500,
            ],
        ];
    }
}
