<?php
    return [
        'config' => [
            'pdf' => [
                'binary' => env('WKHTMLTOPDF_BINARY', plugins_path('initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd65')),
            ]
        ]
    ];
