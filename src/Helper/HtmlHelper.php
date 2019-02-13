<?php
namespace Muhimel\Helper;

class HtmlHelper
{
    private static $options;

    private function getAssetPath($assetPath){
        return '../src/assets/'.$assetPath;
    }

    public static function style($styleFileName)
    {
        echo '<link href="'.self::getAssetPath($styleFileName).'" rel="stylesheet" />';
    }
    
    public static function script($scriptFileName)
    {
        echo '<script src="'.self::getAssetPath($scriptFileName).'"></script>';
        //echo '<script src="'+self::getAssetPath($scriptFileName)+'"></script>';
    }

    public static function setOptions($options)
    {
        self::$options = $options;
    } 
}