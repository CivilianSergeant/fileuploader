<?php
use Muhimel\Helper\HtmlHelper;

?>
<!doctype html>
<html>
    <head>
        <?php 
           HtmlHelper::assets();
        ?>
        <style>
            body{
                background:#a3dde6;
            }
            input[type="file"]{
                position: absolute;
                top: -500px;
            }
            img{
                width:100px;
                padding:4px;
                border:1px solid #aaa;
            }
            progress{
                width:98%;
                bottom:5px;
                left:10px
            }
            .pull-right{
                float:right !important;
            }
            tr{
                padding-top:5px;
                background:#fff;
            }
        </style>
        
    </head>
    <body>
        <div id="fileUploaderRoot" v-cloak>
            <div class="container-fluid mt-2">
                <h3>{{title}}</h3>
                <div class="large-12 medium-12 small-12 cell">
                <label>
                    <div class="large-12 medium-12 small-12 cell">
                        <button class="btn btn-info btn-sm" v-on:click="addFiles()">Add Files</button>
                    </div>
                    <input type="file" id="file" ref="files" accept="<?php echo HtmlHelper::getOption('accept')?>" multiple v-on:change="handleFileUpload()"/>
                </label>
                    
                </div>
            
            </div>
            <div class="container-fluid d-table">
                <table class="table table-borderd">
                    <tbody>
                        <tr v-for="(file,index) in files" :key="index" >
                            <td class="position-relative" >
                                <img v-if="file.isDOC" class="pull-left mb-2 mr-1" src="<?php echo HtmlHelper::getAssetPath('img/doc-512.png'); ?>"/>
                                <img v-if="file.isPDF" class="pull-left mb-2 mr-1" src="<?php echo HtmlHelper::getAssetPath('img/pdf-512.png'); ?>"/>
                                <img v-if="file.isIMG" class="pull-left mb-2 mr-1" :ref="'file-'+index" src="" />
                                <span>{{file.name}}</span>
                                <progress class="position-absolute" max="100" :ref="'file-progress-'+index" value="0" ></progress>
                                <span class="text-danger ml-1" v-if="file.errorMessage">{{file.errorMessage}}</span>
                                <button type="button" class="btn btn-danger btn-sm pull-right  ml-1" @click="removeFile(index)" >X</button>
                                <button v-if="file.uploadFlag"  type="button" class="btn btn-success btn-sm pull-right" @click="uploadFile(index)" >Upload</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr/>
                <button v-if="files.length" type="button" class="btn btn-info btn-sm pull-right" v-on:click="submitFile()">Upload All</button>
            </div>
        </div>
        <script>
            var fileUplader = new Vue({
                el:"#fileUploaderRoot",
                data:{
                    title: "File Uploader",
                    file:null,
                    maxFileSize:0,
                    errorMessage:'',
                    files:[],
                    csrfToken:'<?php echo (HtmlHelper::getOption("csrf_token")) ?>',
                    uploadUrl:'<?php echo HtmlHelper::getUploadUrl() ?>',
                    header:{
                        headers: {'X-CSRF-Token': this.csrfToken,'Content-Type': 'multipart/form-data'},
                        onUploadProgress: function( progressEvent ) {
                            //this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                        }.bind(this)
                    },
                    reader:null
                },
                mounted:function(){
                    this.maxFileSize=5;
                    
                },
                methods:{
                    clearMessage:function(){
                        this.errorMessage = '';
                    },
                    renderThumbnail:function(file){
                        if(file.type.match('image') != null){
                            file.isIMG = true;
                            file.isPDF = false;
                            file.isDOC = false;
                        }
                        if(file.type.match('pdf') != null){
                            file.isIMG = false;
                            file.isPDF = true;
                            file.isDOC = false;
                        }
                        if(file.type.match('doc') != null){
                            file.isIMG = false;
                            file.isPDF = false;
                            file.isDOC = true;
                        }
                    },
                    addFiles(){
                        this.$refs.files.click();
                    },
                    removeFile:function(index){
                        this.files.splice(index,1);
                        if(this.files.length==0){
                            window.location.reload();
                        }
                        this.refreshPreview();
                    },
                    refreshPreview(){
                        for(let i in this.files){
                            let file = this.files[i];
                            this.$refs['file-'+i][0].src=file.src;
                        }
                    },
                    handleOnFileReader:function(reader,index){
                        setTimeout(()=>{
                            if(this.files[index] != undefined){
                                this.files[index].src=reader.result;
                                if(this.files[index].isIMG){
                                    this.$refs['file-'+index][0].src=this.files[index].src;
                                }
                            }
                        },10);
                    },
                    handleFileUpload:function(){
                        this.clearMessage();
        
                        for(let f in this.$refs.files.files){
                            let fileObj = this.$refs.files.files[f];
                            let count = this.files.length;
                            count = (count<0)? 0: count;
                            
                            if(typeof fileObj == 'object'){
                                
                                reader =  new FileReader();
                                reader.onload = this.handleOnFileReader(reader,count);
                                fileObj.uploadPercentage = 0;
                                if(fileObj.size >= (this.maxFileSize*1024*1024)){
                                    fileSize = Math.round(fileObj.size/(1024*1024))
                                    fileObj.errorMessage = 'Maximum File Size '+this.maxFileSize+'Mb, Your file size is :'+fileSize+'Mb';
                                    
                                    fileObj.uploadFlag=false;
                                }
                                
                                fileObj.uploadFlag=true;
                                this.files.push(fileObj);
                                this.renderThumbnail(fileObj);
                                reader.readAsDataURL(fileObj);
                                
                            }
                        }
                    },
                    submitFile:function(){
                        for(let f in this.files){
                            this.uploadFile(f);
                        }
                    },
                    uploadFile:function(index){
                        let file =this.files[index];
                        let formData = new FormData();
                        formData.append('file', file);
                        let obj = this;
                        axios.post(this.uploadUrl,formData,{
                            headers: {'X-CSRF-Token': this.csrfToken,'Content-Type': 'multipart/form-data'},
                            onUploadProgress: function( progressEvent ) {
                                
                                this.files[index].uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                                if(this.files[index].uploadPercentage==100){
                                    obj.files[index].uploadFlag=false;
                                    obj.$forceUpdate();
                                }
                                this.$refs['file-progress-'+index][0].value=this.files[index].uploadPercentage;
                                
                            }.bind({$refs:this.$refs,files:this.files,index:index})
                        }).then(res=>{
                            if(res.data.code != 200){
                                this.files[index].errorMessage = res.data.message;  
                                obj.$forceUpdate();
                            }else if(res.data.code==200){
                                if(window.uploadedFiles == undefined){
                                    window.uploadedFiles = [];
                                }
                                window.uploadedFiles.push(res.data.uploaded);
                            }
                        }).catch(error=>{

                        });
                        
                    }
                }
            });
        </script>
    </body>
</html>
