<?php namespace Initbiz\Pdfgenerator;

use Backend;
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
