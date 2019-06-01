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
					    <b-col sm="4"><label for="name">{{$lang('ca.form.name')}} : </label></b-col>
					    <b-col sm="8">
                            <b-form-group>
                                <b-form-input id="name"
                                        v-model="form.name"
                                        :state="error.name"
                                        @input="changeinput('name')"
                                        :placeholder="$lang('ca.form.namePlace')">
                                </b-form-input>
                                <b-form-invalid-feedback id="ErreurName">
                                    <span vi-if="!txt.name">
                                        {{$lang('ca.form.nameError')}}
                                    </span>
                                    <span vi-if="txt.name">
                                        {{txt.name}}
                                    </span>
                                </b-form-invalid-feedback>
                            </b-form-group>

					    </b-col>
					</b-row>
               
                </b-input-group>

                <b-input-group>

                	<b-row style="width: 100%; margin-top: 1rem;">
					    <b-col sm="4"><label for="color">{{$lang('ca.form.couleur')}} :</label></b-col>
					    <b-col sm="8">
					      	<b-dropdown class="dropdown-color" size="sm">
					  			<template slot="button-content">
									<b-img rounded="circle" blank width="26" height="26" :blank-color="color_value" alt="img" class="m-1" />
					  			</template>
					          	<b-dropdown-item :key="liste.value" @click="color_change(liste.value)" v-for="liste in listes" href="#">
									<b-img rounded="circle" blank width="26" height="26" :blank-color="liste.value" alt="img" class="m-1" />
					          	</b-dropdown-item>
					        </b-dropdown>
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
                    name : null,
                    color : null,
                },

                form: {
                    name : '',
                    color : '',
                },

                txt: {
                    name : '',
                    color : '',
                },

                listes : [
                	{
                		text : '#563d7c' , 
                		value : '#563d7c' , 
                	},
                	{
                		text : '#999999' , 
                		value : '#999999' , 
                	},
                	{
                		text : '#82f31b' , 
                		value : '#82f31b' , 
                	},
                	{
                		text : '#1badf3' , 
                		value : '#1badf3' , 
                	},
                	{
                		text : '#ff5722' , 
                		value : '#ff5722' , 
                	},
                	{
                		text : '#f7f7f7' , 
                		value : '#f7f7f7' , 
                		select : true , 
                	},
                ], 

                color_value : '#563d7c' , 

                id : null , 

            }
    
        },

        methods : {

            changeinput : function ( champ ) {
                if ( this.form[champ] ) {
                    this.error[champ]=null ; 
                }
            },

        	color_change : function ( color ) {
            	this.color_value = color ; 
            },

            submite : function () {
            	
                let url = window.urlapp+'/categorie' ; 

                var formData = new FormData();
                let _methode = '' ;

                if ( this.id ) {
                    url = url + '/' + this.id ; 
                    _methode = 'PUT' ;
                }else{
                    _methode = 'POST' ;
                }

                formData.append("_method", _methode );
                formData.append("token", document.csrf_token);

                formData.append("name", this.form.name);    
                formData.append("color", this.color_value);
            
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

                            if ( this.id ) {
                                this.$emit('alert',this.$lang('ca.update.alert')) ;  
                            }else{
                                this.$emit('alert',this.$lang('ca.new.alert')) ;  
                            }

                            this._$emit('changeca') ;  

                        }
                    
                    })  

                    .catch((err)=>{
                        
                        this.loader = false ; 
                        this.$emit('close') ;  
                        this._$emit('alertError',this.$lang('form.error'));  

                    })

            }

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

            if (this.data) {

                this.form.name = this.data.name ;
                this.id = this.data.id ;
                this.color_value = this.data.color ;
            
            }

		}

	}

</script>

<style>
	

	.ca-editor .dropdown-menu.show {
	    
	    display: flex;
	    max-width: 200px;
	    flex-flow: row wrap;
        padding: 0.51rem;

	}
	
	.ca-editor .dropdown-item {
	    
	    display: block;
        max-width: 34px ; 
	    max-height: 34px ; 
        padding: 0;
        margin: 0;
	}

    .dropdown-color button{
        padding: 0 !important;
        background : none !important;
        border : none !important;
        box-shadow : none !important;
    }

    .dropdown-color .dropdown-toggle::after{
        border-color: #111 ; 
        border-right-color: rgba(0,0,0,0); ; 
        border-left-color: rgba(0,0,0,0); ; 
    }

</style>