<template>
	<div class="ca-editor">

        <b-row>
            <b-col>
                <b-progress v-if="loader" :value="100" variant="info" striped animated class="mb-2"></b-progress>
            </b-col>
        </b-row> 

		<b-form class="form-login" @submit.prevent.stop="onSubmit">

            <b-form-group>
                <b-input-group>
                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="wppassword">{{$lang('modale.profile.form.wppassword')}}</label></b-col>
                        <b-col sm="8">
                            <b-form-input id="wppassword"
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

            <b-form-group>
                <b-input-group>
                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="wpusername">{{$lang('modale.profile.form.wpusername')}}</label></b-col>
                        <b-col sm="8">
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

            <b-form-group>
                <b-input-group>
                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="cpemail">{{$lang('modale.profile.form.cpemail')}}</label></b-col>
                        <b-col sm="8">
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

            <b-form-group>
                <b-input-group>
                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="cppassword">{{$lang('modale.profile.form.cppassword')}}</label></b-col>
                        <b-col sm="8">
                            <b-form-input id="cppassword"
                                v-model="form.cppassword"
                                :state="error.cppassword"
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

        </b-form>
	</div>
</template>

<script>

    import { get, post } from '../lib/api'

	export default {

		props : ['SubmitSet','data'], 

		data(){

            return {
            	
                loader : null ,

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

                formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');

                if ( this.form.oldpassword ) {
                    formData.append("oldpassword", this.form.oldpassword);
                }

                if ( this.form.password ) {
                    formData.append("password", this.form.password);
                }

                if ( this.form.password_confirmation ) {
                    formData.append("password_confirmation", this.form.password_confirmation);
                }

                if ( this.form.wppassword ) {
                    formData.append("wppassword", this.form.wppassword);
                }

                if ( this.form.wpusername ) {
                    formData.append("wpusername", this.form.wpusername);
                }

                if ( this.form.cppassword ) {
                    formData.append("cppassword", this.form.cppassword);
                }

                if ( this.form.cpemail ) {
                    formData.append("cpemail", this.form.cpemail);
                }

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

                            this.$emit('alert',this.$lang('profile.update.alert')) ;  

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

            this.form.oldpassword = this.user.oldpassword; 
            this.form.password = this.user.password; 
            this.form.password_confirmation = this.user.password_confirmation; 
            this.form.wppassword = this.user.wppassword; 
            this.form.wpusername = this.user.wpusername; 
            this.form.cppassword = this.user.cppassword; 
			
		}

	}

</script>

<style>
	

	.ca-editor .dropdown-menu.show {
	    
	    display: flex;
	    max-width: 200px;
	    flex-flow: row wrap;

	}
	
	.ca-editor .dropdown-item {
	    
	    display: block;
	    max-width: 60px ; 

	}

</style>