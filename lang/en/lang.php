<?php return [
    'plugin' => [
        'name' => 'Pdfgenerator',
        'description' => 'Generate PDFs in your October CMS project',
    ],
    'settings' => [
        'settings_label' => 'PDF generator',
        'settings_description' => 'Change you PDF generation settings',
        'pdf_tokenize_label' => 'Tokenize local filename',
        'pdf_tokenize_comment' => 'Adds pseudorandom 15 chars token to local filename. It does not affect downloaded filename',
        'pdf_dir_label' => 'Path to store PDFs',
        'pdf_dir_comment' => 'By default it is temp_path()',
        'pdf_rm_download_label' => 'Remove PDFs after download',
        'pdf_rm_old_label' => 'Remove old files',
        'pdf_rm_older_than_label' => 'Remove files older than',
        'pdf_rm_older_than_comment' => 'In seconds time to store PDFs. By default 172800 - two days',
        'pdf_binary_label' => 'Binary path',
        'pdf_binary_comment' => 'Path to wkhtmltopdf, you can start the path with \~ (root path) or \$ (plugins path)',
    ],
];
