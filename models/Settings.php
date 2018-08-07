<?php namespace Initbiz\Pdfgenerator\Models;

use Model;

class Settings extends Model
{
    public $implement = [
        'System.Behaviors.SettingsModel'
    ];

    public $settingsCode = 'initbiz_pdfgenerator_settings';

    public $settingsFields = 'fields.yaml';
}
