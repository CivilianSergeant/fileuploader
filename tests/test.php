<?php
require_once __DIR__.'/../vendor/autoload.php';
use Muhimel\Uploader;
use Muhimel\Helper\HtmlHelper;

HtmlHelper::setAssetPath('../src/assets/');
HtmlHelper::setAsset('css/bootstrap.min.css');
HtmlHelper::setAsset('css/custom.css');
HtmlHelper::setAsset('js/vue/vue.js');
HtmlHelper::setAsset('js/axios/axios.min.js');
Uploader::getUI();