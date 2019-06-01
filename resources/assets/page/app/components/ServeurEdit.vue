<template>
	<div class="ca-editor">
		
        <b-row>
            <b-col>
                <b-progress :max="100" v-if="loader" variant="info" striped animated class="mb-2">
                    <b-progress-bar :value="100">
                        <span v-if="information">{{$lang(information)}}</span>
                    </b-progress-bar>
                </b-progress>
            </b-col>
        </b-row> 

        <b-form class="form-login" @submit.prevent.stop="onSubmit">

            <b-form-group>
                <b-input-group>

                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="name">{{$lang('serv.form.name')}} : </label></b-col>
                        <b-col sm="8">
                            <b-form-group>
                                <b-form-input id="name"
                                        v-model="form.name"
                                        :state="error.name"
                                        @input="changeinput('name')"
                                        :placeholder="$lang('serv.form.namePlace')">
                                </b-form-input>
                                <b-form-invalid-feedback id="ErreurName">
                                    <span v-if="!txt.name">
                                        {{$lang('serv.form.nameError')}}
                                    </span>
                                    <span v-if="txt.name">
                                        {{txt.name}}
                                    </span>
                                </b-form-invalid-feedback>
                            </b-form-group>

                        </b-col>
                    </b-row>
               
                </b-input-group>

                <b-input-group>

                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="url">{{$lang('serv.form.url')}} : </label></b-col>
                        <b-col sm="8">
                            <b-form-group>
                                <b-form-input id="url"
                                        v-model="form.url"
                                        :state="error.url"
                                        @input="changeinput('url')"
                                        :placeholder="$lang('serv.form.urlPlace')">
                                </b-form-input>
                                <b-form-invalid-feedback id="ErreurUrl">
                                    <span v-if="!txt.url">
                                        {{$lang('serv.form.urlError')}}
                                    </span>
                                    <span v-html="formathtml(txt.url)" v-if="txt.url"></span>
                                </b-form-invalid-feedback>
                            </b-form-group>

                        </b-col>
                    </b-row>
               
                </b-input-group>


                <!--<b-input-group>

                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="port">{{$lang('serv.form.port')}} : </label></b-col>
                        <b-col sm="8">
                            <b-form-group>
                                <b-form-input id="port"
                                        v-model="form.port"
                                        :state="error.port"
                                        @input="changeinput('port')"
                                        :placeholder="$lang('serv.form.portPlace')">
                                </b-form-input>
                                <b-form-invalid-feedback id="ErreurPort">
                                    <span v-if="!txt.port">
                                        {{$lang('serv.form.portError')}}
                                    </span>
                                    <span v-if="txt.port">
                                        {{txt.port}}
                                    </span>
                                </b-form-invalid-feedback>
                            </b-form-group>

                        </b-col>
                    </b-row>
               
                </b-input-group>-->

                <b-input-group>

                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="username">{{$lang('serv.form.username')}} : </label></b-col>
                        <b-col sm="8">
                            <b-form-group>
                                <b-form-input id="username"
                                        v-model="form.username"
                                        :state="error.username"
                                        @input="changeinput('username')"
                                        :placeholder="$lang('serv.form.usernamePlace')">
                                </b-form-input>
                                <b-form-invalid-feedback id="ErreurPort">
                                    <span v-if="!txt.username">
                                        {{$lang('serv.form.usernameError')}}
                                    </span>
                                    <span v-if="txt.username">
                                        {{txt.username}}
                                    </span>
                                </b-form-invalid-feedback>
                            </b-form-group>

                        </b-col>
                    </b-row>
               
                </b-input-group>

                <b-input-group>

                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="token">{{$lang('serv.form.token')}} : </label></b-col>
                        <b-col sm="8">
                            <b-form-group>
                                <b-form-input id="accesstoken"
                                        type="password"
                                        v-model="form.accesstoken"
                                        :state="error.accesstoken"
                                        @input="changeinput('accesstoken')"
                                        :placeholder="$lang('serv.form.tokenPlace')">
                                </b-form-input>
                                <b-form-invalid-feedback id="ErreurToken">
                                    <span v-if="!txt.accesstoken">
                                        {{$lang('serv.form.tokenError')}}
                                    </span>
                                    <span v-if="txt.accesstoken">
                                        {{txt.accesstoken}}
                                    </span>
                                </b-form-invalid-feedback>
                            </b-form-group>

                        </b-col>
                    </b-row>
               
                </b-input-group>

                <b-input-group>

                    <b-row style="width: 100%;">
                        <b-col sm="4"><label for="sshport">{{$lang('serv.form.sshport')}} : </label></b-col>
                        <b-col sm="8">
                            <b-form-group>
                                <b-form-input id="sshport"
                                        v-model="form.sshport"
                                        :state="error.sshport"
                                        @input="changeinput('sshport')"
                                        :placeholder="$lang('serv.form.sshportPlace')">
                                </b-form-input>
                                <b-form-invalid-feedback id="ErreurSshport">
                                    <span v-if="!txt.sshport">
                                        {{$lang('serv.form.sshportError')}}
                                    </span>
                                    <span v-html="formathtml(txt.sshport)" v-if="txt.sshport"></span>
                                </b-form-invalid-feedback>
                            </b-form-group>

                        </b-col>
                    </b-row>
               
                </b-input-group>


                <b-input-group v-if="updated&&parseInt(data.plnactive)==0">

                    <b-row style="width: 100%;">
                        <b-col sm="12">
                            <b-button :disabled="loader" style="margin-left: -15px;" @click.prevent.stop="installpln" variant="link">{{$lang('serv.form.btn.installpln')}}</b-button>    
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
            	
            	error : {
                    name : null ,
                    url : null ,
                    port : null ,
                    accesstoken : null ,
                    username : null ,
                    sshport : null ,
                },

                form: {
                    name : '' ,
                    url : '' ,
                    port : '2087' ,
                    accesstoken : '' ,
                    username : '' ,
                    sshport : '22' ,
                },

                txt: {
                    name : '' ,
                    url : '' ,
                    port : '' ,
                    accesstoken : '' ,
                    username : '' ,
                    sshport : '' ,
                },

                loader : false , 

                id : false , 

                information : '' , 

                resetallinchange : true , 

                updated : false , 

            }
    
        },

        methods : {


            installpln :function () {

                this.checkInstallPln( this.data , false )

            },

            checkInstallPln: function ( ser , isnew ) {
                
                this.loader = true ; 

                if ( !ser.plnactive|| parseInt(ser.plnactive)==0) {

                    let url = window.urlapp+'/pln/install/'+ser.id ;

                    if ( isnew === false ) {
                        url = url + '?update=true' ; 
                    }
                    
                    this.information = 'serv.form.serveur.install' ; 

                    get( url ) 

                        .then((res)=>{
                            
                            this.information = '' ; 
                            
                            if ( res.data.error ) {
                                this.$emit('close') ;
                                this._$emit('alertError',this.$lang(res.data.error.msg, Object.assign({},ser,{codeerr:res.data.error.codeerr})));
                            }else if(isnew){
                                this.$emit('alert',this.$lang('serveur.new.alertwithpln',ser)) ;
                            }else{
                                this.$emit('alert',this.$lang('serveur.install.alertwithpln',ser)) ;
                            }
                            this._$emit('changeserveur') ;  

                        })  

                        .catch((err)=>{

                            this.information = '' ; 
                            this.$emit('close') ;
                            this._$emit('changeserveur') ;  
                            this._$emit('alertError',this.$lang('form.error'));  

                        })

                }else if( isnew ){
                    this.$emit('alert',this.$lang('serveur.new.alert',ser)) ;
                    this._$emit('changeserveur') ;  

                }

            },

            formathtml : function ( data ) {
                
                return  data.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'); ; 
                
            },

            submite : function () {
            	
                let url = window.urlapp+'/serveur'; 
                let _methode = '' ;

                if ( this.id ) {
                    url = url + '/' + this.id ; 
                    _methode = 'PUT' ;
                }else{
                    _methode = 'POST' ;
                }

                var formData = new FormData();

                formData.append("token", document.csrf_token);
                formData.append("_method", _methode );

                if ( this.form.name ) {
                    formData.append("name", this.form.name);
                }

                if ( this.form.url ) {
                    formData.append("url", this.form.url);
                }

                if ( this.form.port ) {
                    formData.append("port", this.form.port);
                }

                if ( this.form.username ) {
                    formData.append("username", this.form.username);
                }

                if ( this.form.accesstoken ) {
                    formData.append("accesstoken", this.form.accesstoken);
                }

                if ( this.form.sshport ) {
                    formData.append("sshport", this.form.sshport);
                }

                this.loader = true ; 

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{

                        this.loader = false ; 
                        this.info = null ; 
                        
                        if ( res.data.error ) {

                            let keys = Object.keys( res.data.error ) ; 

                            for (var i = 0; i < keys.length; i++) {

                                if ( keys[i]!=='reset') 
                                    this.error[keys[i]] = false ;
                                else
                                    this.resetallinchange = true ; 

                                if (typeof (res.data.error[keys[i]]) == 'string')
                                    this.txt[keys[i]]=this.$lang(res.data.error[keys[i]],this.form) ; 
                                else 
                                    this.txt[keys[i]]=this.$lang(res.data.error[keys[i]][0],this.form) ; 
                            }
                        
                        }else{

                            if ( this.id ) {
                                this.$emit('alert',this.$lang('serveur.update.alert')) ;  
                                this._$emit('changeserveur') ;  
                            }else{
                                this.checkInstallPln( res.data.success , true ) ;  
                            }

                            
                        }
                    
                    })  

                    .catch((err)=>{
                        
                        this.loader = false ; 
                        this.$emit('close') ;  
                        this._$emit('alertError',this.$lang('form.error'));  


                    })

            },

            changeinput : function ( champ ) {
                
                if ( this.resetallinchange ) {
                    
                    this.resetallinchange = false ; 
                    let keys = Object.keys(this.form) ; 

                    for (var i = 0; i < keys.length; i++) {
                        this.error[keys[i]]=null ; 
                    }
                    
                }
                else if ( this.form[champ] ) {
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

            if ( this.data ) {
                
                this.id = this.data.id ; 
                this.form.name = this.data.name ; 
                this.form.url = this.data.url ; 
                this.form.port = this.data.port ; 
                this.form.username = this.data.username ; 
                this.form.accesstoken = this.data.accesstoken ; 
                this.form.sshport = this.data.sshport ; 

                this.updated = true ; 

                console.log( this.data.plnactive );

            }

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