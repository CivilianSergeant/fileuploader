<?php
namespace Muhimel;

use Muhimel\Helper\HtmlHelper;
use Muhimel\Interfaces\UploaderInterface;
use Psr\Http\Message\UploadedFileInterface;
class Uploader
{
    public static function getUI($options=null)
    {
        
        HtmlHelper::setOptions($options);
        require_once __DIR__.'/View/ui.php';
        exit;
    }

    public static function setOptions($option)
    {
        HtmlHelper::setOptions($option);
    }

    public static function upload(UploaderInterface $uploadInterface=null)
    {
        if(!empty($uploadInterface)){
            $uploadInterface->beforeUpload($_FILES['file']);
        }
        
        $targetDir = HtmlHelper::getOption('target_dir');
        $targetDir = (!empty($targetDir))? $targetDir : dirname(__DIR__).$targetDir.'/uploads/'; 
        $filename = $targetDir.basename($_FILES['file']['name']);

        move_uploaded_file($_FILES['file']['tmp_name'],$filename);

        if(!empty($uploadInterface)){
            $uploadInterface->afterUpload($_FILES['file'],$filename);
        }
    }
}