<template>
	<div class="padding-start">
		<b-container style="margin-top: 30px;">
			<b-row>
				<b-col md="3" class="my-1">
					<b-form-group class="mb-0">
				        <b-input-group>
				            <b-form-input class="with" v-model="filter" :placeholder="$lang('serv.table.search')" />
				        </b-input-group>
				    </b-form-group>
				</b-col>

				<b-col md="9" class="my-1">
	  				<b-button style="float: right;" @click="newserveur" class="btn-app" variant="primary" :size="'sm'" >{{$lang('serv.btn.newserveur')}}</b-button>
				</b-col>
			</b-row>
			<b-row>
				<b-col md="12" class="my-1">

					<b-table 
							style="position: relative;"
							show-empty    
							hover 
				            :items="serveurs"
				            :fields="fields"
				            :empty-text="$lang('serv.liste.empty')"
				            :empty-filtered-text="$lang('serv.liste.empty.filter')"
				            :current-page="currentPage"
				            :per-page="perPage">
							
				      	<template slot="index" slot-scope="row">{{row.index + 1 }} </template>
				      	<template slot="name" slot-scope="row">{{row.item.name}}</template>
				      	<template slot="active" slot-scope="row"><span class="indique" v-bind:class="{active:row.item.plnactive=='1'}">
				      		<span>{{row.item.plnactive=='1'?'Yes :)':'No :('}}</span>
				      		<span v-if="loadercheck==row.item.id" class="indique-loader-content"><loader style="z-index: 1000;"></loader></span>
				      	</span></template>
				      	<template slot="actions" slot-scope="row">
					        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
					        <b-button style=" margin-top: -0.2rem; " variant="link" size="sm" @click.stop="check(row.item, row.index, $event.target)">
					          	Check
					        </b-button>
					        <b-button style=" margin-top: -0.2rem; " variant="primary" size="sm" @click.stop="info(row.item, row.index, $event.target)">
					          	Edit
					        </b-button>
					        
							<b-button id="exPopoverReactive1" @click="popoverShow = false ; addipsserveur=''" style=" margin-top: -0.2rem; " variant="primary" size="sm">
					          	ADD ips
					        </b-button>
					        <b-popover target="exPopoverReactive1"
					               triggers="click"
					               placement="bottom"
					               :show.sync="popoverShow"
					               ref="popover">
							    <div>
							        <b-form-textarea 
					                    placeholder="Enter something"
					                    v-model="addipsserveur"
					                    :rows="3"
					                    :max-rows="6">
					    			</b-form-textarea>
					    			<hr/>
					    			<b-btn @click.stop="popoverShow=false; addips(row.item, row.index, $event.target)" size="sm" variant="primary">ADD ips</b-btn>
							    </div>
						    </b-popover>


				      	</template>
				    </b-table>

				    <span v-if="loader" class="variante indique-loader-content"><loader style="z-index: 1000;"></loader></span>

				</b-col>
			</b-row>
			
			<b-row v-show="(totalRows / perPage) > 1" >
		      	<b-col md="6" class="my-1">
		        	<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
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
			    
			    fields: [
			        { key: 'index', label: '' },
			        { key: 'name', label: this.$lang('serv.table.name'), sortable: true, sortDirection: 'desc' },
			        { key: 'url', label: this.$lang('serv.table.url') , sortable: true },
			        { key: 'username', label: this.$lang('serv.table.username') },
			        { key: 'accesstoken', label: this.$lang('serv.table.password') },
			        { key: 'port', label: this.$lang('serv.table.port') },
			        { key: 'active', label: this.$lang('serv.table.status') },
			        { key: 'actions', label: this.$lang('serv.table.action') }
			    ],

			    currentPage: 1,
      			
      			perPage: 10,

      			serveurListe : [] , 

      			totalRows: 0 ,

      			filter : '' , 

      			loader : true , 

      			loadercheck : null  , 

      			addipsserveur : '' , 

      			popoverShow : false , 

            }
    
        },

        computed :{

        	serveurs : function () {
        		
        		return this.serveurListe.filter((e)=>{
        			if ( e.url.indexOf( this.filter )!==-1 ) {
        				return true ; 
        			}else if ( e.name.indexOf( this.filter )!==-1 ) {
        				return true ; 
        			}
        			return false  ; 
        		}) ; 

        	}

        }, 

        methods : {

        	newserveur : function ( ) {
        		
        		this.$emit('modale', Object.assign({}, {comp:'ServeurEdit',title: this.$lang('serv.form.title'),btn:{ submit:this.$lang('serv.form.btn'), cancel : this.$lang('serv.form.btn.cancel')}} ) ) 

        	},

        	onFiltered : function (e,i) {
		    
		      	console.log( e,i );
		      	return true ; 
		    
		    },


        	info: function ( data ) {
        		
        		this.$emit('modale', Object.assign({}, {data} , {comp:'ServeurEdit',title:this.$lang('serv.form.update.title'),btn:{ submit:this.$lang('serv.form.update.btn'), cancel : this.$lang('serv.form.update.btn.cancel')}} ) ) 

        	},

        	check : function ( item ) {
        		
        		let url = window.urlapp+'/check' ; 

                var formData = new FormData();

                formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');
                formData.append("serveur_id", item.id);

                this.loadercheck = item.id ;

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{
                    	this.loadercheck = null; 
                		this.loader = null ; 
						this.loadserveurliste() ; 
                    })  

                    .catch((err)=>{ this.loader = null ; this.loadercheck = null;  })

        	},

        	addips : function ( item ) {


        		let url = window.urlapp+'/serveur/add/ips' ; 

                var formData = new FormData();

                formData.append("token", document.csrf_token);
                formData.append("_methode", 'POST');
                formData.append("serveur_id", item.id);
                formData.append("ips", this.addipsserveur );

                this.loadercheck = item.id ;

                post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

                    .then((res)=>{
                    	this.loadercheck = null; 
                		this.loader = null ; 
						this.loadserveurliste() ; 
                    })  

                    .catch((err)=>{ this.loader = null ; this.loadercheck = null;  })

        	},

        	loadserveurliste : function ( ) {

        		this.loader = true ; 

        		let url = window.urlapp+'/serveur' ;  

                get( url )

                    .then((res)=>{

                  		this.loader = false ; 
                    	if ( res.data && res.data.success ) {
                  			this.totalRows = res.data.success.length ;
                  			return this.serveurListe = res.data.success ; 
                  		}

                  		this.warning = true ; 

                    })  

                    .catch((err)=>{
                  		this.loader = false ; 
                  		this.warning = true ; 
                    })

        	}

        },
		
		created(){ 

			this.loadserveurliste() ; 
			
			this._$on('changeserveur',()=>{
                
                this.loader = true ; 
				setTimeout( e => this.loadserveurliste(), 1000 );
				
			}) ; 

		}

	}

</script>

<style>
	
	.indique.active{
		background-color: #25c722 ; 
	}

	.indique{
		padding: 0.25rem 1rem;
		background-color: #ff5f5f ; 
		position: relative;
	}

	.indique .loader {
	    width: 25px;
	    height: 25px;
	}

	.indique-loader-content{
		position: absolute;
		top: 0;left: 0;right: 0;bottom: 0;
	}

	.table-hover tbody tr:hover {
	    background-color: rgba(255, 255, 255, 0.6);
	}

	
	
</style>