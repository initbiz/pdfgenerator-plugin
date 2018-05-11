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
        $pathToTemplate = plugins_path().'/initbiz/pdfgenerator/views/pdf/'.$layout;
        $template = File::get($pathToTemplate);

        $html = Twig::parse($template, $data);
        $tempFilename = '/tmp/pdfgenerator/'.$filename.'_'.str_random(15).'.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$filename.'.pdf"');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Pragma: public');
        $this->snappy->generateFromHtml($html, $tempFilename);
        Event::fire('initbiz.pdfgenerator.beforeDownloadPdf');
        echo readfile($tempFilename);
    }
}
