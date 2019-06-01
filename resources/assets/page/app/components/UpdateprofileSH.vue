<template>

	<div style="margin-bottom: 4rem">
		
		<b-row  v-if="info" style="margin-top: 2rem; margin-bottom: -2rem; ">
            <b-col>
                <b-alert :variant="info.type" show>{{info.txt}}</b-alert>
            </b-col>
        </b-row>  

		<div class="header-profile">

			<b-row>
	            <b-col>
	                <b-progress v-if="loader" :value="100" variant="info" striped animated class="mb-2"></b-progress>
	            </b-col>
	        </b-row> 

			<b-row>
				<b-col sm="12">
					<h4 class="title-section">{{$lang('modale.profile.form.sh.title')}}</h4>
				</b-col>
				<b-col md="6" sm="12">
					<b-form-group>
		                <b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12">
		                            <b-form-input id="scriptfile"
		                                v-model="form.scriptfile"
		                                :state="error.scriptfile"
		                                :placeholder="$lang('modale.profile.form.sh.name')">
		                            </b-form-input>
		                        </b-col>
		                    </b-row>
		                </b-input-group>
		            </b-form-group>
				</b-col>

				<b-col md="6" sm="12">
					<b-form-group>
		               	<b-form-checkbox-group :disabled="!form.scriptfile" v-model="form.script" :options="[{text: $lang('modale.profile.form.sh.run') , value: 'script'}]" />
		            </b-form-group>
				</b-col>

	    		<b-col sm="12">
                    <b-button @click="$emit('cancel')" variant="secondary">{{$lang('profile.update.btn.cancel')}}</b-button>
					<b-button @click="submite"  style="flex: left;" variant="primary">{{$lang('profile.update.btn')}}</b-button>
				</b-col>

	    	</b-row>
		</div>
	</div>


</template>

<script>
	
	import { get, post } from '../lib/api'

	export default {

		props : ['SubmitSet','data'], 

		data(){

            return {

            	user : {} , 
            	
                loader : null ,

                info : null , 

            	error : {
                    scriptfile : '' ,  
                },


                form: {
                    scriptfile : '' ,  
                    files : null , 
					script : [] , 
                },

                txt: {
                    scriptfile : '' ,  
					script : [] , 
                },
            }
    
        },

        methods : {

        	submite(){

                let url = window.urlapp+'/profil/update' ; 

                var formData = new FormData();
  
                formData.append("scriptfile", this.form.scriptfile ); 
                formData.append("script", this.form.script.includes('script')?1:0 );

    			formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');

                formData.append("internetbskey", this.user.internetbskey);
                formData.append("internetbspass", this.user.internetbspass);
                formData.append("internetbs", this.user.internetbs );

                formData.append("name", this.user.name);
                formData.append("forname", this.user.forname);
                formData.append("email", this.user.email);

                formData.append("wppassword", this.user.wppassword);
                formData.append("wpusername", this.user.wpusername);
                formData.append("cpemail", this.user.cpemail);

                this.loader = true ; 

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{

                        this.loader = false ; 
                        this.info = null ; 
                        
                        if ( res.data.error ) {

                            let keys = Object.keys( res.data.error ) ; 

                            for (var i = 0; i < keys.length; i++) {
                                this.error[keys[i]]=false ; 
                                this.txt[keys[i]]=res.data.error[keys[i]][0] ; 
                            }
                        
                        }else{

                            this.$emit('successupdate') ;  

                            setTimeout(()=>{
                                location.reload();
                            }, 3000);

                        }
                    
                    })  

                    .catch((err)=>{
                        
                        this.loader = false ; 

                        this.info = Object.assign({} , { type : 'danger' ,  txt : this.$lang('form.error') }) ;

                    })

        	},

            file_change : function ( e ) {
            	
            	if (  e.target.files.length ) {
            		this.form.files = e.target.files[0] ;  
            		this.form.scriptfile = this.form.files.name ;  
            	}

            },

            changeinput : function ( champ ) {
                
                if ( this.form[champ] ) {
                    this.error[champ]=null ; 
                }

            },

        },

        computed : {
        	 
        },

        watch: {

	      	SubmitSet : function() {
	         	
	         	if ( this.SubmitSet ) {
	         		this.submite()
	         	}

	      	}

	   	},
		
		created(){ 

            this.user = Object.assign({} , document.user ) ;  

            this.form.scriptfile = this.user.scriptfile; 

            this.form.script = this.user.script?this.form.script=['script']:this.form.script=[]; 
		
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

	.title-section{
	
		color: #3ec1d3 ; 
		margin-bottom: 1rem;

	}


</style>