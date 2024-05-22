<?php

namespace Initbiz\Pdfgenerator\Classes;

use File;
use Twig;
use Event;
use Redirect;
use Response;
use Knp\Snappy\Pdf;
use Initbiz\Pdfgenerator\Models\Settings;
use Initbiz\Pdfgenerator\PdfLayouts\Example;
use Symfony\Component\HttpFoundation\Response as ResponseBase;

class PdfGenerator
{
    /**
     * Snappy PDF class
     * @var Pdf
     */
    public $snappyPdf;

    /**
     * Twig layout of PDF path
     * @var string
     */
    public $layout;

    /**
     * Filename of the generated PDF
     * @var string
     */
    public $filename;

    /**
     * Absolute path of generated PDF
     * @var string
     */
    public $directory;

    /**
     * Do you want to tokenize filenames? True, false
     * @var boolean
     */
    public $tokenize;

    /**
     * Random text to add to temporary filename
     * @var string
     */
    public $token;

    /**
     * Data to be injected to PDF layout
     * @var array
     */
    public $data;

    /**
     * Constructor
     * @param string                                 $filename name of PDF file
     * @param Initbiz\Pdfgenerator\Classes\PdfLayout $layout   Layout of PDF
     */
    public function __construct($filename = 'generated', $layout = null)
    {
        $this->snappyPdf = new Pdf();

        //By default the most resonable one is set
        $binaryPath = Settings::get('pdf_binary', plugins_path('initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd64'));
        $binaryPath = ($binaryPath === "") ? plugins_path('initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd64') : $binaryPath;

        $pathAlias = substr($binaryPath, 0, 1);

        if ($pathAlias === '$') {
            $binaryPath = plugins_path(substr($binaryPath, 1));
        } elseif ($pathAlias === '~') {
            $binaryPath = base_path(substr($binaryPath, 1));
        }

        $this->snappyPdf->setBinary($binaryPath);

        $this->filename = $filename;

        //By default empty data is set
        $this->data = [];

        if ($layout) {
            $this->layout = $layout->path;
            $this->data = $layout->data;
        } else {
            //By default empty layout is set
            $this->layout = new Example();
        }

        //By default get from config. Before rendering you can override the variable.
        $this->directory = Settings::get('pdf_dir', temp_path());
        $this->directory = ($this->directory === "") ? temp_path() : $this->directory;

        //By default files are tokenized on local filesystem
        $this->tokenize = Settings::get('pdf_tokenize', true);
        $this->tokenize = ($this->tokenize === "") ? true : $this->tokenize;

        //By default it is just pseudorandom 15 chars long string
        $this->token = str_random(15);
    }

    /**
     * Generate PDF using collected data
     * @return void
     */
    public function generatePdf()
    {
        Event::fire('initbiz.pdfgenerator.beforeGeneratePdf');

        $this->generateFromTwig($this->layout, $this->getLocalRootPath(), $this->data);
    }

    /**
     *  Method to generate pdf from layout and data, return pdf path
     *
     * @param  string   $layout         Path to layout file
     * @param  string   $localFileName  Local file name
     * @param  array    $data           Parameters
     * @return void
     */
    public function generateFromTwig($layout, $localFileName, array $data = [])
    {
        $html = Twig::parse(File::get($layout), $data);
        $settings = Settings::instance();
        $this->snappyPdf->generateFromHtml($html, $localFileName, $settings->getOptionsKeyValue());

        Event::fire('initbiz.pdfgenerator.afterGeneratePdf', [$this]);
    }

    /**
     * Redirect to download URL.
     * @return Redirect Redirect to URL which downloads file defined in routes.php file
     */
    public function downloadPdf()
    {
        Event::fire('initbiz.pdfgenerator.beforeDownloadPdf', [$this]);

        if ($this->tokenize) {
            return Redirect::to('initbiz/pdfgenerator/download/' . $this->filename . '/' . $this->token);
        } else {
            return Redirect::to('initbiz/pdfgenerator/download/' . $this->filename);
        }
    }

    /**
     * Download the file directly using AJAX
     * @return Redirect Redirect download to file
     */
    public function downloadPdfDirectly()
    {
        Event::fire('initbiz.pdfgenerator.beforeDownloadPdf', [$this]);
        Event::fire('initbiz.pdfgenerator.beforeDownloadPdfAjax', [$this]);

        return self::prepareDownloadResponse($this->filename, $this->token);
    }

    /**
     * Set local file name
     */
    public function getLocalFileName()
    {
        if ($this->tokenize) {
            $localFileName = $this->filename . '_' . $this->token . '.pdf';
        } else {
            $localFileName = $this->filename . '.pdf';
        }

        return $localFileName;
    }

    /**
     * Get local file name
     */
    public function getLocalRootPath()
    {
        return $this->directory . '/' . $this->getLocalFileName();
    }

    /**
     * Get full filename
     */
    public function getDownloadFileName()
    {
        return $this->filename . '.pdf';
    }

    /**
     * Prepare the response that will contain the PDF to download and will handle deletion
     *
     * @param string $filename
     * @param string|null $token
     * @return ResponseBase
     */
    public static function prepareDownloadResponse(string $filename, ?string $token = null): ResponseBase
    {
        $tokenize = Settings::get('pdf_tokenize', true);
        $tokenize = ($tokenize === "") ? true : $tokenize;

        $directory = Settings::get('pdf_dir', temp_path());
        $directory = ($directory === "") ? temp_path() : $directory;

        if ($token && $tokenize) {
            $localFileName = $directory . '/' . $filename . '_' . $token . '.pdf';
        } else {
            $localFileName = $directory . '/' . $filename . '.pdf';
        }

        $rmAfterDownload = Settings::get('pdf_rm_after_download', true);
        $rmAfterDownload = ($rmAfterDownload === "") ? true : $rmAfterDownload;

        if (!file_exists($localFileName)) {
            return Redirect::to('/404');
        }

        return Response::download($localFileName, $filename . '.pdf')->deleteFileAfterSend($rmAfterDownload);
    }
}
