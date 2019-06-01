<template>
	<div style="width: 100%;">
		<span v-bind:style="{borderColor:color}" @click="clickbtn" class="prebtnuploadfile">
			<span style="font-weight: 500; color: rgba(0,0,0,0.7) ;">{{placeholder}}</span>
			<span class="fileinfo" style="padding-left: 6px; color: rgba(0,0,0,0.5);">{{nbr}} files upload <a v-if="nbr>0" href="#" style="color: red;" @click.prevent.stop="remove" class="close-file"> <- clear</a></span>
		</span>
		
		<input style="position: absolute; top: -10000px; left: -8000px;" type="file" @change="filechange" multiple="multiple" id="file" class="fileinput" >
	</div>
</template>

<script>

	export default {

		props : ['placeholder','error'], 

		data(){

            return {
            	file : null , 
            	nbr : 0 , 
            }
    
        },

        methods : {

        	filechange( e ){
        		
        		this.nbr = e.target.files.length ; 
        		this.$emit( 'file' , e.target.files );
        	
        	},

        	clickbtn(){

        		let input = $(this.$el).find( "input[type='file']" );
        		input.trigger( "click" );

        	},

        	remove(){

        		this.nbr = 0 ; 
        		this.$emit( 'file' , [] );

        	}

        },

        computed : {

        	color : function () {
        		
        		if ( this.nbr == 0 ) {
        			return '#b3b3b3' ;
        		}else if ( this.nbr != 0 && this.error ) {
        			return  '#ff2a47'
        		}
        		return '#3ec1d3' ;

        	}

        },
		
		created(){ 

		}

	}

</script>

<style>

	.tableaux_liste{
		width: 120px;
	}
	
	.tableaux_item{
		width: 250px;
		min-width: 250px ; 
		max-width: 250px ; 
	}

	.tableaux_item span{
		display: block;
		width: 100%;
		overflow: hidden;
		white-space: nowrap;
		text-overflow:ellipsis;
	}
	
	.close-file{
		margin-left: 15px;
	}

	.close-file:hover{
		color: #007bff ; 
	}

	.prebtnuploadfile{
		display: block;
		text-align: center;
		width: 100%;
		height: 35px;
		border: 1px dashed #b3b3b3 ; 
		cursor: pointer;
	    line-height: 31px;
    	border-radius: 4px;
	}

	.prebtnuploadfile:hover{
		background-color: rgba(255,255,255,0.8);
	}

</style>