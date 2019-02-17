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
        $targetDir = HtmlHelper::getOption('target_dir');
        $targetDir = (!empty($targetDir))? $targetDir : dirname(__DIR__).$targetDir.'/uploads/'; 

        if(!empty($uploadInterface)){
            $_FILES['file']['uploadDir'] = $targetDir;
            if(!file_exists($targetDir)){
                mkdir($targetDir,0777,true);
            }
            $uploadInterface->beforeUpload($_FILES['file']);
        }

        $uploadedFileName = basename($_FILES['file']['name']);
        $filename = $targetDir.$uploadedFileName;
        move_uploaded_file($_FILES['file']['tmp_name'],$filename);

        if(!empty($uploadInterface)){
            $_FILES['file']['uploaded_file_name'] = $uploadedFileName;
            $uploadInterface->afterUpload($_FILES['file']);
        }
    }
}