<?php
use Muhimel\Helper\HtmlHelper;
?>
<!doctype html>
<html>
    <head>
        <?php 
           HtmlHelper::style('css/bootstrap.min.css?v='.time());
           HtmlHelper::style('css/custom.css?v='.time());
           HtmlHelper::script('js/vue/vue.js?v='.time());
           HtmlHelper::script('js/axios/axios.min.js?v='.time());
        ?>
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
                    <input type="file" id="file" ref="files" accept="image/*" multiple v-on:change="handleFileUpload()"/>
                </label>
                    
                </div>
            
            </div>
            <div class="container-fluid d-table">
                <table class="table table-borderd">
                    <tbody>
                        <tr v-for="(file,index) in files" :key="index" >
                            <td class="position-relative">
                                <img class="pull-left mb-2 mr-1" :ref="'file-'+index" src="" />
                                <span>{{file.name}}</span>
                                <progress class="position-absolute" max="100" :ref="'file-progress-'+index" value="0" ></progress>
                                <span class="text-danger" v-if="file.errorMessage">{{file.errorMessage}}</span>
                                <button type="button" class="btn btn-danger btn-sm pull-right  ml-1" @click="removeFile(index)" >X</button>
                                <button v-if="!file.errorMessage" type="button" class="btn btn-success btn-sm pull-right" @click="uploadFile(index)" >Upload</button>
                            </td>
                        </tr>
                    </tbody>

                </table>
                <hr/>
                <button class="btn btn-info btn-sm pull-right" v-on:click="submitFile()">Upload All</button>
            </div>
        </div>
        <?php
            HtmlHelper::script('js/uploader.js?v='.time());
        ?>
    </body>
</html>
