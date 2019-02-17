<?php
require_once __DIR__.'/../vendor/autoload.php';
use Muhimel\Uploader;
use Muhimel\Helper\HtmlHelper;

HtmlHelper::setAssetPath('../src/assets/');
HtmlHelper::setAsset('css/bootstrap.min.css');
HtmlHelper::setAsset('css/custom.css');
HtmlHelper::setAsset('js/vue/vue.js');
HtmlHelper::setAsset('js/axios/axios.min.js');

Uploader::getUI([
    'base_url'=> $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/',
    'upload_url'=>'file-uploader/muhimel/fileuploader/tests/process.php',
    'csrf_token'=> 'testToken',
    'accept'=>'image/*'
]);