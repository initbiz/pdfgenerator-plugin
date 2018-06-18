# Pdf generator plugin
## Introduction
PdfGenerator plugin is a OctoberCMS plugin allow to generate pdf from twig template. It uses [knplabs/snappy](https://github.com/knplabs/snappy).

### Yet another PDF generator?
Yes, we decided to write another PDF generator. But this one differs a little from all out there. One thing is that it uses `wkhtmltopdf` and the second thing is that it is simplified as much as possible for OctoberCMS (no special requirements and messy code). What is more, it is dedicated to generate PDFs for frontend users and return them in OctoberCMS's AJAX (using routing that is almost transparent to user).

## How to
### Configure environment
The very first thing to do is to set absolute path to `wkhtmltopdf` binary using `WKHTMLTOPDF_BINARY` in your `.env` file.

You are ready to go. But sometimes you may want to customize more `.env` variables:
 * `PDF_TOKENIZE` - boolean value to add pseudorandom 15 chars token to local filename. It does not affect downloaded filename so is set to true by default
 * `PDF_DIR` - path to store generated PDF files, default `temp_path()`
 * `PDF_RM_AFTER_DOWNLOAD` - remove local file right after sending to user, default true
 * `PDF_RM_OLD` - if you do not want to remove files right after sending to user, then maybe files older than
 * `PDF_RM_OLDER_THAN` - how long temporary files are remain in system in seconds (default 172800 - two days)

### Example usage
In this example is `onDownloadPdf` AJAX handler that generates PDF using our plugin. As simple as that:

```php
    use Initbiz\Pdfgenerator\Classes\PdfGenerator;

    ...

    /*
     * OctoberCMS AJAX handler to download PDF
     */
    public function onDownloadPdf()
        $pdfGenerator = new PdfGenerator();

        //Set absolute path of the Twig layout
        $pdfGenerator->layout = plugins_path().'/initbiz/exampleplugin/views/pdf/pdflayout.htm';

        //Set filename for downloaded file
        $pdfGenerator->filename = "my-pdf";

        //Set data which will be sent to the layout
        $pdfGenerator->data = ['viewData' => 'Value of viewData'];

        $pdfGenerator->generatePdf();

        return $pdfGenerator->downloadPdf();
    }
```
