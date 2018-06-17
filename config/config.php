<?php

return [
    'pdf' => [
        //By default the most resonable one is set
        'binary' => env('WKHTMLTOPDF_BINARY', plugins_path('initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd64')),

        //where to store generated PDFs by default temp_path
        'tokenize' => env('PDF_TOKENIZE', true),

        //where to store generated PDFs by default temp_path
        'pdf_dir' => env('PDF_DIR', temp_path()),

        //Do we want to remove file after download
        'rm_after_download' => env('PDF_RM_AFTER_DOWNLOAD', true)),

        //Do we want to remove old files
        'rm_old_files' => env('PDF_RM_OLD', false)),

        //Time in seconds, by default two days
        'rm_older_than' => env('PDF_RM_OLDER_THAN', 172800),
    ]
];
