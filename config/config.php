<?php

return [
    'pdf' => [
        // By default the most resonable one is set
        'binary'  => env('WKHTMLTOPDF_BINARY', plugins_path('initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd64')),
    ]
];
