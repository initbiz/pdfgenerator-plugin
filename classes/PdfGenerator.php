<?php namespace Initbiz\Pdfgenerator\Classes;

use File;
use Knp\Snappy\Pdf;
use Twig;
use Config;
use Event;

class PdfGenerator
{
    public $snappy;
    public function __construct()
    {
        $this->snappy = new Pdf();
        $this->snappy->setBinary(Config::get('initbiz.pdfgenerator::config.pdf.binary'));
    }

    /**
     *  Method to generate pdf from layout and data, return pdf path
     *
     * @param  string $layout   Path to layout file
     * @param  array $data      Parameters
     * @param  string $filename Output filename
     * @return string
     */
    public function generateFromHtml($layout, array $data =[], $filename)
    {
        $html = Twig::parse(File::get($layout), $data);
        $this->snappy->generateFromHtml($html, $filename);
        Event::fire('initbiz.pdfgenerator.beforeDownloadPdf');
    }
}
