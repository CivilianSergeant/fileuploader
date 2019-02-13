var fileUploader = new Vue({
    el:'#fileUploaderRoot',
    data:{
        title: "File Uploader",
        file:null,
        maxFileSize:0,
        errorMessage:'',
        files:[],
        csrfToken:'',
        header:{
            headers: {'X-CSRF-Token': this.csrfToken,'Content-Type': 'multipart/form-data'},
            onUploadProgress: function( progressEvent ) {
                console.log(progressEvent)
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
        addFiles(){
            this.$refs.files.click();
        },
        removeFile:function(index){
            console.log(index);
            this.files.splice(index,1)
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
                    this.$refs['file-'+index][0].src=this.files[index].src;
                }
            },10);
        },
        onLoadImage:function(index){
            console.log(event,index);
        },
        handleFileUpload:function(){
            this.clearMessage();
            this.files = [];

            for(let f in this.$refs.files.files){
                let fileObj = this.$refs.files.files[f];
                if(typeof fileObj == 'object'){
                    this.reader =  new FileReader();
                    this.reader.onload = this.handleOnFileReader(this.reader,f);
                    fileObj.uploadPercentage = 0;
                    if(fileObj.size >= (this.maxFileSize*1024*1024)){
                        fileSize = Math.round(fileObj.size/(1024*1024))
                        fileObj.errorMessage = 'Maximum File Size '+this.maxFileSize+'Mb, Your file size is :'+fileSize+'Mb';
                        console.log('h',fileObj)
                    }
                    this.files.push(fileObj);
                    this.reader.readAsDataURL(fileObj);
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
            axios.post(base_url+uri+'/upload-process',formData,{
                headers: {'X-CSRF-Token': csrfToken,'Content-Type': 'multipart/form-data'},
                onUploadProgress: function( progressEvent ) {
                    
                    this.files[index].uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                    this.$refs['file-progress-'+index][0].value=this.files[index].uploadPercentage;
                    
                }.bind({$refs:this.$refs,files:this.files,index:index})
            }).then(res=>{

            }).catch(error=>{

            });
            
        }
    }
});