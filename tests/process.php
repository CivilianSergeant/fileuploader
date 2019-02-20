<?php
require_once __DIR__.'/../vendor/autoload.php';
use Muhimel\Uploader;
use Muhimel\Interfaces\UploaderInterface;
class EventListener implements UploaderInterface
{
    public function beforeUpload(&$file)
    {
        if(empty($file)){
            echo json_encode(array(
                'code'=>400,
                'message'=> 'Sorry! file not selected'
            ));
            exit;
        }

        if($file['size'] > (5*(1024*1024))){
            echo json_encode(array(
                'code'=>400,
                'message'=> 'Sorry! File size should not greater than 5Mb'
            ));
            exit;
        }
    }

    public function afterUpload($file)
    {
        print_r($file);exit;
    }
} 

Uploader::upload(new EventListener);