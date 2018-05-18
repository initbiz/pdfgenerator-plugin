# Pdf generator plugin
## Introduction
PdfGenerator plugin is a OctoberCMS plugin allow to generate pdf from twig template. 

## Quick start
### Prepare environment
1. Clone https://gitlab.init/october/pdfgenerator.git
1. Set env variables:
 * `WKHTMLTOPDF_BINARY` - path to binary file (default: `/plugins/initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd64`)
 * `PDF_TMP_DIR` - path to store temporary files (default: `/tmp/pdfgenerator`)
 * `PDF_RM_OLDER_THAN` - how long temporary files are remain in system in seconds (default 172800s) 
 
### Example useage

```
$pdf = new PdfGenerator();
$pdf->generateFromHtml('examples/bill.htm', ['name' => 'John'], 'filename');
```