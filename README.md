# FILE UPLOADER
This is a little file uploading app
## Requirements
PHP >= 5.3.2

Vue Js v2

Axios JS

## Usage
Add muhimel/fileuploader:"v1.1.1.0-dev" line at the require section of composer.json file in your project

```
require:{
  muhimel/fileuploader:"v1.1.1.0-dev"
}
```
then run the following command 
```
composer update
```
or just run following command to install this package in your project

```
composer require --dev muhimel/fileuploader "v1.1.1.0-dev"
```

To display the uploader ui

```
require_once __DIR__.'/../vendor/autoload.php';
use Muhimel\Uploader;
use Muhimel\Helper\HtmlHelper;

// Make sure all asset and asset path contain following files in your project 
HtmlHelper::setAssetPath('../src/assets/');
HtmlHelper::setAsset('css/bootstrap.min.css');
HtmlHelper::setAsset('css/custom.css');
HtmlHelper::setAsset('js/vue/vue.js');
HtmlHelper::setAsset('js/axios/axios.min.js');

Uploader::getUI([
    'base_url'=> $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/',
    'upload_url'=>'path/to/process.php',
    'csrf_token'=> 'testToken',
    'uploader-object' => 'abc',
    'accept'=>'images' // use document to allow (.docx,.xlsx,.pptx,pdf) or use images to allow (.png,.jpg,.bmp,.gif)
]);
```

To process file upload request

```
require_once __DIR__.'/../vendor/autoload.php';
use Muhimel\Uploader;
use Muhimel\Interfaces\UploaderInterface;

class EventListener implements UploaderInterface
{
    public function beforeUpload(&$file)
    {
        // write file check code here
        
    }

    public function afterUpload($file)
    {
        // write database code here
    }
} 

Uploader::upload(new EventListener);
```
