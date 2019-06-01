<template>
	
	<div class="install padding-start">

		<div v-if="loader" style="position: absolute;top: 0;left: 0;right: 0;bottom: 0; z-index: 3000; background: rgba(62, 193, 211, 0.8);">
			<span class="loader-txt" v-if="titleloader.title" style="position: absolute; text-align: center; color: #FFF; top: 37%; left: 50%; transform: translate( -50% , -50%); font-size: 1.6rem;"><span>{{titleloader.title}}</span><em v-if="titleloader.strong" style="font-size: 1.1rem; color: #144b52; font-style: initial;"> ({{titleloader.strong}})</em></span>
			<loader style="z-index: 1000;" :placeholder="percentyloader"></loader>
			<span class="loader-txt" v-if="steeploader" style="position: absolute; text-align: center; color: #FFF; top: 60%; left: 50%; transform: translate( -50% , -50%); font-size: 1.6rem;">{{steeploader}}</span>
		</div>
		
		<b-container v-if="warning">
			<b-alert v-html="this.formathtml( $lang('install.warning') ) " show variant="warning"></b-alert>
		</b-container>

		<b-container v-if="!warning">

			<b-row>
				<b-col cols="8">

					<b-form-group>
						<b-row>
							<b-col sm="12">
								<h3>{{$lang('installsite.cpanel.title')}}</h3>
							</b-col>
						</b-row>
					</b-form-group>

                    <!---------------------------------------------------------->

                    <b-form-group>
		                <b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12"><label for="domaine">{{$lang('installsite.form.domaine')}}</label></b-col>
		                        <b-col sm="12">
		                            <b-form-textarea id="domaine"
		                                v-model="form.domaine"
		                                :state="error.domaine"
		                                @input="changeinput('domaine')"
		                                rows="3"
                     					max-rows="6"
		                                :placeholder="$lang('installsite.form.domainePlace')">
			                        </b-form-textarea>
			                        <b-form-invalid-feedback id="ErreurForname">
			                            <span v-if="!txt.domaine">
			                                {{$lang('installsite.form.domaineError')}}
			                            </span>
			                            <span v-if="txt.domaine">
			                                {{txt.domaine}}
			                            </span>
			                        </b-form-invalid-feedback>
		                        </b-col>
		                    </b-row>
		                </b-input-group>
		            </b-form-group>

		            <b-form-group>
		                <b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12"><label for="domaineIP">{{$lang('installsite.form.domaineIP')}}</label></b-col>
		                        <b-col sm="12">
		                            <b-form-textarea id="domaineIP"
		                                v-model="form.domaineIP"
		                                :state="error.domaineIP"
		                                rows="3"
                     					max-rows="6"
		                                @input="changeinput('domaineIP')"
		                                :placeholder="$lang('installsite.form.domaineIPPlace')">
			                        </b-form-textarea>
			                        <b-form-invalid-feedback id="ErreurForname">
			                            <span v-if="!txt.domaineIP">
			                                {{$lang('installsite.form.domaineIPError')}}
			                            </span>
			                            <span v-if="txt.domaineIP">
			                                {{txt.domaineIP}}
			                            </span>
			                        </b-form-invalid-feedback>
		                        </b-col>
		                    </b-row>
		                </b-input-group>
		            </b-form-group>

		            <b-form-group>
						<b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12"><label for="categorie">{{$lang('installsite.form.categorie')}}</label></b-col>
		                        <b-col sm="12">
			                        <b-form-select name="categorie" v-model="form.categorie" :options="categories" class="mb-3" />
		                        </b-col>
		                    </b-row>
		                </b-input-group>
					</b-form-group>

					<div class="pln-manager-fond">
						<b-form-group>
							<b-input-group>
			                    <b-row style="width: 100%;">
			                        <b-col sm="12"><h3 @click="cpanelOption?cpanelOption=false:cpanelOption=true" style="cursor: pointer !important;"  for="categorie">{{$lang('installsite.form.cpanelOption')}}</h3></b-col>
			                    </b-row>
			                </b-input-group>
						</b-form-group>
						
						<div v-if="cpanelOption">
							
							<b-form-group>
								<b-input-group>
				                    <b-row style="width: 100%;">
				                        <b-col sm="12"><label for="package">{{$lang('installsite.form.packageLabel')}}</label></b-col>
				                        <b-col sm="12" class="loader-input variante" style="position: relative;">
				                        	<span v-if="loaderInfo" class="input-loader" >
				                                <loader style="z-index: 1000;"></loader>
				                            </span>
					                        <b-form-select name="package" v-model="form.package" :options="packageliste" class="mb-3" />
				                        </b-col>
				                    </b-row>
				                </b-input-group>
							</b-form-group>
							
							<b-form-group>
								<b-input-group>
				                    <b-row style="width: 100%;">
				                        <b-col sm="12"><label for="serveur">{{$lang('installsite.form.serveur')}}</label></b-col>
				                        <b-col sm="12">
					                        <b-form-select @input="change_serveur( )" name="serveur" v-model="form.serveur" :options="serveurs" class="mb-3" />
				                        </b-col>
				                    </b-row>
				                </b-input-group>
							</b-form-group>

							<b-form-group>
				                <b-input-group>
				                    <b-row style="width: 100%;">
				                        <b-col sm="12"><label for="password">{{$lang('installsite.form.password')}}</label></b-col>
				                        <b-col sm="12">
				                            <b-form-input id="password"
				                                v-model="form.password"
				                                :state="error.password"
				                                rows="3"
				                                type="password"
		                     					max-rows="6"
				                                @input="changeinput('password')"
				                                :placeholder="$lang('installsite.form.passwordPlace')">
					                        </b-form-input>
					                        <b-form-invalid-feedback id="ErreurForname">
					                            <span v-if="!txt.password">
					                                {{$lang('installsite.form.passwordError')}}
					                            </span>
					                            <span v-if="txt.password">
					                                {{txt.password}}
					                            </span>
					                        </b-form-invalid-feedback>
				                        </b-col>
				                    </b-row>
				                </b-input-group>
				            </b-form-group>

				            <b-form-group>
				                <b-input-group>
				                    <b-row style="width: 100%;">
				                        <b-col sm="12"><label for="passconf">{{$lang('installsite.form.passconf')}}</label></b-col>
				                        <b-col sm="12">
				                            <b-form-input id="passconf"
				                                v-model="form.passconf"
				                                :state="error.passconf"
				                                type="password"
				                                rows="3"
		                     					max-rows="6"
				                                @input="changeinput('passconf')"
				                                :placeholder="$lang('installsite.form.passconfPlace')">
					                        </b-form-input>
					                        <b-form-invalid-feedback id="ErreurForname">
					                            <span v-if="!txt.passconf">
					                                {{$lang('installsite.form.passconfError')}}
					                            </span>
					                            <span v-if="txt.passconf">
					                                {{txt.passconf}}
					                            </span>
					                        </b-form-invalid-feedback>
				                        </b-col>
				                    </b-row>
				                </b-input-group>
				            </b-form-group>

				            <b-form-group>
				                <b-input-group>
				                    <b-row style="width: 100%;">
				                        <b-col sm="8">
											<passJauge :passJauge="form.passJauge" :waitpassJauge="waitpassJauge" ></passJauge>
										</b-col>
										<b-col sm="4">
								  			<b-button style="float: right;" id="pass-generate" >Password generator</b-button>
											<passGenerat :nativegenerate="false" :target="'pass-generate'" @pass="password_change_in_generator"></passGenerat>
											
										</b-col>
				                    </b-row>
				                </b-input-group>
				            </b-form-group>
							
							<b-form-group>
				                <b-input-group>
				                    <b-row style="width: 100%;">
				                        <b-col sm="12"><label for="contactemail">{{$lang('installsite.form.contactemail')}}</label></b-col>
				                        <b-col sm="12">
				                            <b-form-input id="contactemail"
				                                v-model="form.contactemail"
				                                :state="error.contactemail"
				                                rows="3"
		                     					max-rows="6"
				                                @input="changeinput('contactemail')"
				                                :placeholder="$lang('installsite.form.contactemailPlace')">
					                        </b-form-input>
					                        <b-form-invalid-feedback id="ErreurContactemail">
					                            <span v-if="!txt.contactemail">
					                                {{$lang('installsite.form.contactemailError')}}
					                            </span>
					                            <span v-if="txt.contactemail">
					                                {{txt.contactemail}}
					                            </span>
					                        </b-form-invalid-feedback>
				                        </b-col>
				                    </b-row>
				                </b-input-group>
				            </b-form-group>

						</div>
					</div>
					
					<div class="pln-manager-fond">
						<b-form-group>
							<b-input-group>
			                    <b-row style="width: 100%;">
			                        <b-col sm="12"><h3 @click="wordpressOption?wordpressOption=false:wordpressOption=true" style="cursor: pointer !important;" for="categorie">{{$lang('installsite.form.wordpressOption')}}</h3></b-col>
			                    </b-row>
			                </b-input-group>
						</b-form-group>
						
						<div v-if="wordpressOption">
				            <b-form-group>
						      	<b-form-checkbox-group name="mode-ip" v-model="form.wpoptionval" :options="[{text: 'WordPress install', value: 'wordpress'}]" />
						    </b-form-group>

						    <div v-if="form.wpoptionval.indexOf('wordpress')!==-1">
						    	
								<b-row>
									<b-col sm="12" style="margin-top: 20px;margin-bottom: 20px;">
										<h2>{{$lang('installsite.wp.title')}}</h2>
									</b-col>
								</b-row>

							    <b-form-group class="checkboxwordpress">
							      	<b-form-checkbox-group name="mode-ip" v-model="form.wpoptionval" :options="wpoption" />
							    </b-form-group>

							    <b-form-group>
									<b-input-group>
					                    <b-row style="width: 100%;">
					                        <b-col sm="12"><label for="wplang">{{$lang('installsite.form.wplang')}}</label></b-col>
					                        <b-col sm="12" class="loader-input variante" style="position: relative;">
						                        <b-form-select name="wplang" v-model="form.wplang" :options="wplangliste" class="mb-3" />
					                        </b-col>
					                    </b-row>
					                </b-input-group>
								</b-form-group>
								

								<b-form-group>
					                <b-input-group>
					                    <b-row style="width: 100%;">
					                        <b-col sm="12"><label for="wp_pass">{{$lang('installsite.form.wp_pass')}}</label></b-col>
					                        <b-col sm="12">
					                            <b-form-input id="wp_pass"
					                                v-model="form.wp_pass"
					                                :state="error.wp_pass"
					                                rows="3"
			                     					max-rows="6"
					                                @input="changeinput('wp_pass')"
					                                :placeholder="$lang('installsite.form.wp_passPlace')">
						                        </b-form-input>
						                        <b-form-invalid-feedback id="ErreurWp_pass">
						                            <span v-if="!txt.wp_pass">
						                                {{$lang('installsite.form.wp_passError')}}
						                            </span>
						                            <span v-if="txt.wp_pass">
						                                {{txt.wp_pass}}
						                            </span>
						                        </b-form-invalid-feedback>
					                        </b-col>
					                    </b-row>
					                </b-input-group>
					            </b-form-group>


					            <b-form-group>
					                <b-input-group>
					                    <b-row style="width: 100%;">
					                        <b-col sm="12"><label for="wp_username">{{$lang('installsite.form.wp_username')}}</label></b-col>
					                        <b-col sm="12">
					                            <b-form-input id="wp_username"
					                                v-model="form.wp_username"
					                                :state="error.wp_username"
					                                rows="3"
			                     					max-rows="6"
					                                @input="changeinput('wp_username')"
					                                :placeholder="$lang('installsite.form.wp_usernamePlace')">
						                        </b-form-input>
						                        <b-form-invalid-feedback id="ErreurWp_username">
						                            <span v-if="!txt.wp_username">
						                                {{$lang('installsite.form.wp_usernameError')}}
						                            </span>
						                            <span v-if="txt.wp_username">
						                                {{txt.wp_username}}
						                            </span>
						                        </b-form-invalid-feedback>
					                        </b-col>
					                    </b-row>
					                </b-input-group>
					            </b-form-group>


					            <b-form-group>
					                <b-input-group>
					                    <b-row style="width: 100%;">
					                        <b-col sm="12"><label for="theme_name">{{$lang('installsite.form.theme_name')}}</label></b-col>
					                        <b-col sm="12">
					                            <b-form-input id="theme_name"
					                                v-model="form.theme_name"
					                                :state="error.theme_name"
					                                rows="3"
			                     					max-rows="6"
					                                @input="changeinput('theme_name')"
					                                :placeholder="$lang('installsite.form.theme_namePlace')">
						                        </b-form-input>
						                        <b-form-invalid-feedback id="ErreurTheme_name">
						                            <span v-if="!txt.theme_name">
						                                {{$lang('installsite.form.theme_nameError')}}
						                            </span>
						                            <span v-if="txt.theme_name">
						                                {{txt.theme_name}}
						                            </span>
						                        </b-form-invalid-feedback>
					                        </b-col>
					                    </b-row>
					                </b-input-group>
					            </b-form-group>


					            <b-form-group>
					                <b-input-group>
					                    <b-row style="width: 100%;">
					                        <b-col sm="12"><label for="theme_file">{{$lang('installsite.form.theme_file')}}</label></b-col>
					                        <b-col sm="12">
						                        <inputFile :error="false===error.theme_file" @file="theme_file_change" :placeholder="$lang('installsite.form.theme_filePlace')"></inputFile>
						                        <div v-show="false===error.theme_file" style="color: red; width: 100%;" >
					                                Error file(s)
					                            </div>
					                        </b-col>
					                    </b-row>
					                </b-input-group>
					            </b-form-group>


					            <b-form-group>
							      	<b-form-checkbox-group name="mode-ip" v-model="form.wpthemeoptionval" :options="[{text: 'Active theme', value: 'activate_theme'}]" />
							    </b-form-group>

								
								<b-form-group>
					                <b-input-group>
					                    <b-row style="width: 100%;">
					                        <b-col sm="12"><label for="plugin_file">{{$lang('installsite.form.plugin_file')}}</label></b-col>
					                        <b-col sm="12">
						                        <inputFile :error="false===error.plugin_file" @file="plugin_file_change" :placeholder="$lang('installsite.form.plugin_filePlace')"></inputFile>
						                        <div v-show="false===error.plugin_file" style="color: red; width: 100%;" >
					                                Error file(s)
					                            </div>
					                        </b-col>
					                    </b-row>
					                </b-input-group>
					            </b-form-group>

								
								<b-form-group>
							      	<b-form-checkbox-group name="mode-ip" v-model="form.wppluginoptionval" :options="[{text: 'Enable plugins', value: 'upluginactive'}]" />
							    </b-form-group>


							    <b-form-group>
					                <b-input-group>
					                    <b-row style="width: 100%;">
					                        <b-col sm="12"><label for="upload_file">{{$lang('installsite.form.upload_file')}}</label></b-col>
					                        <b-col sm="12">
						                        <inputFile :error="false===error.upload_file" @file="upload_file_change" :placeholder="$lang('installsite.form.upload_filePlace')"></inputFile>
						                        <div v-show="false===error.upload_file" style="color: red; width: 100%;" >
					                                Error file(s)
					                            </div>
					                        </b-col>
					                    </b-row>
					                </b-input-group>
					            </b-form-group>
						    </div>
						</div>
                    </div>
                    <!---------------------------------------------------------->

					<b-form-group>
						<b-row>
							<b-col style="padding-top: 20px;padding-bottom: 20px;">
								<b-button :variant="'primary'" @click="submite" >Have fun !</b-button>
							</b-col>
						</b-row>
					</b-form-group>

				</b-col>

				<b-col cols="4">
		  			<b-form-group>
				      	<b-form-checkbox-group name="mode-ip" v-model="form.ipvaluse" :options="ipoption" />
				    </b-form-group>

				    <b-form-group>
						<b-input-group>
		                    <b-row style="width: 100%;">
		                        <b-col sm="12"><label for="ip">{{$lang('installsite.form.IPLabel')}}</label></b-col>
		                        <b-col sm="12" class="loader-input variante" style="position: relative;">
		                        	<span v-if="loaderInfo" class="input-loader" >
		                                <loader style="z-index: 1000;"></loader>
		                            </span>
			                        <b-form-select name="ip" :disabled="domainuseid" v-model="form.ipvalue" :options="ipliste" class="mb-3 select-loader" />
		                        </b-col>
		                    </b-row>
		                </b-input-group>
					</b-form-group>
				</b-col>

			</b-row>
		</b-container>

	</div>
