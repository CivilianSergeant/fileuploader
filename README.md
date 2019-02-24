# FILE UPLOADER
This is a little file uploading app
## Requirements
PHP >= 5

Vue.js v2

## Usage
Add muhimel/fileuploader:"v1.1.1.1-dev" line at the require section of composer.json file in your project

```
require:{
  muhimel/fileuploader:"v1.1.1.1-dev"
}
```
then run the following command 
```
composer update
```

To display the uploader ui

```
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
```
