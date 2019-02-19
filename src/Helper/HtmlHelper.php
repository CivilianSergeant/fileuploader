<?php
namespace Muhimel\Helper;

class HtmlHelper
{
    private static $options;
    private static $assetPath;
    private static $assets = array();

    // Get Asset Path
    public static function getAssetPath($fileName)
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
        if(!empty($options['accept'])){
            if(strtolower($options['accept']) == 'pdf'){
                $options['accept'] = 'application/pdf';
                $options['allowed-file-type'] = 'application/pdf'; 
                $options['allowed-file-ext']  = 'pdf';   
            }
            if(strtolower($options['accept'] == 'doc')){
                $options['accept'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                $options['allowed-file-type'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'; 
                $options['allowed-file-ext']  = 'docx';   
            }
            if(strtolower($options['accept'] == 'excel')){
                $options['accept'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $options['allowed-file-type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; 
                $options['allowed-file-ext']  = 'xlsx';
            }
            if(strtolower($options['accept'] == 'ppt')){
                $options['accept'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                $options['allowed-file-type'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation'; 
                $options['allowed-file-ext']  = 'pptx';
            }
            if(strtolower($options['accept']) == 'png'){
                $options['accept'] = 'image/png';
                $options['allowed-file-type'] = 'image/png';    
                $options['allowed-file-ext']  = 'png';
            }
            if(strtolower($options['accept']) == 'jpg'){
                $options['accept'] = 'image/jpeg';
                $options['allowed-file-type'] = 'image/jpeg';  
                $options['allowed-file-ext']  = 'jpg';  
            }
            
        }
        self::$options = $options;
    } 

    public static function getOption($optionName)
    {
       
        if(!empty(self::$options[$optionName])){
            return self::$options[$optionName];
        }
        if($optionName == 'accept'){
            return (!empty(self::$options[$optionName]))? self::$options[$optionName] : 'image/*,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
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