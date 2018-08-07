<?php namespace Initbiz\Pdfgenerator\Classes;

abstract class PdfLayout
{
    use \System\Traits\ViewMaker;

    /**
     * Absolute path of assets, by default this will be October-style dir name in directory with class
     * @var string
     */
    public $assetsPath;

    /**
     * Path of the layout
     * @var string
     */
    public $path;

    /**
     * Data injected to PDF layout
     * @var array|object
     */
    public $data;

    public function __construct($data = [])
    {
        $this->viewPath = $this->guessViewPathFrom($this);

        $this->assetsPath = $this->viewPath.'/assets';

        $this->path = $this->viewPath.'/default.htm';

        $this->prepareData($data);
    }

    /**
     * To override or run in child class
     * @param  array|object $data data to inject to layout
     */
    public function prepareData($data)
    {
        $this->data = $data;
    }
}
