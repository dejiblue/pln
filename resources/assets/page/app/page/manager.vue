<template>
	<div class="site-manager padding-start">

		<div v-if="loader" style="position: absolute;top: 0;left: 0;right: 0;bottom: 0;"><loader style="z-index: 1000;"></loader></div>

		<input multiple="multiple" @change="change_liste_file" type="file" id="uploadfile" style="position: absolute;top: -6000px; left: -8000px;">
		
		<b-container>

			<b-row>
				<b-col md="3" class="my-1">
	  				<div>

	  					<b-btn v-show="view=='widget'" id="exPopoverReactive1"
				            variant="primary"
				            ref="button">
				        	{{$lang('manager.table.btn.widget.title')}}
				      	</b-btn>
						<b-popover target="exPopoverReactive1"
					               triggers="click"
					               placement="bottom"
					               :show.sync="popoverShow"
					               ref="popover">
						    <template slot="title">
						        <b-btn @click="popoverShow = false ; widgeteditable=''" class="close" aria-label="Close">
						          <span class="d-inline-block" aria-hidden="true">&times;</span>
						        </b-btn>
						        {{$lang('manager.table.btn.widget.popover')}}
						    </template>
						    <div>
						        <b-form-textarea 
				                    placeholder="Enter something"
				                    v-model="widgeteditable"
				                    :rows="3"
				                    :max-rows="6">
				    			</b-form-textarea>
				    			<b-btn @click="popoverShow=false;widgeteditable=''" size="sm" variant="danger">Cancel</b-btn>
						        <b-btn @click="popoverShow=false;" size="sm" variant="primary">Ok</b-btn>
						    </div>
					    </b-popover>
					</div>
				</b-col>

				<b-col md="9" class="my-1">
					<b-button style="float: right;" :size="'sm'"  @click="view=='widget'?view='site':view='widget'">{{widgetbtn}}</b-button>
				</b-col>
			</b-row>

        	<b-row>
        		<b-col cols="12">
					<b-table :fields="fields" :items="sites">

						<template slot="HEAD_select" slot-scope="data">
						    <div class="manage-header">
						    	<div class="header">

						    	</div>
						    	<div @click="selectall" class="manage-header-action">
						    		<b-form-checkbox
				                    	v-model="allselect"></b-form-checkbox>
						    	</div>
						    </div>
						</template>

						<template slot="HEAD_url" slot-scope="data">
						    <div @click.prevent.stop="''" class="manage-header">
						    	<div class="header">
						    		<strong>{{data.label}}</strong>
						    	</div>
						    	<div class="manage-header-action">
						    		<b-form-input  
					                  	type="text"
					                  	v-model="urlfilter"
					                  	:placeholder="$lang('manager.table.search')">  	
					                </b-form-input>
						    	</div>
						    </div>
						</template>

						<template slot="HEAD_categorie" slot-scope="data">
						    <div @click.prevent.stop="''"  class="manage-header">
						    	<div class="header">
						    		<strong>{{data.label}}</strong>
						    	</div>
						    	<div class="manage-header-action">
						    		<b-form-select v-model="ca_selected" :options="ca_options" class="mb-3" />
						    	</div>
						    </div>
						</template>

						<template slot="HEAD_login" slot-scope="data">
						    <div class="manage-header">
						    	<div class="header">
						    		<strong>{{data.label}}</strong>
						    	</div>
						    	<div class="manage-header-action">
						    		
						    	</div>
						    </div>
						</template>

						<template v-if="view=='site'" slot="HEAD_upload" slot-scope="data">
				            <div class="manage-header">
						    	<div class="header">

						    	</div>
						    	<div class="manage-header-action">
						    		<b-button :disabled="is_selected_site" @click="uploadfilecreate(true)" size="sm" >
						                {{data.label}} ({{files.length}})
						            </b-button>
						    	</div>
						    </div>
						</template>

						<template v-if="view=='site'" slot="HEAD_publier" slot-scope="data">
						    <div class="manage-header">
						    	<div class="header">

						    	</div>
						    	<div class="manage-header-action">
						    		<b-button @click="publier_post(true)" :disabled="files.length == 0" size="sm" >
						                {{data.label}}
						            </b-button>
						    	</div>
						    </div>
						</template>

						<template v-if="view=='site'" slot="HEAD_edit" slot-scope="data">
						    <div class="manage-header">
						    	<div class="header">
						    		<strong>{{data.label}}</strong>
						    	</div>
						    	<div class="manage-header-action">
						    		
						    	</div>
						    </div>
						</template>
						
						<template v-if="view=='widget'" slot="HEAD_widget" slot-scope="data">
						    <div class="manage-header">
						    	<div class="header">

						    	</div>
						    	<div class="manage-header-action">
						    		<b-button @click="publish_textarea(true)" :disabled="is_selected_site||!widgeteditable" size="sm" >
						                {{data.label}}
						            </b-button>
						    	</div>
						    </div>
						</template>
			
						<template slot="categorie" slot-scope="row">
							<span class="categorie-info">
								<span class="color" :style="{backgroundColor:row.item.categorie.color}"></span>
								<span class="txt">{{ row.item.categorie.name }} </span>
							</span>
						</template>

						<template slot="select" slot-scope="row">
							<span class="select-info">
								<b-form-checkbox
									@input="change( row.item.id , $event )"
			                    	v-model="rowselect[row.item.id]">
								</b-form-checkbox>
							</span>
						</template>

						<template slot="login" slot-scope="row">
							<span class="login-info">
								<a href="#" @click.prevent.stop="connection_website( row.item.id )" class="link">{{$lang('manager.table.btn.connection')}}</a>
							</span>
						</template>

						<template v-if="view=='site'" slot="upload" slot-scope="row">
							<b-button  @click="uploadfilecreate(false, row.item.id)" size="sm" >
				                {{$lang('manager.table.btn.upload')}}<span v-if="files.length&&focussite==row.item.id">({{files.length}})</span>
				            </b-button>
						</template>

						<template  v-if="view=='site'" slot="publier" slot-scope="row">
							<b-button size="sm" :disabled="files.length == 0" @click="publier_post(false , row.item.id )"  >
				                {{$lang('manager.table.btn.publish')}}
				            </b-button>
						</template>

						<template v-if="view=='site'" slot="edit" slot-scope="row">
							<b-button @click="deletewebsite" variant="danger" size="sm" >
				                {{$lang('manager.table.btn.delete')}}
				            </b-button>
						</template>

						<template v-if="view=='widget'" slot="widget" slot-scope="row">
							<b-button size="sm" @click="publish_textarea(false , row.item.id )" :disabled="!widgeteditable" >
				                {{$lang('manager.table.btn.publishb')}}
				            </b-button>
						</template>

					</b-table>
        		</b-col>
        	</b-row>

        	<b-row>
        		<b-col>
        			<b-button @click="deletewebsite" :disabled="is_selected_site" variant="danger" style="float: left;" :size="'sm'">{{$lang('manager.table.btn.deleteall')}}</b-button>
        		</b-col>
        	</b-row>
        </b-container>
	</div>
