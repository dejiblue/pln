<template>
	<div style="margin-top: 2rem;">

		<loader v-if="loader"></loader>

		<b-container>
			<b-row>
				<b-col sm="4">
					<div class="history-page-content-left history-page-content">
						<div class="list-group list-group-flush" >
							<a @click.prevent.stop="change_focus(item)" v-for="( item , index ) in header" href="#" class="list-group-item" v-bind:class="{ _active : active==item.id }">{{item.domain}} ({{item.nbr}})<em>{{item.email}}</em><em>{{item.date}}</em></a>
						</div>
					</div>
				</b-col>
				<b-col sm="8">
					<div class="history-page-content-leslistar">
					    <div v-for="item in history.data" class="rg">
					        <div class="h">
					            <h4>{{item.domain}}</h4><a @click.prevent.stop="datadownload(item)" href="#">Download CSV</a></div>
					        <div class="c">
					            <div class="l">
					                <h5>cPanel Account</h5>
					                <div>
										<span v-for="(value, key, index) in item" v-if="key!='wordpress'">- {{key}} : {{value}}<br></span>
					                </div>
					            </div>
					            <div class="g" v-if="item.wordpress">
					                <h5>WordPress</h5>
					                <div><span v-for="(value, key, index) in item.wordpress" v-if="key!='wordpress'">- {{key}} : {{value}}<br></span>
					                </div>
					            </div>
					        </div>
					    </div>
					</div>
					<div class="history-page-content-btn"><div style="flex: 1 1 0%;"></div> <button v-if="history.data && history.data.length" type="button" class="btn btn-info" @click.prevent.stop="datadownload( history.data ,true )" >Download All CSV</button></div>
				</b-col>
			</b-row>
		</b-container>
	</div>
</template>

<script>
	
	import arraytocsv from '../lib/arraytocsv';
	import { post , get } from '../lib/api'

	export default {

		props : [], 

		data(){

            return {
       
            	warning : false ,
            	loader : true ,
            	historys : {} ,
            	history : [] ,
            	header : [] ,
            	active : '' ,
            	date : null , 

            }
    
        },

        methods : {

        	change_focus( e ){
        		
        		this.history = this.historys.filter((r) => {
        			return r.id === e.id ; 
        		})[0]

				this.active = e.id; 

				this.date = e.date; 

        	},

        	formathtml : function ( data ) {
                return  data.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'); ;   
            },

        	datadownload(res,multiple){

        		let temp = [];

        		if ( !multiple ) {
        			res = [res]
        		}

        		res = res.map(e => e.data?e.data:e )  ; 

        		for (var i = 0; i < res.length; i++) {
        			let objtemp = {}
        			let keys = Object.keys( res[i] ) ; 
        			for (var a = 0; a < keys.length; a++) {
        				if ( keys[a] != 'wordpress' ) {
        					objtemp[keys[a]]=res[i][keys[a]] ;
        				}else{
        					let keysWP = Object.keys( res[i][keys[a]] ) ; 
        					for (var e = 0; e < keysWP.length; e++) {
        						objtemp['wp '+keysWP[e]]=res[i]['wordpress'][keysWP[e]] ;
        					}
        				}
        			}
        			temp.push( objtemp );
        		}

        		return new arraytocsv({ filename: this.$lang('filename.download',{date:this.date})+".csv" } , temp);  
        	
        	}

        },
		
		created(){ 
			

			let url = window.urlapp+'/history' ;  

            get( url )

                .then((res)=>{
                	
                	this.loader = false ; 

                	let list = [] ; 

                	res.data.success.length>0?list=res.data.success.reverse():list=[] ; 

					this.historys = list ; 

					if ( this.historys.length>0 ) {
						this.history = this.historys[0];
						this.active = this.history.id; 
						this.date = this.history.created_at ; 
					}

					for(let h of this.historys ){

						this.header.push({
							domain :h.data[0].domain,
							email : h.data[0].contactemail,  
							date : h.created_at,   
							id : h.id,   
							nbr : h.data.length,   
						})
					}

                })  

                .catch((err)=>{

                	console.log( err );
                	this.loader = false ; 
                	this.warning = true ; 
                })


		}

	}

</script>

<style>
	
	.history-page-content-leslistar {
	    padding: 10px;
	    width: 100%;
	    height: calc(100% - 30px);
	    padding-top: 0;
	}

	.history-page-content-btn {
	    width: 100%;
	    padding: 10px;
	    display: -moz-flex;
	    display: -ms-flex;
	    display: -o-flex;
	    display: -webkit-box;
	    display: -ms-flexbox;
	    display: flex;
	}
	
	.history-page-content-leslistar .h {
	    width: 100%;
	    height: 45px;
	    line-height: 45px;
	    background-color: hsla(0,0%,78%,.1);
	    padding-left: 10px;
	}

	.history-page-content-leslistar .h h4, a {
	    display: inline-block;
	    vertical-align: middle;
	}
	
	.history-page-content-leslistar .h a {
	    margin-left: 10px;
	}

	.history-page-content-leslistar .c {
	    display: -moz-flex;
	    display: -ms-flex;
	    display: -o-flex;
	    display: -webkit-box;
	    display: -ms-flexbox;
	    display: flex;
	    padding: 10px 15px;
	}

	.history-page-content-leslistar .l {
	    -webkit-box-flex: 1;
	    -ms-flex: 1;
	    flex: 1;
	    width: 50%;
	}
	
	.history-page-content-leslistar .g, .history-page-content-leslistar .l {
	    -webkit-box-flex: 1;
	    -ms-flex: 1;
	    flex: 1;
	    width: 50%;
	}

	.history-page-content .list-group-item._active {
	    background-color: rgba(0,0,0,.04)!important;
	}

	.history-page-content .list-group-item:hover {
	    background-color: hsla(0,0%,100%,.6)!important;
	}

	.history-page-content a.list-group-item {
	    display: block;
	    width: 100%;
	    color: rgba(0,0,0,.9);
	    overflow: hidden;
	    white-space: nowrap;
	    text-overflow: ellipsis;
	}
	
	a.list-group-item:hover, a.list-group-item:focus {
	    text-decoration: none;
	    background-color: #f5f5f5;
	}

	.history-page-content .list-group-item {
	    background: none!important;
	    border-bottom: 1px solid rgba(0,0,0,0.2)!important;
	}

	.history-page-content a.list-group-item em {
	    margin-left: 15px;
	    font-size: 14px;
	    color: rgba(0,0,0,.5);
	}


</style>