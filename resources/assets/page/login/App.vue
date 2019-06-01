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
                                        <h4 class="alert-heading">{{$lang('login.form.alert.title')}}</h4>
                                        <p v-html="formathtml($lang('login.form.alert',{name:form.name}))">
                                            
                                        </p>
                                    </b-alert>
                                </b-col>
                            </b-row>

                            <b-row v-if="!successinfo" >
                                <b-col sm="5" cols="12">
                                    <Avatar :unchange="true" style="position: relative;top: 50%;transform: translateY(-50%);" ></Avatar>
                                </b-col>
                                <b-col sm="7" cols="12">
                                    
                                    <h1>{{$lang('login.title')}}</h1>
                                    
                                    <b-form class="form-login" @submit.prevent.stop="onSubmit">
                                        
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
                                                        :placeholder="$lang('login.form.email')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurEmail">
                                                    <span v-if="!txt.email">
                                                        {{$lang('login.form.emailError')}}
                                                    </span>
                                                    <span v-if="txt.email">
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
                                                        class="input-icon-txt"
                                                        type="password"
                                                        v-model="form.password"
                                                        :state="error.password"
                                                        @input="changeinput('password')"
                                                        :placeholder="$lang('login.form.password')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurEmail">
                                                    <span v-if="!txt.password">
                                                        {{$lang('login.form.passwordError')}} 
                                                    </span>
                                                    <span v-if="txt.password">
                                                        {{txt.password}}
                                                    </span>
                                                </b-form-invalid-feedback>
                                            </b-input-group>
                                        </b-form-group>
                                        

                                        <b-button style="display: block; width: 100%;" type="submit" variant="primary">{{$lang('login.form.submit')}}</b-button>

                                        <a href="/password/reset" style="display: block; ;margin-top: 10px;" >{{$lang('login.form.forgot')}}</a>

                                        <a href="/register" class="btn btn-link" style="float: right;margin-top: 10px;" variant="link">
                                            {{$lang('login.form.register')}}
                                        </a>

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
                    email : null,
                    password : null,
                },

                form: {
                    email : '',
                    password : '',
                    name : '',
                },

                txt: {
                    email : '',
                    password : '',
                },

                loader : false , 

                info : null , 

                successinfo : false , 


            }
        
        },

        computed : {

        },

        methods : {

            changeinput : function ( champ ) {
                
                if ( this.form[champ] ) {
                    this.error[champ]=null ; 
                }

            },

            hideInfo : function () {
                
                setTimeout(()=>{

                    this.info = null ; 

                }, 3000);

            },

            formathtml : function ( data ) {
                
                return  data.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'); ; 
                
            },

            onSubmit : function () {

                let url = window.urlapp+'/login' ; 

                var formData = new FormData();
                
                formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');

                formData.append("email", this.form.email); 
                formData.append("password", this.form.password);

                this.loader = true ;  

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{

                        console.log( res );

                        this.loader = false ; 

                        if ( res.data.error ) {

                            let keys = Object.keys( res.data.error ) ; 

                            for (var i = 0; i < keys.length; i++) {
                                this.error[keys[i]]=false ; 

                                if ( typeof(res.data.error[keys[i]])=='string' ) {
                                    this.txt[keys[i]]=res.data.error[keys[i]] ; 
                                }else{
                                    this.txt[keys[i]]=res.data.error[keys[i]][0] ; 
                                }

                                
                            }

                            this.info = Object.assign({} , { type : 'danger' ,  txt : this.$lang('sinup.form.error')}) ;
                            
                            this.hideInfo() ; 

                        }else{
                            
                            this.successinfo = true ; 
                            this.form.name = res.data.name ; 

                            setTimeout(()=>{
                                window.location = window.urlapp ; 
                            }, 4000);

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