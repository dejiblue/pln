<template>

	<div>

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
	    		
				<!--
				<b-col xl="3" lg="3" md="4" sm="12">
	    			<Avatar :src="user.avatar" @reset="error.avatar=null;txt.avatar='';" :error="error.avatar" :txterror="txt.avatar" :unchange="false" @avatar="avatar_change" ></Avatar>
	    		</b-col>
				-->

	    		<b-col sm="12">
	    			<div class="profile-form">
	                    
	                    <b-form @submit.prevent.stop="''">

		    				<b-row>

			    				<b-col md="6" sm="12">
			    					<b-form-group>
			                            <b-form-input id="name"
			                                    v-model="form.name"
			                                    :state="error.name"
			                                    @input="changeinput('name')"
			                                    :placeholder="$lang('profile.update.form.name')">
			                            </b-form-input>
			                            <b-form-invalid-feedback id="ErreurName">
		                                    <span vi-if="!txt.name">
		                                        {{$lang('profile.update.form.nameError')}}
		                                    </span>
		                                    <span vi-if="txt.name">
		                                        {{txt.name}}
		                                    </span>
		                                </b-form-invalid-feedback>
			                        </b-form-group>
			    				</b-col>

			    				<b-col md="6" sm="12">	
			    					<b-form-group>
			                            <b-form-input id="forname"
			                                    v-model="form.forname"
			                                    :state="error.forname"
			                                    @input="changeinput('forname')"
			                                    :placeholder="$lang('profile.update.form.forname')">
			                            </b-form-input>
			                            <b-form-invalid-feedback id="ErreurForname">
			                                <span vi-if="!txt.forname">
		                                        {{$lang('profile.update.form.fornameError')}}
		                                    </span>
		                                    <span vi-if="txt.forname">
		                                        {{txt.forname}}
		                                    </span>
			                            </b-form-invalid-feedback>
			                        </b-form-group>
			    				</b-col>
							
							</b-row>

							<b-row>

			    				<b-col md="6" sm="12">
			    					<b-form-group>
			                            <b-form-input id="email"
			                                    v-model="form.email"
			                                    :state="error.email"
			                                    @input="changeinput('email')"
			                                    :placeholder="$lang('profile.update.form.email')">
			                            </b-form-input>
			                            <b-form-invalid-feedback id="ErreurEmail">
			                                <span vi-if="!txt.email">
		                                        {{$lang('profile.update.form.emailError')}}
		                                    </span>
		                                    <span vi-if="txt.email">
		                                        {{txt.email}}
		                                    </span>
			                            </b-form-invalid-feedback>
			                        </b-form-group>
			    				</b-col>

			    				<b-col md="6" sm="12">

									<b-row>

					    				<b-col sm="12">
					    					<b-form-group>
					                            <b-form-input id="password"
					                                    v-model="form.password"
					                                    :state="error.password"
					                                    type="password"
					                                    @input="changeinput('password')"
					                                    :placeholder="$lang('modale.profile.form.passwordPlace')">
					                            </b-form-input>
					                            <b-form-invalid-feedback id="ErreurPassword">
					                                <span vi-if="!txt.password">
				                                        {{$lang('modale.profile.form.passwordError')}}
				                                    </span>
				                                    <span vi-if="txt.password">
				                                        {{txt.password}}
				                                    </span>
					                            </b-form-invalid-feedback>
					                        </b-form-group>
					    				</b-col>

					    				<b-col v-show="form.password" sm="12">
					    					<b-form-group>
					                            <b-form-input id="password_confirmation"
					                            		type="password"
					                                    v-model="form.password_confirmation"
					                                    :state="error.password_confirmation"
					                                    @input="changeinput('password_confirmation')"
					                                    :placeholder="$lang('modale.profile.form.password_confirmation')">
					                            </b-form-input>
					                            <b-form-invalid-feedback id="Erreurpassword_confirmation">
					                                <span vi-if="!txt.password_confirmation">
				                                        {{$lang('modale.profile.form.password_confirmationError')}}
				                                    </span>
				                                    <span vi-if="txt.password_confirmation">
				                                        {{txt.password_confirmation}}
				                                    </span>
					                            </b-form-invalid-feedback>
					                        </b-form-group>
					    				</b-col>
							           	
							           	<b-col v-show="form.password" sm="12">
					    					<b-form-group>
					                            <b-form-input id="oldpassword"
					                            		type="password"
					                                    v-model="form.oldpassword"
					                                    :state="error.oldpassword"
					                                    @input="changeinput('oldpassword')"
					                                    :placeholder="$lang('modale.profile.form.oldpassword')">
					                            </b-form-input>
					                            <b-form-invalid-feedback id="ErreurOldpassword">
					                                <span vi-if="!txt.oldpassword">
				                                        {{$lang('modale.profile.form.oldpasswordError')}}
				                                    </span>
				                                    <span vi-if="txt.oldpassword">
				                                        {{txt.oldpassword}}
				                                    </span>
					                            </b-form-invalid-feedback>
					                        </b-form-group>
					    				</b-col>

									</b-row>

								</b-col>
					           
							</b-row>

						</b-form>

						<b-row>

		    				<b-col md="6" sm="12">
			                    <b-button @click="$emit('cancel')" variant="secondary">{{$lang('profile.update.btn.cancel')}}</b-button>
		    					<b-button @click="onSubmit" style="flex: left;" variant="primary">{{$lang('profile.update.btn')}}</b-button>
		    				</b-col>
						
						</b-row>
	    			</div>
	    		</b-col>

	    	</b-row>
		</div>
	</div>


</template>

<script>
	

    import { get, post } from '../lib/api'

	export default {

		props : [], 

		data(){

            return {

            	user : {},
            	
            	error : {
                    name : null,
                    forname : null,
                    email : null,
                },

                form: {
                    name : '',
                    forname : '',
                    email : '',
                },

                txt: {
                    name : '',
                    forname : '',
                    email : '',
                },

                info : null , 

                loader : false , 

            }
    
        },

        methods : {

        	hideInfo : function () {
                
                setTimeout(()=>{

                    this.info = null ; 

                }, 3000);

            },


        	avatar_change : function ( e ) {
                
                this.form.avatar = e ; 

            },

            changeinput : function ( champ ) {
                
                if ( this.form[champ] ) {
                    this.error[champ]=null ; 
                }

            },

        	onSubmit : function () {


                let url = window.urlapp+'/profil/update' ; 

                var formData = new FormData();

                formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');

                formData.append("name", this.form.name);
                formData.append("forname", this.form.forname);
                formData.append("email", this.form.email); 

                if ( this.form.oldpassword ) {
                    formData.append("oldpassword", this.form.oldpassword);
                }

                if ( this.form.password ) {
                    formData.append("password", this.form.password);
                }

                if ( this.form.password_confirmation ) {
                    formData.append("password_confirmation", this.form.password_confirmation);
                }

                if ( this.form.avatar ) {
                    formData.append("avatar", this.form.avatar);
                }

                formData.append("wppassword", this.user.wppassword);
                formData.append("wpusername", this.user.wpusername);
                formData.append("cppassword", this.user.cppassword);
                formData.append("cpemail", this.user.cpemail);

                this.loader = true ; 

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{

                    	console.log( res );

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

                        this.hideInfo() ; 

                    })

            }

        },

        computed : {

        	 
        },
		
		created(){ 
			
			this.user = Object.assign({} , document.user ) ;  

			this.form.name = this.user.name ;
			this.form.forname = this.user.forname ;
			this.form.email = this.user.email ; 

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