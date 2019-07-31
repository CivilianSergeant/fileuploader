# FILE UPLOADER
This is a little file uploading app
## Requirements
PHP >= 5.3.2

Vue Js v2

Axios JS

## Usage

IF project contain composer.json then follow the process
=

open composer.json from project root and add following line
-----------------------------------------------------------
require:{
  "muhimel/fileuploader":"v1.1.1.0-dev"
}

 run the following command from terminal (from project root)
-----------------------------------------------------------
composer update
or
recommanded to use this command so composer will only install this library
will not update other library used in the project

composer require --dev muhimel/fileuploader "v1.1.1.0-dev"

For CakePHP

create a plugin name(ex. FileUploader)

copy assets dir from vendor/muhimel/fileuploader/src 
create a dir name(uploader) under webroot and paste assets dir under uploader dir

Now Create UploadController within FileUploader Plugin

make sure you add following namespaces
```
use Muhimel\Uploader;
use Muhimel\Helper\HtmlHelper;
use Muhimel\Interfaces\UploaderInterface;
```

Now within UploadController create index method and write following code
controller should implement UploaderInterface interface;
        	
public function index($category,$accept,$suffix=null,$selectionType="single"){
    
    // change base url

	$baseUrl = 'http://localhost/appname/';
	HtmlHelper::setAssetPath($baseUrl.'uploader/assets/');
	HtmlHelper::setAsset('css/bootstrap.min.css');
		   
	HtmlHelper::setAsset('js/vue/vue.js');
	HtmlHelper::setAsset('js/axios/axios.min.js');

	Uploader::getUI([
		'base_url'=> $baseUrl,
		'upload_url'=>'file-upload/upload/process/'.$category.(!empty($suffix)? '/'.$suffix : ''),
				'csrf_token'=> $this->request->param('_csrfToken'),
				'uploader-object' => null, //optional
				'selection-type' => $selectionType,
				'accept'=> $accept // use document to allow (.docx,.xlsx,.pptx,pdf) or use images to allow (.png,.jpg,.bmp,.gif)
	]);
}


Now Write process method within UploadController

public function process($category,$suffix=null)
{
	$this->category = $category;
	
    // you can modify suffix here as your need


	if($this->request->allowMethod(['post'])){
		$targetDir = $_SERVER['DOCUMENT_ROOT'].$this->request->webroot.'webroot/uploads/'.((!empty($suffix))? $suffix.'/' : '');
		Uploader::setOptions(['target_dir'=>$targetDir]);
		Uploader::upload($this);
	}
	exit;
}

Write following 2 hook methods in UploadController

public function beforeUpload(&$file)
{
    // file upload error checking and error message

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

    // setting  file name and file original name 
    $file['original_filename'] = $file['name'];
    $file['name'] = $this->category.'-'.md5(microtime(true)).substr($file['name'],strrpos($file['name'],'.'));
    
    
}

public function afterUpload($file)
{
    // custom project specific code
    $uploadDir = explode('/',substr($file['uploadDir'],0,strlen($file['uploadDir'])-1));
    
    $lastFolderName = array_pop($uploadDir);
    $lastFolder = null;
    
    try{
        
        $uaFileFoldersRepo = TableRegistry::get('FileUpload.UaFileFolders');
        $lastFolder = $uaFileFoldersRepo->find('all')->where(['name'=>$lastFolderName])->first();
        
        if(!empty($lastFolder)){
            $uaFilesRepo = TableRegistry::get('FileUpload.UaFiles');
            $uaFile = $uaFilesRepo->newEntity();
            $uaFile->user_id = $this->logged_user_id;
            $uaFile->ua_file_folder_id = $lastFolder->id;
            $uaFile->file_name = $file['name'];
            $uaFile->file_orginal_name = $file['original_filename'];
            $uaFile->type = $file['type'];
            $uaFile->extension = substr($file['name'],(strrpos($file['name'],'.')+1),strlen($file['name']));
            $uaFile->file_size = $file['size'];
            $file['file']=$uaFile;
            $file['folder_id'] = $lastFolder->id;
            if(!$uaFilesRepo->save($uaFile)){
                $file['file_insert_error'] = $uaFile->errors();
            }
        }
    
    }catch(\Exception $ex){

        $file['error_message'] = $ex->getMessage();
        echo json_encode(array(
            'code'=>400,
            'uploaded' => $file
        ));
        exit;
    }

    // end of custom project specific code
    
    // this method should response like following

    echo json_encode(array(
        'code'=>200,
        'uploaded' => $file
    ));
    exit;
}

Make sure you have uploader.js file under plugins/ThemeAdmin/webroot/js/
also make sure have following method under your pagewise custom.js 

handleUploader:function(uploadedFiles){
    // all uploaded file information will be available in uploadedFiles 
    // handle your own code here 
}
