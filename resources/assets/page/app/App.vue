<template>
	<div class="cont-app">
        
        <menu-app></menu-app>

        <b-container>
            <b-row v-if="successinfo" style="margin-top: 2rem; margin-bottom: -2rem; ">
                <b-col>
                    <b-alert show :variant="alerttype">
                        <p v-html="alerttxt"></p>
                    </b-alert>
                </b-col>
            </b-row> 
        </b-container>
        
        <transition name="fade">
            <router-view @alert="showalert" @modale="model_action"></router-view>
        </transition>

        <ModalEdit @alert="showalert" @close="modale=false;modale_option.comp=''" :option="modale_option" :title="modale_option.title" :comp="modale_option.comp"  :shoed="modale"></ModalEdit>

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
                
                modale : false  , 
                modale_option : {}  , 
                successinfo : null , 
                alerttxt : '' , 
                alerttype : '' , 

            }
        
        },

        computed : {

        },

        methods : {

            model_action : function ( action ) {
                
                this.modale = true ; 
                this.modale_option = Object.assign({} ,action ) ; 

            },

            formathtml : function ( data ) {
                
                return  data.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'); ; 
                
            },

            showalert : function ( txt , type = 'success' ) {
                
                this.successinfo = true ;
                this.alerttxt = this.formathtml( txt ) ; 
                this.alerttype = type ; 

                setTimeout(()=>{
                    this.successinfo = false ; 
                    this.alerttxt = '' ; 
                }, 4000);

            }

        },

        mounted(){

            setTimeout(()=>{
                $('#principale-loader').hide() ; 
            }, 1000);  
        
        },
        
        created(){
            
            this._$on('alert', (e ,type) => this.showalert(e) ) ; 

            this._$on('alertError', (e ,type) => this.showalert(e ,'danger') )
            
            this._$on('alertwarning', (e ,type) => this.showalert(e ,'warning') )

        }

    }
</script>
<style>
    
    body, html {
        width: 100%;
        height: 100%;
        padding: 0;margin: 0;
        overflow: hidden;
    }

    .cont-app{
        
        background-color: #EEEEEE !important ; 
        width: 100%;
        height: 100% ; 
        overflow-x: hidden;
        overflow-y: auto;
        
    }
    
    .padding-start{
        margin-top: 3rem;
    }

    /*
    *   Variate de style du loader 
    */

    .variante .loader-quart:after{
        border-top-color: #3ec1d3;
    }

    .variante .loader-quart {
        border-radius: 50px;
        border: 6px solid rgba(62, 193, 211, 0.22);
    }

</style>