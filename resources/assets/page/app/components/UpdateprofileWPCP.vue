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
					<h4 class="title-section">{{$lang('modale.profile.form.cpwp.title')}}</h4>
				</b-col>
				<b-col md="6" sm="12">
					<b-form-group>
		                <b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12">
		                            <b-form-input id="wpusername"
		                                v-model="form.wpusername"
		                                :state="error.wpusername"
		                                @input="changeinput('wpusername')"
		                                :placeholder="$lang('modale.profile.form.wpusernamePlace')">
		                            </b-form-input>
		                            <b-form-invalid-feedback id="ErreurWpusername">
		                                <span v-if="!txt.wpusername">
		                                    {{$lang('modale.profile.form.wpusernameError')}}
		                                </span>
		                                <span v-if="txt.wpusername">
		                                    {{txt.wpusername}}
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
		                        	<span v-if="chechpasword" class="input-loader" >
		                                <loader style="z-index: 1000;"></loader>
		                            </span>
		                            <b-form-input id="cppassword"
		                            	class="select-loader"
		                                v-model="form.cppassword"
		                                :state="error.cppassword"
		                                type="password"
		                                @input="changeinput('cppassword')"
		                                :placeholder="$lang('modale.profile.form.cppasswordPlace')">
		                            </b-form-input>
		                            <b-form-invalid-feedback id="ErreurCppassword">
		                                <span v-if="!txt.cppassword">
		                                    {{$lang('modale.profile.form.cppasswordError')}}
		                                </span>
		                                <span v-if="txt.cppassword">
		                                    {{txt.cppassword}}
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
		                        <b-col sm="12">
		                            <b-form-input id="wppassword"
		                            	type="password"
		                                v-model="form.wppassword"
		                                :state="error.wppassword"
		                                @input="changeinput('wppassword')"
		                                :placeholder="$lang('modale.profile.form.wppasswordPlace')">
		                            </b-form-input>
		                            <b-form-invalid-feedback id="ErreurWppassword">
		                                <span v-if="!txt.wppassword">
		                                    {{$lang('modale.profile.form.wppasswordError')}}
		                                </span>
		                                <span v-if="txt.wppassword">
		                                    {{txt.wppassword}}
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
		                        <b-col sm="12">
		                            <b-form-input id="cpemail"
		                                v-model="form.cpemail"
		                                :state="error.cpemail"
		                                @input="changeinput('cpemail')"
		                                :placeholder="$lang('modale.profile.form.cpemailPlace')">
		                            </b-form-input>
		                            <b-form-invalid-feedback id="ErreurCpemail">
		                                <span v-if="!txt.cpemail">
		                                    {{$lang('modale.profile.form.cpemailError')}}
		                                </span>
		                                <span v-if="txt.cpemail">
		                                    {{txt.cpemail}}
		                                </span>
		                            </b-form-invalid-feedback>
		                        </b-col>
		                    </b-row>
		                </b-input-group>
		            </b-form-group>
				</b-col>

	    		<b-col sm="12">
                    <b-button @click="$emit('cancel')" variant="secondary">{{$lang('profile.update.btn.cancel')}}</b-button>
					<b-button :disabled="chechpasword||(form.cppassword&&form.passJauge<82)" @click="submite" style="flex: left;" variant="primary">{{$lang('profile.update.btn')}}</b-button>
				</b-col>

	    	</b-row>
		</div>
	</div>


</template>

<script>
	
	import { whm_pass } from '../lib/whm';
    import { get, post } from '../lib/api'

	export default {

		props : ['SubmitSet','data'], 

		data(){

            return {

            	user : {} , 
            	
                loader : null ,

                info : null , 

                chechpasword : false , 

                interval_password_change : null , 

            	error : {
                    password : null,
                    oldpassword : null,
                    password_confirmation : null,
                    wppassword : null,
                    wpusername : null,
                    cppassword : null,
                },


                form: {
                    oldpassword : '',
                    password : '',
                    password_confirmation : '',
                    wppassword : '',
                    wpusername : '',
                    cppassword : '',
                    passJauge : 100 ,
                },

                txt: {
                    oldpassword : '',
                    password : '',
                    password_confirmation : '',
                    wppassword : '',
                    wpusername : '',
                    cppassword : '',
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

    			formData.append("cppassword", this.form.cppassword);

                formData.append("token", document.csrf_token);

                formData.append("_methode", 'POST');

                formData.append("wppassword", this.form.wppassword);

                formData.append("wpusername", this.form.wpusername);
        
                formData.append("cpemail", this.form.cpemail);
          		
                formData.append("name", this.user.name);
                formData.append("forname", this.user.forname);
                formData.append("email", this.user.email);

                formData.append("internetbskey", this.user.internetbskey);
                formData.append("internetbspass", this.user.internetbspass);
                formData.append("internetbs", this.user.internetbs );

				   
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

            password_change_chech(){

            	this.chechpasword = true ;

        		whm_pass( this.form.cppassword , ( e ) => {

        			this.chechpasword = false ; 
        			this.form.passJauge = e ;  
        			if ( this.form.passJauge <= 82 ) {
        				this.error.cppassword = false;
        				this.txt.cppassword = this.$lang('installsite.form.passJauge') ; 
        			}else{
        				this.error.cppassword = null;
        				this.txt.cppassword = '' ; 
        			}

        		})

            },

            changeinput : function ( champ ) {
                
                if ( champ == 'cppassword' ) {

                	clearInterval( this.interval_password_change ) ; 

	        		this.error.cppassword = null ; 

	        		this.form.passJauge = -1 ; 

	        		this.interval_password_change = setTimeout(()=>{
	        			this.password_change_chech( this.form.cppassword ) ; 
	        		}, 800);

                }
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

            this.form.wppassword = this.user.wppassword; 
            this.form.wpusername = this.user.wpusername; 
            this.form.cppassword = this.user.cppassword; 
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