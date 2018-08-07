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
            'name'        => 'initbiz.pdfgenerator::lang.plugin.name',
            'description' => 'initbiz.pdfgenerator::lang.plugin.description',
            'author'      => 'initbiz',
            'icon'        => 'icon-file-pdf-o'
        ];
    }

    public function registerSettings()
    {
        return [
            'pdf_generator' => [
                'label'          => 'initbiz.pdfgenerator::lang.settings.settings_label',
                'description'    => 'initbiz.pdfgenerator::lang.settings.settings_description',
                'icon'           => 'icon-file-pdf-o',
                'class'          => 'Initbiz\PdfGenerator\Models\Settings',
                'permissions'    => [],
                'order'          => 400
            ],
        ];
    }
}
