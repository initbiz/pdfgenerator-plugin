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
        $this->snappyPdf->generateFromHtml($html, $localFileName);
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
            return Redirect::to('initbiz/pdfgenerator/download/'.$this->filename.'/'.$this->token, 302, ['X-PJAX' => false]);
        } else {
            return Redirect::to('initbiz/pdfgenerator/download/'.$this->filename, 302, ['X-PJAX' => false]);
        }
    }

    /**
     * Set local file name
     */
    public function getLocalFileName()
    {
        if ($this->tokenize) {
            $localFileName = $this->filename.'_'.$this->token.'.pdf';
        } else {
            $localFileName = $this->filename.'.pdf';
        }

        return $localFileName;
    }

    /**
     * Get local file name
     */
    public function getLocalRootPath()
    {
        return $this->directory.'/'.$this->getLocalFileName();
    }

    /**
     * Get full filename
     */
    public function getDownloadFileName()
    {
        return $this->filename . '.pdf';
    }
}
