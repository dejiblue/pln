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
                                        <p v-html="formathtml($lang('forgot.form.alert'))"></p>
                                    </b-alert>
                                </b-col>
                            </b-row>

                            <b-row v-if="!successinfo" >
                                
                                <b-col cols="12">
                                    
                                    <h1>{{$lang('forgot.title')}}</h1>
                                    
                                    <b-form class="form-login" @submit.prevent.stop="onSubmit">
                                        
                                        <b-form-group>
                                            <b-input-group>
                                                <span class="input-icon" >
                                                    <img :src="'../img/bande3.png'" alt="">
                                                </span>
                                                <b-form-input id="email"
                                                        class="input-icon-txt"
                                                        v-model="form.email"
                                                        :state="error.email"
                                                        @input="changeinput('email')"
                                                        :placeholder="$lang('forgot.form.email')">
                                                </b-form-input>
                                                <b-form-invalid-feedback id="ErreurEmail">
                                                    <span v-if="!txt.email">
                                                        {{$lang('forgot.form.emailError')}}
                                                    </span>
                                                    <span v-if="txt.email">
                                                        {{txt.email}}
                                                    </span>
                                                </b-form-invalid-feedback>
                                            </b-input-group>
                                        </b-form-group>
                                        
                                        <b-button style="display: block; width: 100%;" type="submit" variant="primary">{{$lang('forgot.form.submit')}}</b-button>

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
                },

                form: {
                    email : '',
                    name : '',
                },

                txt: {
                    email : '',
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

                let url = window.urlapp+'/password/email' ; 

                var formData = new FormData();
                
                formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');

                formData.append("email", this.form.email); 

                this.loader = true ;  

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{

                        this.loader = false ; 

                        if ( res.data.error ) {

                            let keys = Object.keys( res.data.error ) ; 

                            for (var i = 0; i < keys.length; i++) {
                                this.error[keys[i]]=false ; 
                                this.txt[keys[i]]=res.data.error[keys[i]] ; 
                            }

                            this.info = Object.assign({} , { type : 'danger' ,  txt : this.$lang('sinup.form.error')}) ;
                            
                            this.hideInfo() ; 

                        }else{
                            
                            this.successinfo = true ; 
                            console.log( res );

                            setTimeout(()=>{
                                //window.location = window.urlapp +'/password/reset'; 
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