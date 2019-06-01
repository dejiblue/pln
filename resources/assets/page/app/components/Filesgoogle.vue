<template>
	<div class="list-ctn">
		<b-card >
			
			<div class="header-lst" slot="header">	
            	<h6 class="h mb-0">{{$lang('google.title')}}</h6>
            	<span v-if="total"> {{$lang('google.info',{total:total})}}</span>
            	<span class="s"></span>
            	<b-button @click="uploadfile">+</b-button>
            	<input @change="file_change" type="file" id="file-google-upload" style="position: absolute;top: -6000px;left: -90000px;">
			</div>

			<div v-if="loader" style="background-color: rgba(0, 0, 0, 0.16); z-index: 1000 ;position: absolute;top: 0;left: 0;right: 0;bottom: 0;"><loader></loader></div>

			<b-alert v-if="warning" show variant="warning">
				{{$lang('google.load.warning')}}
			</b-alert>

			<b-list-group  v-if="!warning" >
			  	
			  	<b-list-group-item v-bind:key="key" v-for="(file, key, index) in files" @mouseenter="mouseenter(key)" @mouseleave="mouseleave" class="d-flex justify-content-between  align-items-center" button>
			  		<span>
			  			<img :src="'./img/file-html.png'" alt="file">
						<span>
							<span>{{file.name}}</span>
						</span>
			  		</span>
			  		<b-badge variant="danger"  @click="del(file)" v-show="hovered==key&&(hovered||hovered===0)" pill>x</b-badge>
			  	</b-list-group-item>

			</b-list-group>
            
        </b-card>
	</div>
</template>

<script>
	
    import { get, post } from '../lib/api'


	export default {

		props : [], 

		data(){

            return {

            	total : null , 

                form: {
                    file : '',
                },

                loader : true  , 

                files : [] ,

                hovered : null , 

                warning : false , 

            }
    
        },

        methods : {

        	mouseenter : function ( index ) {
        		this.hovered = index ; 
        	},

        	mouseleave : function () {
        		this.hovered = null ; 
        	},

        	del : function ( index ) {


        		let r = confirm(this.$lang('google.delete.conf', index ));

				if (r == true) {
					
					let url = window.urlapp+'/detache' ; 

	                var formData = new FormData();

	                formData.append("_method", 'post' );
	                formData.append("token", document.csrf_token);
	                formData.append("attache_id", index.id);
	            
	                this.loader = true ; 

	                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

	                    .then((res)=>{

	                        this.loader = false ; 
	                        if ( res.data.success ) {
	                        	this._$emit('alert',this.$lang('google.delete.alert', index )); 
				                this.loader = true ; 
								return setTimeout(()=>{this.findattachefile()}, 1000 );
	                        }
	                        this._$emit('alertError',this.$lang('google.delete.alert.erreur', index )); 
	                    
	                    })  

	                    .catch((err)=>{
	                        this.loader = false ; 
	                        this._$emit('alertError',this.$lang('google.delete.alert.erreur', index ));  
	                    })

				} 
        	
        	},

        	uploadfile : function () {
        		
        		$('#file-google-upload').trigger('click') ; 

        	}, 

        	findattachefile : function () {

	            this.loader = true ; 

        		let url = window.urlapp+'/attache' ;  

	                get( url )

	                    .then((res)=>{

	                  		this.loader = false ; 
	                    	if ( res.data && res.data.success ) {
	                  			this.total = res.data.success.length;
	                  			return this.files = res.data.success ; 
	                  		}

	                  		this.warning = true ; 

	                    })  

	                    .catch((err)=>{
	                  		this.loader = false ; 
	                  		this.warning = true ; 
	                    })

        	},

        	file_change : function ( e ) {
        		
        		if ( e.target.files.length == 0 ) {
        			return
        		}

        		let url = window.urlapp+'/attache' ; 

                var formData = new FormData();

                formData.append("token", document.csrf_token);
                formData.append("attachable_id", document.user.id );    
                formData.append("attachable_type", 'App\\User' );    
                formData.append("file", e.target.files[0] );    
   
                this.loader = true ; 

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{

                        this.loader = false ; 
                        
                        if ( res.data.error ) {  
                        	this._$emit('alertError',this.$lang('google.form.error')); 
                        }else{
                            this._$emit('alert',this.$lang('google.form.success')) ;
                            setTimeout(()=>{this.findattachefile()}, 1000 );
                        }
                    
                    })  

                    .catch((err)=>{
                        
                        this.loader = false ;  
                        this._$emit('alertError',this.$lang('form.error'));  

                    })
        	}

        },

        computed : {
        	 
        },
		
		created(){ 
			
			this.findattachefile()  ; 

		}

	}

</script>

<style>
	
	.profile-form{

		position: relative;
		top: 50%;
		transform: translateY( -50% );
	
	}

	.profile-form-btn{
	
		width: 100%;
		max-width: 100px;
		
	}

</style>