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


    public function filterFields($fields, $context = null)
    {
        $engine = post('pdf_engine', $this->pdf_engine);

        // let it fail
        try {
            if ($engine === 'snappy') {
                $fields->pdf_generator_options->commentAbove = 'initbiz.pdfgenerator::lang.settings.pdf_generator_options_comment';
                $fields->pdf_binary->commentAbove = 'initbiz.pdfgenerator::lang.settings.pdf_binary_comment';
            } else if ($engine === 'chrome') {
                $fields->pdf_generator_options->commentAbove = 'initbiz.pdfgenerator::lang.settings.pdf_generator_options_comment_chrome';
                $fields->pdf_binary->commentAbove = 'initbiz.pdfgenerator::lang.settings.pdf_binary_comment_chrome';
            }
        }catch (\Exception $ex){}
    }
}
