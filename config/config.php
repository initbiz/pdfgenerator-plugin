<?php return [
          'pdf' => array(
                    'enabled' => true,
                    'binary'  => env('WKHTMLTOPDF_BINARY', plugins_path('initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd64')),
                    'timeout' => false,
                    'options' => array(),
                    'env'     => array(),
                )
        ];
