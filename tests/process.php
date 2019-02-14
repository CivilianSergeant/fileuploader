<?php
require_once __DIR__.'/../vendor/autoload.php';
use Muhimel\Uploader;
use Muhimel\Interfaces\UploaderInterface;
class EventListener implements UploaderInterface
{
    public function beforeUpload($file)
    {
        echo 'before <pre>';
        print_r($file);
    }

    public function afterUpload($file, $uploadedFilename)
    {
        echo 'after'.$uploadedFilename;
    }
} 

Uploader::upload(new EventListener);