<?php namespace Initbiz\Pdfgenerator\PdfLayouts;

use Initbiz\Pdfgenerator\Classes\PdfLayout;

class Example extends PdfLayout
{
    public function prepareData($data)
    {
        parent::prepareData($data);

        $this->data['initlogo'] = $this->assetsPath.'/img/initlogo.png';
        $this->data['mdicss'] = $this->assetsPath.'/css/materialdesigniconspdf.min.css';

        $this->data['fonts'] = [
            1 => [
                'name' => 'Material Design Icons',
                'src'  => $this->assetsPath.'/fonts/mdi/materialdesignicons-webfont.svg',
                'format' => 'svg'
            ],
        ];
    }
}