</template>

<script>
	
	import { post , get } from '../lib/api'

	export default {

		props : [], 

		data(){

            return {

            	view : 'site' , 

            	allselect : '' , 

            	filterage : '' ,

            	loader : true , 

            	totalRows : 0 , 

            	rowselect : {}, 

            	fields: [

			        { key: 'select', label: this.$lang('manager.table.select') },
			        { key: 'url', label: this.$lang('manager.table.url') , sortable: true, sortDirection: 'desc' },
			        { key: 'categorie', label: this.$lang('manager.table.categorie') , sortable: true },
			        { key: 'login', label: this.$lang('manager.table.login') },

			        { key: 'upload', label: this.$lang('manager.table.upload') },
			        { key: 'publier', label: this.$lang('manager.table.publier') },
			        { key: 'edit', label: this.$lang('manager.table.edit') },

			        { key: 'widget', label: this.$lang('manager.table.widget') },

			    ],

			    siteListe : [] , 

			    ca_selected: '',

			    categories : [] , 

			    files :  [] ,

			    log_send_post : {errors:[],success:[]} , 

			    focussite : null , 

			    widgeteditable : '' , 

			    popoverShow : false  , 

			    //filtre par l'URL 
			    urlfilter : '' , 

            }
    
        },

        methods : {

        	deletewebsite : function () {
        		
        		alert('Je supprime ce site') ; 

        	},

        	publier_post : function ( e , index ) {

        		let selected ,key , selectedObj ; 

        		if ( e ) {
        		
	        		selected = 0 ; 
	        		key = Object.keys( this.rowselect ) ; 
	        		selectedObj = [] ; 

	        		key.forEach((e)=>{
	        			if ( this.rowselect[e] ) {
	        				selected++  ; 
	        				selectedObj.push( parseInt(e) ) ; 
	        			}
	        		})
        		
        		}else{
        			selected = 0 ; 
        			selectedObj = [ index ] ; 
        		}


        		if ( this.files.length < selected ) {
                    this._$emit('alertError',this.$lang('manager.bulkpost.error.nombre'));  
        			return	
        		}

        		let datas = [] ;

        		this.siteListe.forEach((e)=>{
        			if (selectedObj.indexOf(e.id) !== -1 ) {
        				datas.push({usr:e.cpusername,serv:e.serveur_id})
        			}
        		})

        		this.loader = true

        		this.sendRequest( datas , 0 ) ; 

        	},


        	connection_website  : function ( site_id ) {
        	
        		this.siteListe.forEach((e)=>{
        			if ( e.id == site_id ) {
        				console.log(  e.serveur );
						window.open( window.urlapp+'/wp/session/' + e.cpusername + "/" + e.serveur.id );
        			}
        		})

        	},

        	publish_textarea : function ( e , index ) {

        		this.popoverShow = false ; 
        		
        		let selected ,key , selectedObj ; 

        		if ( e ) {
        		
	        		selected = 0 ; 
	        		key = Object.keys( this.rowselect ) ; 
	        		selectedObj = [] ; 

	        		key.forEach((e)=>{
	        			if ( this.rowselect[e] ) {
	        				selected++  ; 
	        				selectedObj.push( parseInt(e) ) ; 
	        			}
	        		})
        		
        		}else{
        			selected = 0 ; 
        			selectedObj = [ index ] ; 
        		}

        		if ( selectedObj.length == 0 ) {
        			this._$emit('alertError',this.$lang('manager.widget.error'));  
        			return	
        		}

        		let datas = [] ;

        		this.siteListe.forEach((e)=>{
        			if (selectedObj.indexOf(e.id) !== -1 ) {
        				datas.push({usr:e.cpusername,serv:e.serveur_id})
        			}
        		})

        		this.loader = true

        		this.sendRequestWidget( datas , 0 ) ; 

        	},

        	sendRequestWidget : function ( datas , i ) {
        	
        		var formData = new FormData();

				formData.append("widget", this.widgeteditable);
				formData.append("username", datas[i].usr );
				formData.append("serveur_id", datas[i].serv );

				let url = window.urlapp+'/wp/widget'  ;

				return post( url , formData , { headers: { 'Content-Type': 'multipart/form-data' }} )
				
				    .then(( res )=>{

				    	if ( res.data&&res.data.success ) {
				    		this.log_send_post.success.push( datas[i].usr ) 
				    	}else{
				    		this.log_send_post.errors.push( datas[i].usr ) 
				    	}

				        if ( i < datas.length-1 ) {
				         	this.sendRequestWidget( datas , i +1 ) ; 
				        }else{
				        	this.terminateWidget() ; 
				        }

				    })
				
				    .catch(( res )=>{
				    	
				    	this.log_send_post.errors.push( datas[i].usr ) 

				        if ( i < datas.length-1 ) {
				         	this.sendRequestWidget( datas , i +1 ) ; 
				        }else{
				        	this.terminateWidget() ; 
				        }

				    }) ; 

        	},

        	terminateWidget : function () {
        		
        		if ( this.log_send_post.errors.length > 0 && this.log_send_post.success.length == 0 ) {
                    this._$emit('alertError',this.$lang('manager.widget.error.create'));  
        		}else if ( this.log_send_post.errors.length == 0 && this.log_send_post.success.length > 0 ) {
                    this._$emit('alert',this.$lang('manager.widget.success.create'));  
        		}else if ( this.log_send_post.errors.length > 0 && this.log_send_post.success.length > 0 ) {
                    this._$emit('alertwarning',this.$lang('manager.widget.warning.create',{success:this.log_send_post.success.length,error:this.log_send_post.errors.length }));  
        		}

        		this.files = [] ; 

        		let key = Object.keys( this.rowselect ) ; 
    			key.forEach((e)=>{
        			this.rowselect[e] = false ; 
        		})

        		this.log_send_post.errors = [] ; 
        		this.log_send_post.success = [] ; 

        		this.loader = false; 

        		this.widgeteditable = '' ; 

        	},

        	terminatePost : function ( ) {
        		
        		if ( this.log_send_post.errors.length > 0 && this.log_send_post.success.length == 0 ) {
                    this._$emit('alertError',this.$lang('manager.bulkpost.error.create'));  
        		}else if ( this.log_send_post.errors.length == 0 && this.log_send_post.success.length > 0 ) {
                    this._$emit('alert',this.$lang('manager.bulkpost.success.create'));  
        		}else if ( this.log_send_post.errors.length > 0 && this.log_send_post.success.length > 0 ) {
                    this._$emit('alertwarning',this.$lang('manager.bulkpost.warning.create',{success:this.log_send_post.success.length,error:this.log_send_post.errors.length }));  
        		}

        		this.files = [] ; 

        		let key = Object.keys( this.rowselect ) ; 
    			key.forEach((e)=>{
        			this.rowselect[e] = false ; 
        		})

        		this.log_send_post.errors = [] ; 
        		this.log_send_post.success = [] ; 

        		this.loader = false; 

        	},

        	sendRequest : function ( datas , i ) {

        		var formData = new FormData();

				formData.append("file", this.files[i]);
				formData.append("username", datas[i].usr );
				formData.append("serveur_id", datas[i].serv );

				let url = window.urlapp+'/wp/post'  ;

				return post( url , formData , { headers: { 'Content-Type': 'multipart/form-data' }} )
				
				    .then(( res )=>{
				    	
				    	if ( res.data&&res.data.success ) {
				    		this.log_send_post.success.push( datas[i].usr ) 
				    	}else{
				    		this.log_send_post.errors.push( datas[i].usr ) 
				    	}

				        if ( i < datas.length-1 ) {
				         	this.sendRequest( datas , i +1 ) ; 
				        }else{
				        	this.terminatePost() ; 
				        }

				    })
				
				    .catch(( res )=>{
				    	
				    	this.log_send_post.errors.push( datas[i].usr ) 

				        if ( i < datas.length-1 ) {
				         	this.sendRequest( datas , i +1 ) ; 
				        }else{
				        	this.terminatePost() ; 
				        }

				    }) ; 

        	},

        	change_liste_file : function ( e ) {
        		
        		this.files = e.target.files ; 

        	},

        	uploadfilecreate: function ( bulk , focussite ) {

        		if ( !bulk ) {
        			this.focussite = focussite ; 
        		}
        		
        		$('#uploadfile').trigger('click') ; 

        	},

        	change : function ( id ) {

        		let key = Object.keys( this.rowselect ) ; 
        		
       			let noncoched = false ; 
        		key.forEach((e)=>{
        			if ( ! this.rowselect[e] ) {
        				noncoched = true ; 
        			}
        		})

        		if ( noncoched ) {
        			return this.allselect = false ; 
        		}
        		return this.allselect = true ; 

        	},

        	changeall: function () {

        		let value = false; 
        		if (this.allselect) {
        			value = true ; 
        		}

        		let key = Object.keys( this.rowselect ) ; 
    			key.forEach((e)=>{
        			this.rowselect[e] = value ; 
        		})

        	},

        	status : function () {
        		console.log('--- data');
        	},

        	selectall : function () {
        		
        		if ( this.allselect ) {
        			this.allselect = false
        			this.changeall()
        			return  ; 
        		}
        		this.allselect = true
        		this.changeall()
        		return  ; 

        	},

        	loadcaliste : function ( ) {

        		let url = window.urlapp+'/categorie' ;  

                get( url )

                    .then((res)=>{
                    	if ( res.data && res.data.success ) {
                  			return this.categories = res.data.success ; 
                  		}

                    })  

                    .catch((err)=>{
                  		this.categories = [] ; 
                    })

        	},

        	loadssiteliste : function ( ) {

        		let url = window.urlapp+'/site' ;  

                get( url )

                    .then((res)=>{
                    	
                    	this.loadcaliste() ; 
                  		this.loader = false ; 
                    	if ( res.data && res.data.success ) {

                  			this.totalRows = res.data.success.length ;

                  			let rowselect = {} ; 
                  				
                  			res.data.success.map((e)=>{
                  				rowselect[e.id] = false ; 
                  			})

                  			this.rowselect = Object.assign({} , rowselect ) ; 

                  			return this.siteListe = res.data.success ; 
                  			

                  		}

                    })  

                    .catch((err)=>{
                    	this.loadcaliste() ; 
                  		this.loader = false ; 
                    })

        	}

        },

        computed : {

        	sites : function () {
        		
        		return this.siteListe.filter((e)=>{
        			return (e.url.indexOf( this.urlfilter )!==-1) && (this.ca_selected?e.categorie.id==this.ca_selected:true) ; 
        		}); 


        	},

        	ca_options : function () {

        		let map = this.categories.map((e)=>{
        			return { value: e.id, text: e.name } 
        		})

        		map.unshift({ value: '', text: this.$lang('manager.table.categorie.all') })
        		
        		return map ; 

        	}, 

        	is_selected_site : function () {
        		

        		let key = Object.keys( this.rowselect ) ; 
        		
       			let noncoched = true ; 
        		key.forEach((e)=>{
        			if ( this.rowselect[e] ) {
        				noncoched = false ; 
        			}
        		})

        		return noncoched ; 

        	},

        	widgetbtn : function (argument) {
        		
        		if (this.view =='widget') {
        			return 'Site manager' ; 
        		}else{
        			return 'widget manager' ; 
        		}

        	}

        },
		
		created(){ 

			this.loadssiteliste() ; 

		}

	}

</script>

<style>
	
	option:after {
	    content: " ";
	    height: 5px;
	    width: 5px;
	    background: #c00;
	    border-radius: 5px;
	    display: inline-block;
	}
	

	.cont-app .site-manager  .form-control {
	    color: #495057;
	    background-color: #ffffff !important;
	    border: 1px solid rgba(0,0,0,0);
	}

	.cont-app .site-manager .mb-3, .cont-app .site-manager .my-3{
		margin: 0 !important;
	}

	.manage-header{
		height: 5rem;
	}

	.manage-header .header{
		height: 3em;
	}

	.manage-header-action{
		height: calc(2rem) ; 
	}
	
	.categorie-info .color{
		display: inline-block;
		vertical-align: middle;
		width: 16px;
		height: 16px;
		border-radius: 16px;
		background-color: red ; 
	}

	.categorie-info .txt{
		display: inline-block;
		vertical-align: middle;	
	}

</style>