<?php namespace Initbiz\Pdfgenerator\Classes;

use File;
use Knp\Snappy\Pdf;
use Twig;
use Config;

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
     * @param  object $layout
     * @param  array $data
     * @return string
     */
    public function generateFromHtml($layout ='', $data =[])
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="file.pdf"');
//        $loader = new \Twig_Loader_Filesystem(plugins_path().'/views/pdf');
//        $twig = new \Twig_Environment($loader);

        $pathToTemplate = plugins_path().'/initbiz/pdfgenerator/views/pdf/'.$layout;
        $template = File::get($pathToTemplate);
        $html = Twig::parse($template, $data);
        $fileName = str_random(20).'.pdf';
        // $snappy->setOption('cover', '<h1>Bill cover</h1>');
        $this->snappy->generateFromHtml($html, $fileName);
        echo readfile($fileName);
    }
}