</template>

<script>
	
	import { post , get } from '../lib/api'
	import forearch from '../lib/forearch'

	import { fun_domain , fun_domainip , fun_ipvaluse , fun_ipvalue } from '../lib/fonction';
	import { wordpress , whm_pass , internetbs , create , install_theme , install_plugin , install_file , log_create , runscript } from '../lib/whm';
	import validate from 'validate.js';

	export default {

		props : [], 

		data(){

            return {

            	warning : false, 

            	loader : false , 

            	//loader de change des informations du serveur 
            	loaderInfo : true , 

            	////////////////////////////////
            	
            	form : {
            		domaine : '' ,
            		ipvaluse: ['dedicated','shared'], 
            		ipvalue: '', 
            		package: 'default', 

            		categorie: '', 
            		serveur: '', 

            		domaineIP: '', 
            		password: '', 
            		passconf: '', 
            		passJauge: -1, 
            		contactemail: '', 
            		wpoptionval : ['wordpress'] , 

            		//wordpress
            		wplang : 'fr_FR' , 
            		wp_pass : '' , 
            		wp_username : '' ,
            		theme_name : '' ,

            		theme_file : [] ,
            		wpthemeoptionval : [] , 
            		plugin_file : [] ,
            		wppluginoptionval : [] ,
            		upload_file : [] ,

            	}, 

            	txt : {
            		domaine : '' ,
            		domaineIP : '' ,
            		password : '' ,
            		passconf : '' ,
            		passJauge : '' ,
            		contactemail : '' ,
            		//
            		wp_pass : '' ,
            		wp_username : '' ,
            		theme_name : '' ,
            	},

            	error : {
            		domaine : null ,
            		domaineIP : null ,
            		password : null ,
            		passconf : null ,
            		passJauge : null ,
            		contactemail : null ,

            		wp_pass : null ,
            		wp_username : null ,
            		theme_name : null ,

            		theme_file : null , 
            		plugin_file : null , 
            		upload_file : null , 
            	},

            	////////////////////////////////


			    ipoption: [
			        {text: 'Ip dedicated random', value: 'dedicated'},
			        {text: 'Ip shared random', value: 'shared'},
			    ],

			    ipliste: [],

			    //value de donner 
			    submite_var : ['fun_domain','fun_domainip','fun_ipvaluse','fun_ipvalue'] ,

			    waitpassJauge : false ,
				
				packageliste : [] , 

				wpoption : [
					{text: 'use WWW', value: 'www'},
					{text: 'use HTTPS', value: 'https'},
					{text: 'Disable Indexing SEO', value: 'secret'},
					{text: 'Random admin username', value: 'randomusername'},
					{text: 'Linkwheel', value: 'Linkwheel'},
				], 

				wplangliste : [
					{text: 'English (United States)', value: 'en_US'},
					{text: 'French (France)', value: 'fr_FR'},
				],

				//les data a envoyer
				dataSender : null , 

				titleloader : {title:'',strong:''} , 

				steeploader : '' , 

				percentyloader : '',

				user : {} , 

				serveurs : [] , 

				categories : [] , 

				interval_password_change : null , 

				//enregistre la site de compte cpanel qui a été crée 
				cpanelcompte : [] ,
				//enregistre l'id de la token unique qui enregistre 
				sh1keycompte : null , 
				//percenty loader

				//affiche ou pa le cpanel option 
				cpanelOption : false , 
				wordpressOption : false , 

            }
    
        },

        computed : {

        	domainuseid () {
        		return this.form.ipvaluse.length>0?true:false;
        	}

        },

        methods : {

        	fun_domain , fun_domainip , fun_ipvaluse , fun_ipvalue , 

        	changeinput : function ( champ ) {

                if ( this[champ+'_change'] ) {
                	this[champ+'_change']() ; 
                }
                else if ( this.form[champ] ) {
                    this.error[champ]=null ; 
                }

            },

        	formathtml : function ( data ) {
                return  data.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'); ;   
            },

        	domaine_change () {

        		
        		if ( this.form.domaine == '' ) {
        			this.error.domaine = null;
        			return true ; 
        		}

        		if ( this.form.domaine !== '' && this.form.domaineIP ) {
        			this.error.domaineIP = null ; 
        			this.form.domaineIP = '' ; 
        		}

        		//chech 
        		let tempdata = this.fun_domain( this.form.domaine ) ; 

        		let isvalide = true ; 
        		for (var i = 0; i < tempdata.length; i++) {
        			if ( validate({website: this.addschemes(tempdata[i]) }, {website: {url: true}}) ) {
        				isvalide = false ; 
        			}
        		}

        		if ( !isvalide && (this.error.domaine == null||this.error.domaine == true) ) {
        			this.error.domaine = false ; 
        		}else if ( isvalide && !this.error.domaine ) {
        			this.error.domaine = null;
        		}

        	},

        	domaineIP_change () {
        	
        		if ( this.form.domaineIP == '' ) {
        			this.error.domaineIP = null;
        			return true ; 
        		}

        		if ( this.form.domaineIP !== ''  && this.form.domaine ) {
        			this.error.domaine = null;
        			this.form.domaine = '' ; 
        		}

        		let tempdata = this.fun_domainip( this.form.domaineIP ) ;

        		if ( tempdata == false ) {
        			this.error.domaineIP = false;
        			return
        		}

        		let isvalide = true ;

        		for (var i = 0; i < tempdata.length; i++) {
        			if ( validate({website: this.addschemes( tempdata[i].domain ) }, {website: {url: true}}) || ! /^(([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])(\.(?!$)|(?=$))){4}$/.test( tempdata[i].ip||"") ) {
        				isvalide = false ; 
        			}
        		}

        		if ( !isvalide && (this.error.domaineIP == null||this.error.domaineIP==true) ) {
        			this.error.domaineIP = false ; 
        		}else if ( isvalide && !this.error.domaineIP ) {
        			this.error.domaineIP = null;
        		}
        		
        	},

        	addschemes( s ){

        		let prefix = 'http://';
				if (s.substr(0, prefix.length) !== prefix)
				{
				    s = prefix + s;
				}
				return s ;

        	},

        	contactemail_change(){

        		var constraints = {
				  	from: {
				    	email: true
				  	}
				};

				let isvalide = true ;

        		if ( validate({from: this.form.contactemail}, constraints)) {
        			isvalide = false ;
        		}
        		this.error.contactemail = isvalide?null:false ; 

        	},

        	password_change( data ){

        		clearInterval( this.interval_password_change ) ; 

        		this.error.password = null ; 

        		this.interval_password_change = setTimeout(()=>{
        			this.password_change_chech( this.form.password ) ; 
        		}, 800);

        	},

        	password_change_chech( data ){

        		this.waitpassJauge = true ;

        		whm_pass( data , ( e ) => {

        			this.waitpassJauge = false ; 
        			this.form.passJauge = e ;  
        			if ( this.form.passJauge <= 82 ) {
        				this.error.password = false;
        				this.error.passJauge = false;
        				this.txt.password = this.$lang('installsite.form.passJauge') ; 
        			}else{
        				this.error.password = null;
        				this.error.passJauge = null;
        				if ( this.form.password !== this.form.passconf) {
		    				this.error.passconf = false ; 
		    			}	
        			}

        		});

        	},

        	password_change_in_generator( data ){

        		this.form.password = data ; 
        		this.form.passconf = this.form.password ;
        		this.password_change_chech( this.form.password ) ; 

        	},

        	change_serveur( ){

        		this.changeInfoServeur() ; 

        	},

        	//vérifie si les element sont submitable 
        	issubmitable( data ){

        		let iserror = false ; 
        		let keys = Object.keys( this.error ) ; 
        		for (var i = 0; i < keys.length; i++) {
        			if ( this.error[ keys[i] ] === false ) {
        				iserror = true ; 
        			}
        		}

        		if ( this.form.domaineIP =='' && this.form.domaine =='' ) {
        			iserror = true ; 
        			this.error.domaine = false;
        		}

        		if ( this.form.contactemail == '' ) {
        			iserror = true ; 
        			this.error.contactemail = false;
        		}

        		if (!this.form.password) {
        			this.error.password = false;
        		}else if ( this.form.passJauge <= 82 ) {
    				this.error.password = false;
    				this.error.passJauge = false;
    				this.txt.password = this.$lang('installsite.form.passJauge') ; 
    			}

        		return !iserror;

        	},

        	theme_file_change( files ){

				this.form.theme_file = files ; 

				let error_zip = false ; 
				for (var i = 0; i < this.form.theme_file.length; i++) {
					let file_ext = this.form.theme_file[i]['name'].split('.').pop();
					if ( file_ext.toLowerCase() != 'zip' ) {
						error_zip = true ; 
					}
				}

				this.error.theme_file = error_zip?false:null ; 

        	},


        	plugin_file_change( files ){

				this.form.plugin_file = files ; 

				let error_zip = false ; 
				for (var i = 0; i < this.form.plugin_file.length; i++) {
					let file_ext = this.form.plugin_file[i]['name'].split('.').pop();
					if ( file_ext.toLowerCase() != 'zip' ) {
						error_zip = true ; 
					}
				}

				this.error.plugin_file = error_zip?false:null ; 

        	}, 

        	chechFilePost(){

        		if ( this.form.upload_file.length == 0 ) {

        			return true
        		
        		}

        		if ( this.form.domaine ) {
        			
        			let tempdata = this.fun_domain( this.form.domaine ) ; 

        			if ( tempdata && this.form.upload_file.length < tempdata.length ) {
	        			return false ; 
	        		}

        		}

        		if ( this.form.domainIP ) {
        			
        			let tempdataIp = this.fun_domainip( this.form.domainIP ) ;
        			
        			if ( tempdataIp && this.form.upload_file.length < tempdataIp.length  ) {
	        			return false ; 
	        		}

        		}

        		return true ;

        	}, 


        	upload_file_change( files ){

        		this.form.upload_file = files ; 

				let error_zip = false ; 
			
				for (var i = 0; i < this.form.upload_file.length; i++) {
					let file_ext = this.form.upload_file[i]['name'].split('.').pop();
					if ( file_ext.toLowerCase() != 'txt' ) {
						error_zip = true ; 
					}
				}


				if ( error_zip == true || ! this.chechFilePost() ) {
					this.error.upload_file = false ;
				}else{
					this.error.upload_file = null ;
				}

        	},

        	endinstallshoed(){

        		var formData = new FormData();

        		formData.append( "contactemail", this.form.contactemail ); 

    			formData.append("sh1keycompte", this.sh1keycompte);

        		log_create( formData , (res)=>{

        			console.log( res );

        			this.loader = false ; 

        			if ( res ) {
        			
	        			let success = res.success.length ; 

	        			let error = res.error.length ;

	        			if ( error > 0 && success == 0 ) {
		                    this._$emit('alertError',this.$lang('installsite.error.create'));  
		        		}else if ( error == 0 && success > 0 ) {
		                    this._$emit('alert',this.$lang('installsite.success.create'));  
		        		}else if ( error > 0 && success > 0 ) {
		                    this._$emit('alertwarning',this.$lang('installsite.warning.create',{success,error}));  
		        		}

        			}

					document.querySelector('.cont-app').scrollTop = 0;

					setTimeout(()=>{
						location.reload();
					}, 4000);
    
        		})


        	}, 

        	//installation de wordpress 
        	wordpress( cps ){

				this.titleloader.title = this.$lang('installsite.title.loader.etape2') ; 
				this.titleloader.strong = this.$lang('installsite.info.loader.etape2') ; 

				let alldm  = cps.map( e => e.domain )
				let item = 0 ; 
        		let each = forearch( cps , ( cp , next )=>{

        			item++;

        			var formData = new FormData();

    				formData.append("sh1keycompte", this.sh1keycompte);
    				formData.append("alldm", alldm);
    				formData.append("username", cp.username);
    				formData.append("contactemail", cp.contactemail);
    				formData.append("domain", cp.domain);
	        		formData.append( "wp_pass", this.form.wp_pass );
	        		formData.append( "wp_username", this.form.wp_username );
	        		
	        		if ( this.form.wpoptionval.indexOf( 'Linkwheel' ) > -1 ) {
	        			formData.append( "Linkwheel", true );
	        		}

	        		if ( this.form.wpoptionval.indexOf( 'www' ) > -1 ) {
	        			formData.append( "www", true );
	        		}

	        		if ( this.form.wpoptionval.indexOf( 'https' ) > -1 ) {
	        			formData.append( "https", true );
	        		}

	        		if ( this.form.wpoptionval.indexOf( 'secret' ) > -1 ) {
	        			formData.append( "secret", true );
	        		}

	        		if ( this.form.wpoptionval.indexOf( 'randomusername' ) > -1 ) {
	        			formData.append( "randomusername", true );
	        		}

        			wordpress( formData , this.form.categorie , this.form.serveur , ( res , sh1unique )=>{
						next() ; 
					},( e )=>{
						console.log( e );
	        			this.steeploader = e ; 
	        			this.percentyloader = ((item * 100) / cps.length) + ' %' ; 
	        		})

        		})

        		each.end(()=>{
        			if ( this.user.script ) {
						runscript( cps , this.form.serveur ) ; 
					}
        			//Installation de theme
        			this.install_theme( cps ) ; 
        		})

        		each.run() ; 

        	}, 

        	internetbs( succes , end = false ){

        		console.log(' --- internetbs ' , succes );
        		//installation de wordpress 
        		if (end === false ) {
        			
        			let dataSend = succes.map( e => e.domain +';' + e.ip ) ; 
        			internetbs( dataSend , this.form.serveur , ()=>{
        				//lancement si un script existe

        				this.wordpress( succes ) ; 
        			})

        		}else{
        			if ( succes.length ) {
        				let dataSend = succes.map( e => e.domain +';' + e.ip ) ; 
	        			//ici on termine l'installation
	        			internetbs( dataSend , this.form.serveur , ()=>{
	        				this.endinstallshoed() ;
	        			})
        			}else{
        				this.endinstallshoed() ;
        			}
        		}
        		
        	},  

        	cpanel( formData ){

				this.titleloader.title = this.$lang('installsite.title.loader.etape1') ; 
				
				if (this.form.wpoptionval.indexOf( 'wordpress' ) > -1) {
					this.titleloader.strong = this.$lang('installsite.info.loader.etape1') ; 
				}else{
					this.titleloader.strong = '' ; 
				}

				create( formData , this.form.categorie , this.form.serveur , ( res )=>{

					this.cpanelcompte = res.data ; 
					this.sh1keycompte = res.sh1unique ;

					if ( res.data.success.length > 0 && this.form.wpoptionval.indexOf( 'wordpress' ) > -1 ) {
						//crée wordpress si l'option wordpress est selectonner 
						
						console.log('script lancement ici : ' , this.user.script );
						
						if ( (this.user.internetbs == 1 || this.user.internetbs == '1' ) && this.user.internetbskey && this.user.internetbspass ) {
							this.internetbs( res.data.success , false ) ;
						}else{
							this.wordpress( res.data.success ) ; 
						}

					}else{
						console.log( (this.user.internetbs == 1 || this.user.internetbs == '1' ) && this.user.internetbskey && this.user.internetbspass , this.user );
						if ( (this.user.internetbs == 1 || this.user.internetbs == '1' ) && this.user.internetbskey && this.user.internetbspass ) {
							this.internetbs( res.data.success , true ) ;
						}else{
							this.endinstallshoed() ; 
						}

					}

				},( e )=>{
					console.log( e );
        			this.steeploader = e.title ; 
        			this.percentyloader = e.value ; 
        		});

        	},

        	create( formData ){

        		this.cpanel( formData )

        	},

        	submite(){

				if ( ! this.chechFilePost() ) {
					return this.error.upload_file = false ;
				}

				this.error.upload_file = null ;  
        		
        		let dataSender = {} ; 

        		var formData = new FormData();

    			formData.append("domain", this.form.domaine);
        		dataSender['domain'] = this.form.domaine ; 
        		
        		ipvaluse: ['dedicated','shared'] ;

        		if ( this.form.ipvaluse.indexOf( 'dedicated' ) > -1 ) {
        			dataSender['ipdedicated'] = true ; 
    				formData.append( "ipdedicated", true );
        		}else{
        			dataSender['ipdedicated'] = false ; 
    				formData.append( "ipdedicated", false );
        		}

        		if ( this.form.ipvaluse.indexOf( 'shared' ) > -1 ) {
        			dataSender['ipshared'] = true ; 
    				formData.append( "ipshared", true );
        		}else{
        			dataSender['ipshared'] = false ; 
    				formData.append( "ipshared", false );
        		}

        		dataSender['ip'] = this.form.ipvalue ; 
    			formData.append( "ip", this.form.ipvalue );
        		
        		dataSender['domainIP'] = this.form.domaineIP ; 
    			formData.append( "domainIP", this.form.domaineIP );

        		//domain IP 

        		dataSender['package'] = this.form.package ;
    			formData.append( "package", this.form.package );

        		dataSender['password'] = this.form.password ; 
        		formData.append( "password", this.form.password );

        		dataSender['passconf'] = this.form.passconf ;
        		formData.append( "passconf", this.form.passconf ); 

        		dataSender['passJauge'] = this.form.passJauge ;
        		formData.append( "passJauge", this.form.passJauge );  

        		dataSender['contactemail'] = this.form.contactemail ; 
        		formData.append( "contactemail", this.form.contactemail );  


        		if ( this.form.wpoptionval.indexOf( 'wordpress' ) > -1 ) {
        			dataSender['wordpress'] = true ; 
        			formData.append( "wordpress", true );
        		}

        		if ( this.form.wpoptionval.indexOf( 'www' ) > -1 ) {
        			dataSender['www'] = true ; 
        			formData.append( "www", true );
        		}

        		if ( this.form.wpoptionval.indexOf( 'https' ) > -1 ) {
        			dataSender['https'] = true ; 
        			formData.append( "https", true );
        		}

        		if ( this.form.wpoptionval.indexOf( 'secret' ) > -1 ) {
        			dataSender['secret'] = true ; 
        			formData.append( "secret", true );
        		}

        		if ( this.form.wpoptionval.indexOf( 'randomusername' ) > -1 ) {
        			dataSender['randomusername'] = true ; 
        			formData.append( "randomusername", true );
        		}

        		if ( this.form.wpoptionval.indexOf( 'Linkwheel' ) > -1 ) {
        			dataSender['Linkwheel'] = true ; 
        			formData.append( "Linkwheel", true );
        		}
				
				dataSender['wplang'] = this.form.wplang ; 
        		formData.append( "wplang", this.form.wplang );

				dataSender['wp_pass'] = this.form.wp_pass ; 
        		formData.append( "wp_pass", this.form.wp_pass );

				dataSender['wp_username'] = this.form.wp_username ; 
        		formData.append( "wp_username", this.form.wp_username );
				
				dataSender['theme_name'] = this.form.theme_name ;  
        		formData.append( "theme_name", this.form.theme_name );

				dataSender['progress'] = true ;  
        		formData.append( "progress", true );
				
				if ( this.form.wpthemeoptionval.indexOf( 'activate_theme' ) > -1 ) {
        			dataSender['activate_theme'] = true ; 
        			formData.append( "activate_theme", true );
        		}

        		if ( this.form.wppluginoptionval.indexOf( 'upluginactive' ) > -1 ) {
        			dataSender['upluginactive'] = true ; 
        			formData.append( "upluginactive", true );
        		}

        		if ( ! this.issubmitable( dataSender ) ) {
        			return
        		}

        		let kay = Object.keys( dataSender ) ; 

        		for (var i = 0; i < kay.length; i++) {
        			if ( dataSender[kay[i]] =='' || dataSender[kay[i]] == undefined ) {
        				delete dataSender[kay[i]] ; 
        			}
        		}

        		this.loader = true ; 

        		this.dataSender = dataSender ; 

        		this.create( formData ) ; 

        	},

        	install_theme( cps , cbl ){

        		console.log('Instalation de théme' , cps );

				let usernameArray  = cps.map( e => e.username ) ; 

				let username = [] ;

				for (var i = 0; i < usernameArray.length; i++) {
    				username += usernameArray[i] + ' ';
    			}

    			username = username.trim() ;

        		if ( this.form.theme_file.length ==0 ) {
        			return this.install_plugin( username ) ; 
        		}

        		this.steeploader = this.$lang('installsite.loader.theme') ;
				this.percentyloader = "" ; 

				this.titleloader.title = '' ; 
				this.titleloader.strong = '' ;

				var formData = new FormData();
				formData.append("file", this.form.theme_file[0]);
				formData.append("username", username);
				formData.append("serveur_id", this.form.serveur );

				install_theme( formData , ()=>{
					this.install_plugin( username ) ;
				})
        	
        	},

        	install_plugin( username ){

        		if ( this.form.plugin_file.length ==0 ) {
        			return this.install_file( username ) ; 
        		}

        		let each = forearch( this.form.plugin_file , ( file , next )=>{
					
					this.steeploader = this.$lang('installsite.loader.theme') ;
					this.percentyloader = "" ; 

					this.titleloader.title = '' ; 
					this.titleloader.strong = '' ;

					var formData = new FormData();
				
					formData.append("file",  file );
					formData.append("serveur_id", this.form.serveur );
					formData.append("username", username );
					formData.append("Active", this.dataSender['upluginactive']);

					install_plugin( formData , (e)=>{
						console.log( 'RESPONCE PLUGIn ', e );
						next() ; 
					})


				})

				each.end(()=>{
        			//Installation de theme
        			this.install_file( username ) ; 
        		})

        		each.run() ;

        	},

        	install_file( username ){

        		if ( this.form.upload_file.length ==0 ) {
        			return this.endinstallshoed() ; 
        		}

        		let user = username.split(" ");

				this.steeploader = this.$lang('installsite.loader.post') ; 

				let i = 0 ; 
        		let each = forearch( this.form.plugin_file , ( file , next )=>{
					
					var formData = new FormData();

					formData.append("file", this.form.upload_file[i]);
					formData.append("username", user[i] );
					formData.append("serveur_id", this.form.serveur );

					i++;
					install_file( formData , (e)=>{
						console.log('Installe File ', e );
						next() ; 
					})

				})
				
				each.end(()=>{
					
					this.endinstallshoed( ) ;

				})
				
				each.run() ;

        	},

        	//
        	zip(){

        		return true ; 

        	},

        	initPreformData(){

        		this.user = Object.assign({} , document.user ) ; 

				if ( this.user.cppassword ) {
					this.form.passconf = this.user.cppassword ; 
					this.form.password = this.user.cppassword ; 
				}

				if ( this.user.wppassword ) {
					this.form.wp_pass = this.user.wppassword ; 
				}

				if ( this.user.wpusername ) {
					this.form.wp_username = this.user.wpusername ; 
				}

				if ( this.user.cpemail ) {
					this.form.contactemail = this.user.cpemail ; 
				}

        	}, 

        	changeInfoServeur(){

        		this.loaderInfo = true ; 
        		
        		console.log( this.form.serveur );

        		let url = window.urlapp+'/serveur/whm/'+this.form.serveur ;  

        		get( url )

        			.then((res)=>{

                   		this.packageliste = res.data.listpkgs.map((e)=>{
                   			let n = e.name ; 
                   			return { value: n , text: n }
                   		})

                   		if (this.packageliste.length) {
                   			this.form.package = this.packageliste[0].value ;
                   		}

                   		this.ipliste = res.data.listip.map((e)=>{
                   			return { value: e , text: e }
                   		})

                    	this.loaderInfo = false ; 

                    })  

                    .catch((err)=>{

                    	console.log( err );
                    	this.loaderInfo = false ; 
                    })

        	},

        	//loader de la liste des serveur 
        	serveurLoad(){

        		let url = window.urlapp+'/serveur' ;  

                get( url )

                    .then((res)=>{

                    	if ( res.data.success.length > 0 ) {

                    		let serv = res.data.success.filter( e => parseInt(e.active)==0?false:true ); 
                    		this.serveurs = serv.map((e)=>{
                    			return { value: e.id, text: e.url }
                    		}); 

                    		this.form.serveur = this.serveurs[0].value ; 

                    		console.log( this.form.serveur );
	                    	
	                    	let url = window.urlapp+'/categorie' ;  

			                return get( url )

			                    .then((res)=>{

			                    	this.changeInfoServeur() ; 

			                    	if ( res.data.success.length > 0 ) {
			                    		this.categories = res.data.success.map((e)=>{
			                    			return { value: e.id, text: e.name }
			                    		}); 
			                    		this.form.categorie = this.categories[0].value ; 
			                  			return this.initPreformData() ; 
			                    	}

			                    	this.warning = true ; 

			                    })  

			                    .catch((err)=>{
			                    	this.warning = true ; 
			                    })
                    	
                    	}
			            
			            this.warning = true ; 

                    })  

                    .catch((err)=>{
                    	this.warning = true ; 
                    })

        	}, 

        },
		
		mounted(){ 

			this.serveurLoad() ; 

		}

	}

</script>

<style>

	.cont-app .install  .form-control {
	    color: #495057;
	    background-color: #ffffff !important;
	    border: 1px solid rgba(0,0,0,0);
	}

	.checkboxwordpress .custom-control{
		width: 45%;
		margin-bottom: 1rem;
	}
	
	.pln-manager-fond{
		background-color: #e4e4e4;
		border: 1px dashed #c5c5c5;
		padding: 0.51rem;
		margin-bottom: 1rem;
		padding-top: 1.3rem;
	}

</style>