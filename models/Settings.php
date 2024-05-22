<?php namespace Initbiz\Pdfgenerator\Models;

use Model;

class Settings extends Model
{
    public $implement = [
        'System.Behaviors.SettingsModel'
    ];

    public $settingsCode = 'initbiz_pdfgenerator_settings';

    public $settingsFields = 'fields.yaml';

    /**
     * Getting generator options in key => value syntax
     *
     * @return array
     */
    public function getOptionsKeyValue(): array
    {
        $options = $this->pdf_generator_options;
        if (!is_array($options)) {
            $options = [];
        }

        $parsed = [];

        foreach ($options as $additionalDataEntry) {
            // If the value is left empty, we're setting it to true
            // @see \Knp\Snappy\AbstractGenerator@buildCommand for the implementation
            $value = ($additionalDataEntry['value'] === '') ? true : $additionalDataEntry['value'];

            $parsed[$additionalDataEntry['key']] = $value;
        }

        return $parsed;
    }
}
