<?php
namespace Muhimel\Helper;

class HtmlHelper
{
    private static $options;
    private static $assetPath;
    private static $assets = array();

    // Get Asset Path
    private static function getAssetPath($fileName)
    {
        if(empty(self::$assetPath)){
            return '../src/assets/'.$fileName;
        }

        return self::$assetPath.$fileName;
        
    }

    public static function setAssetPath($assetPath)
    {
        self::$assetPath=$assetPath;
        return true;
    }

    public static function setAsset($asset)
    {
        array_push(self::$assets,$asset);
        
    }

    public static function assets()
    {
        
        if(!empty(self::$assets)){
            foreach(self::$assets as $asset){
                $type = substr($asset,strrpos($asset,'.')+1);
                if($type == 'css'){
                    self::style($asset);
                }
                if($type == 'js'){
                    self::script($asset);
                }
                
            }
        }
    }

    public static function style($styleFileName)
    {
        echo '<link href="'.self::getAssetPath($styleFileName).'" rel="stylesheet" />';
    }
    
    public static function script($scriptFileName)
    {
        echo '<script src="'.self::getAssetPath($scriptFileName).'"></script>';
    }

    public static function setOptions($options)
    {
        self::$options = $options;
    } 

    public static function getOption($optionName)
    {
        if(!empty(self::$options[$optionName])){
            return self::$options[$optionName];
        }
        return null;
    }

    public static function getUploadUrl()
    {
        $url = '';
        if(!empty(self::$options) && !empty(self::$options['base_url']) && !empty(self::$options['upload_url'])){
            $url = self::$options['base_url'].self::$options['upload_url'];
        }
        
        return $url;
    }
}