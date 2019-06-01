<template>
	<div class="list-ctn">
		<b-card >
			
			<div class="header-lst" slot="header">	
            	<h6 class="h mb-0">{{$lang('cat.title')}} </h6>
            	<span v-show="total"> {{$lang('cat.info',{total:this.total})}}</span>
            	<span class="s"></span>
            	<b-button @click="$emit('newca')">+</b-button>
			</div>

			<div v-if="loader" style="background-color: rgba(0, 0, 0, 0.16); z-index: 1000 ;position: absolute;top: 0;left: 0;right: 0;bottom: 0;"><loader></loader></div>

			<b-alert v-if="warning" show variant="warning">
				{{$lang('cat.load.warning')}}
			</b-alert>

			<b-list-group v-if="!warning">
			  	
			  	<b-list-group-item v-bind:key="key" v-for="(categorie, key, index) in categories" @mouseenter="mouseenter(key)" @mouseleave="mouseleave" class="d-flex justify-content-between  align-items-center" button>
			  		<span>
			  			<b-img rounded="circle" blank width="20" height="20" :blank-color="categorie.color" alt="img" class="m-1" />
						<span>
							<span>{{categorie.name}}</span>
						</span>
			  		</span>
			  		<span v-show="hovered==key&&(hovered||hovered===0)">
			  			<b-badge @click="$emit('edit',categorie)" pill>
							<img :src="'./img/editmini.png'" alt="edit mini" width="5" height="auto" >
				  		</b-badge>
				  		<b-badge variant="danger" @click="del(categorie)" pill>x</b-badge>
			  		</span>
			  	</b-list-group-item>

			</b-list-group>

			
            
        </b-card>
	</div>
</template>

<script>

    import { get, post } from '../lib/api'

	export default {

		props : [], 

		data(){

            return {
            	
            	total : null , 

            	loader : true ,

            	categories : [] ,

            	hovered : null ,   

            	warning : false , 
            }
    
        },

        methods : {

        	mouseenter : function ( index ) {
        		this.hovered = index ; 
        	},

        	mouseleave : function () {
        		this.hovered = null ; 
        	},

        	loadca : function () {
        		
        		let url = window.urlapp+'/categorie' ;  

                get( url )

                    .then((res)=>{

                  		this.loader = false ; 
                    	if ( res.data && res.data.success ) {
                  			this.total = res.data.success.length;
                  			return this.categories = res.data.success ; 
                  		}

                  		this.warning = true ; 

                    })  

                    .catch((err)=>{
                  		this.loader = false ; 
                  		this.warning = true ; 
                    })

        	}, 

        	del : function ( categori ) {
        		
        		let r = confirm(this.$lang('cat.delete.conf', categori ));

				if (r == true) {
					
					let url = window.urlapp+'/categorie/' + categori.id  ; 

	                var formData = new FormData();

	                formData.append("_method", 'delete' );
	                formData.append("token", document.csrf_token);
	            
	                this.loader = true ; 

	                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

	                    .then((res)=>{

	                        this.loader = false ; 
	                        if ( res.data.success ) {
	                        	this._$emit('alert',this.$lang('cat.delete.alert', categori )); 
				                this.loader = true ; 
								return setTimeout( e => this.loadca(), 1000 );
	                        }
	                        this._$emit('alertError',this.$lang('cat.delete.alert.erreur', categori )); 
	                    
	                    })  

	                    .catch((err)=>{
	                        this.loader = false ; 
	                        this._$emit('alertError',this.$lang('cat.delete.alert.erreur', categori ));  
	                    })

				} 

        	}

        },

        computed : {

        	 
        },
		
		created(){ 
			
			this.loadca() ; 

			this._$on('changeca',()=>{

                this.loader = true ; 
				setTimeout( e => this.loadca(), 1000 );

			})

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

	.list-ctn .badge{
		width: 1.45rem;
		height: 1.45rem;
		cursor: pointer !important;
	}

	.card-body{
		position: relative;
		min-height: 300px;	
	}

</style>