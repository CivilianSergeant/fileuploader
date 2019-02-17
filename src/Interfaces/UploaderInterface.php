<?php
namespace Muhimel\Interfaces;

interface UploaderInterface{
    public function beforeUpload($file);
    public function afterUpload($file);
}