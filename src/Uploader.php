<?php
namespace Muhimel;

use Muhimel\Helper\HtmlHelper;

class Uploader
{
    public static function getUI($options=null)
    {
        
        HtmlHelper::setOptions($options);
        require_once __DIR__.'/View/ui.php';
        exit;
    }
}