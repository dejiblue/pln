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
					<h4 class="title-section">{{$lang('modale.profile.form.api.title')}}</h4>
				</b-col>
				<b-col md="6" sm="12">
					<b-form-group>
		                <b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12">
		                            <b-form-input id="internetbskey"
		                                v-model="form.internetbskey"
		                                :state="error.internetbskey"
		                                @input="changeinput('internetbskey')"
		                                :placeholder="$lang('modale.profile.form.InternetbskeyPlace')">
		                            </b-form-input>
		                            <b-form-invalid-feedback >
		                                <span v-if="!txt.internetbskey">
		                                    {{$lang('modale.profile.form.InternetbskeyPlace')}}
		                                </span>
		                                <span v-if="txt.internetbskey">
		                                    {{txt.internetbskey}}
		                                </span>
		                            </b-form-invalid-feedback>
		                        </b-col>
		                    </b-row>
		                </b-input-group>
		            </b-form-group>
				</b-col>

				<b-col md="6" sm="12">
					<b-form-group>
		                <b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12" class="loader-input variante" style="position: relative;">
		                            <b-form-input id="internetbspass"
		                            	class="select-loader"
		                                v-model="form.internetbspass"
		                                :state="error.internetbspass"
		                                type="password"
		                                @input="changeinput('internetbspass')"
		                                :placeholder="$lang('modale.profile.form.InternetbspassPlace')">
		                            </b-form-input>
		                            <b-form-invalid-feedback id="ErreurCppassword">
		                                <span v-if="!txt.internetbspass">
		                                    {{$lang('modale.profile.form.InternetbspassError')}}
		                                </span>
		                                <span v-if="txt.internetbspass">
		                                    {{txt.internetbspass}}
		                                </span>
		                            </b-form-invalid-feedback>
		                        </b-col>
		                    </b-row>
		                </b-input-group>
		            </b-form-group>
				</b-col>

				<b-col md="6" sm="12">
					<b-form-group>

		               	<b-form-checkbox-group v-model="form.internetbs" :options="[{text: $lang('modale.profile.form.Internetbsactive') , value: 'internetbs'}]" />

		            </b-form-group>
				</b-col>

	    		<b-col sm="12">
                    <b-button @click="$emit('cancel')" variant="secondary">{{$lang('profile.update.btn.cancel')}}</b-button>
					<b-button @click="submite" style="flex: left;" variant="primary">{{$lang('profile.update.btn')}}</b-button>
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
                    internetbskey : null , 
					internetbspass : null , 
                },


                form: {
                    internetbskey : '' , 
					internetbspass : '' , 
					internetbs : [] , 
                },

                txt: {
                    internetbskey : '' , 
					internetbspass : '' , 
					internetbs : [] , 
                },
            }
    
        },

        methods : {

            submite : function () {
            	
                let url = window.urlapp+'/profil/update' ; 

                var formData = new FormData();

    			if ( this.form.cppassword && this.form.passJauge < 82 ) {
    				return false ;
    			}

    			formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');

                formData.append("internetbskey", this.form.internetbskey);
                formData.append("internetbspass", this.form.internetbspass);
                formData.append("internetbs", this.form.internetbs.includes('internetbs')?1:0 );

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

            this.form.internetbskey = this.user.internetbskey; 
            this.form.internetbspass = this.user.internetbspass; 
            this.form.internetbs = this.user.internetbs == 1 || this.user.internetbs == '1' ? ['internetbs'] : [] ; 
            this.form.cpemail = this.user.cpemail; 
			
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