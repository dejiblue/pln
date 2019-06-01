<template>
	<div class="cont-app">
        <b-container fluid>
            <b-row class="justify-content-md-center">
                <b-col xl="6" lg="8" md="9" sm="10" cols="12">
                    <div class="col-form">
                        <div class="col-form-cont">

                            <b-row>
                                <b-col>
                                    <b-alert v-if="info" :variant="info.type" show>{{info.txt}}</b-alert>
                                    <b-progress v-if="loader" :value="100" variant="info" striped animated class="mb-2"></b-progress>
                                </b-col>
                            </b-row>  
                            
                            <b-row v-if="successinfo">
                                <b-col>
                                    <b-alert show variant="success">
                                        <h4 class="alert-heading">{{$lang('sinup.form.alert.title')}}</h4>
                                        <p v-html="formathtml($lang('sinup.form.alert',{name:form.name}))">
                                            
                                        </p>
                                    </b-alert>
                                </b-col>
                            </b-row>  

                            <b-row v-if="!successinfo">
                                <b-col sm="5" cols="12">
                                    <Avatar @reset="error.avatar=null;txt.avatar='';" :error="error.avatar" :txterror="txt.avatar" :unchange="true" @avatar="avatar_change" style="position: relative;top: 50%;transform: translateY(-50%);" ></Avatar>
                                </b-col>
                                <b-col sm="7" cols="12">
                                    
                                    <h1>{{$lang('sinup.title')}}</h1>
                                    
                                    <b-form class="form-login" @submit.prevent.stop="onSubmit">
                                        
                                        <b-form-group>
                                            <b-input-group>
                                                <span class="input-icon" >
                                                    <img :src="'./img/bande2.png'" alt="">
                                                </span>
                                                <b-form-input id="name"
                                                        class="input-icon-txt"
                                                        v-model="form.name"
                                                        :state="error.name"
                                                        @input="changeinput('name')"
                                                        :placeholder="$lang('sinup.form.name')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurName">
                                                    <span vi-if="!txt.name">
                                                        {{$lang('sinup.form.nameError')}}
                                                    </span>
                                                    <span vi-if="txt.name">
                                                        {{txt.name}}
                                                    </span>
                                                </b-form-invalid-feedback>
                                            </b-input-group>
                                        </b-form-group>
                                        
                                        <b-form-group>
                                            <b-input-group>
                                                <span class="input-icon" >
                                                    <img :src="'./img/bande2.png'" alt="">
                                                </span>
                                                <b-form-input id="forname"
                                                        class="input-icon-txt"
                                                        v-model="form.forname"
                                                        :state="error.forname"
                                                        @input="changeinput('forname')"
                                                        :placeholder="$lang('sinup.form.forname')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurForname">
                                                    <span vi-if="!txt.forname">
                                                        {{$lang('sinup.form.fornameError')}}
                                                    </span>
                                                    <span vi-if="txt.forname">
                                                        {{txt.forname}}
                                                    </span>
                                                </b-form-invalid-feedback>
                                            </b-input-group>
                                        </b-form-group>
                                        
                                        <b-form-group>
                                            <b-input-group>
                                                <span class="input-icon" >
                                                    <img :src="'./img/bande3.png'" alt="">
                                                </span>
                                                <b-form-input id="email"
                                                        class="input-icon-txt"
                                                        v-model="form.email"
                                                        :state="error.email"
                                                        @input="changeinput('email')"
                                                        :placeholder="$lang('sinup.form.email')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurEmail">
                                                    <span vi-if="!txt.email">
                                                        {{$lang('sinup.form.emailError')}}
                                                    </span>
                                                    <span vi-if="txt.email">
                                                        {{txt.email}}
                                                    </span>
                                                </b-form-invalid-feedback>
                                            </b-input-group>
                                        </b-form-group>
                                        
                                        <b-form-group>
                                            <b-input-group>
                                                <span class="input-icon" >
                                                    <img :src="'./img/bande4.png'" alt="">
                                                </span>
                                                <b-form-input id="password"
                                                        type="password"
                                                        class="input-icon-txt"
                                                        v-model="form.password"
                                                        :state="error.password"
                                                        @input="changeinput('password')"
                                                        :placeholder="$lang('sinup.form.password')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurEmail">
                                                    <span vi-if="!txt.password">
                                                        {{$lang('sinup.form.passwordError')}}
                                                    </span>
                                                    <span vi-if="txt.password">
                                                        {{txt.password}}
                                                    </span>
                                                </b-form-invalid-feedback>
                                            </b-input-group>
                                        </b-form-group>
                                        
                                        <b-form-group>
                                            <b-input-group>
                                                <span class="input-icon" >
                                                    <img :src="'./img/bande4.png'" alt="">
                                                </span>
                                                <b-form-input 
                                                        
                                                        type="password"
                                                        id="passconf"
                                                        class="input-icon-txt"
                                                        v-model="form.passconf"
                                                        :state="error.passconf"
                                                        @input="changeinput('passconf')"
                                                        :placeholder="$lang('sinup.form.passconf')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurEmail">
                                                    <span vi-if="!txt.passconf">
                                                        {{$lang('sinup.form.passwordError')}}
                                                    </span>
                                                    <span vi-if="txt.passconf">
                                                        {{txt.passconf}}
                                                    </span>
                                                </b-form-invalid-feedback>
                                            </b-input-group>
                                        </b-form-group>

                                        <b-button style="display: block; width: 100%;" type="submit" variant="primary">{{$lang('sinup.form.submit')}}</b-button>

                                        <b-button style="float: right;margin-top: 10px;" variant="link">
                                            
                                        </b-button>

                                        <a href="/login" class="btn btn-link" style="float: right;margin-top: 10px;" >{{$lang('sinup.form.login')}} </a>


                                    </b-form>

                                </b-col>
                            </b-row>
                        </div>
                    </div>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    
    import 'bootstrap/dist/css/bootstrap.css'
    import 'bootstrap-vue/dist/bootstrap-vue.css'

    import { get, post } from './lib/api'

    export default {

        name: 'App', 

        data(){
        
            return {

                error : {
                    name : null,
                    forname : null,
                    email : null,
                    password : null,
                    passconf : null,
                    avatar : null,
                },

                form: {
                    name : '',
                    forname : '',
                    email : '',
                    password : '',
                    passconf : '',
                    avatar : '',
                },

                txt: {
                    name : '',
                    forname : '',
                    email : '',
                    password : '',
                    passconf : '',
                    avatar : '',
                },

                loader : false , 

                info : null , 

                successinfo : false , 

            }
        
        },

        computed : {

        },

        methods : {

            hideInfo : function () {
                
                setTimeout(()=>{

                    this.info = null ; 

                }, 3000);

            },

            formathtml : function ( data ) {
                
                return  data.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'); ; 
                
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

                let url = window.urlapp+'/register' ; 

                var formData = new FormData();

                formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');

                formData.append("name", this.form.name);
                formData.append("forname", this.form.forname);
                formData.append("email", this.form.email); 
                formData.append("password", this.form.password); 
                formData.append("password_confirmation", this.form.passconf); 

                if ( this.form.avatar ) {
                    formData.append("avatar", this.form.avatar);
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

                            this.info = Object.assign({} , { type : 'danger' ,  txt : this.$lang('sinup.form.error')}) ;
                            
                            this.hideInfo() ; 
                        
                        }else{
                            this.successinfo = true ; 
                        }

                    
                    })  

                    .catch((err)=>{
                        
                        this.loader = false ; 

                        this.info = Object.assign({} , { type : 'danger' ,  txt : this.$lang('form.error') }) ;
                        this.hideInfo() ; 

                    })

            }

        },

        mounted(){

            setTimeout(()=>{
                $('#principale-loader').hide() ; 
            }, 1000);
        
        },
        
        created(){

            
        
        }

    }
</script>
<style>
    
    body {
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif !important ;
        font-size: 14px !important;
        line-height: 1.42857143 !important;
        color: #333 !important ;
        background-color: #fff !important;
    }

    .justify-content-md-center {
        -ms-flex-pack: center !important;
        justify-content: center !important;
    }


</style>