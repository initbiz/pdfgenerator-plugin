<?php namespace Initbiz\Pdfgenerator\Classes;

use File;
use Twig;
use Event;
use Redirect;
use Knp\Snappy\Pdf;
use Initbiz\Pdfgenerator\Models\Settings;
use Initbiz\Pdfgenerator\PdfLayouts\Example;

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
     * Filename on local storage, not the same as filename (that is used for downloading)
     * @var string
     */
    public $localFileName;

    public function __construct($layout = null)
    {
        $this->snappyPdf = new Pdf();

        //By default the most resonable one is set
        $this->snappyPdf->setBinary(Settings::get('pdf_binary', plugins_path('initbiz/pdfgenerator/vendor/bin/wkhtmltopdf-amd64')));

        //By default empty layout is set
        $this->layout = new Example();

        //By default empty data is set
        $this->data = [];

        //By default get from config. Before rendering you can override the variable.
        $this->directory = Settings::get('pdf_dir', temp_path());

        //By default files are tokenized on local filesystem
        $this->tokenize = Settings::get('pdf_tokenize', true);

        //By default it is just pseudorandom 15 chars long string
        $this->token = str_random(15);

        if ($layout) {
            $this->layout = $layout->path;
            $this->data = $layout->data;
        }
    }

    /**
     * Generate PDF using collected data
     * @return void
     */
    public function generatePdf()
    {
        Event::fire('initbiz.pdfgenerator.beforeGeneratePdf');
        if ($this->tokenize) {
            $this->localFileName = $this->directory.'/'.$this->filename.'_'.$this->token.'.pdf';
        } else {
            $this->localFileName = $this->directory.'/'.$this->filename.'.pdf';
        }

        $this->generateFromTwig($this->layout, $this->data, $this->localFileName);
    }

    /**
     *  Method to generate pdf from layout and data, return pdf path
     *
     * @param  string   $layout     Path to layout file
     * @param  array    $data       Parameters
     * @param  string   $filename   Output filename
     * @return void
     */
    public function generateFromTwig($layout, array $data =[], $filename)
    {
        $html = Twig::parse(File::get($layout), $data);
        $this->snappyPdf->generateFromHtml($html, $filename);
        Event::fire('initbiz.pdfgenerator.afterGeneratePdf');
    }

    /**
     * Redirect to download URL.
     * @return Redirect Redirect to URL which downloads file defined in routes.php file
     */
    public function downloadPdf()
    {
        Event::fire('initbiz.pdfgenerator.beforeDownloadPdf');

        if ($this->tokenize) {
            return Redirect::to('initbiz/pdfgenerator/download/'.$this->filename.'/'.$this->token);
        } else {
            return Redirect::to('initbiz/pdfgenerator/download/'.$this->filename);
        }
    }
}
