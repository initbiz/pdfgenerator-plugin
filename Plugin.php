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
}
