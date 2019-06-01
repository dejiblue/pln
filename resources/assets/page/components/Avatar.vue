<template>
	<div>
		<div class="avatar-content">
			<b-img id="image-loaded" v-bind:class="{flou:hover}" @mouseenter="mouseenter" @mouseleave="mouseleave" :src="src?src:'./img/bande1.png'" alt="avatar" rounded="circle" class="avatar-show" />
			<div @click="selectfile" class="avatar-cache-over" @mouseenter="mouseenter" @mouseleave="mouseleave" v-show="hover">
				<span>{{$lang('avatar.button')}}</span>
				<input 
					type="file"
			       	id="avatar" 
			       	name="avatar"
			       	accept="image/png, image/jpeg"
			       	@change="change_avatar"
			       	style="position: absolute;top: -6000px; left: -60000px;">
			</div>
			<div id="image-loaded" v-bind:class="{flou:hover}" @mouseenter="mouseenter" @mouseleave="mouseleave" v-if="loader" style="position: absolute;top: 0;left: 0;right: 0;bottom: 0;"><loader style="z-index: 1000;"></loader></div>
		</div>
		<span class="error-avatar" v-if="error_select||error===false">
			<span v-if="!txterror">{{$lang('avatar.error_size')}}</span>
			<span v-if="txterror">{{txterror}}</span>
		</span>
	</div>
</template>

<script>

	export default {

		props : ['placeholder','steeploader','unchange','error','txterror','src'], 

		data(){

            return {
            	
            	hover :false ,

            	avatar :null , 

            	loader :false , 

            	error_select :false , 
            
            }
    
        },

        methods : {


        	mouseenter : function (e) {

        		if (this.unchange) {
        		
        		}else{
        			this.hover = true ; 
        		}
        		
        	},

        	mouseleave : function (e) {
        		this.hover = false ; 
        	},

        	selectfile : function (e) {
        		
        		let el = $( this.$el ).find( 'input' ) ; 
        		el.trigger('click') ; 

        	},

        	change_avatar : function ( e ) {

        		this.$emit('reset') ; 
        		
        		if (e.target.files.length>0) {
        			
        			if ( e.target.files[0].size > 1000000 ) {
        				this.error_select = true ;
	        			return
        			}

        			this.error_select = false ;
        			this.loader = true;

        			setTimeout(()=>{
        				this.previewFile( e.target.files[0] ) ; 
        			 	this.$emit('avatar',e.target.files[0]) ; 
        			}, 600);
        		
        		}

        	},

        	previewFile : function ( file ) {

				let preview = document.querySelector('#image-loaded');
				let reader  = new FileReader();

			  	reader.onloadend = () => {
			    	
			    	preview.src = reader.result;
			  		
			  		setTimeout(()=>{
        				this.loader = false;
        			}, 1000);

			  	}

			  	if (file) {
			    	reader.readAsDataURL(file);
			  	} else {
			    	preview.src = "";
			  	}

			}

        },
		
		created(){ 


		}

	}

</script>

<style>
	
	.avatar-content{
		
		width: 100%;
		height: auto;
		max-width: 185px;
		margin-left: auto;
		margin-right: auto;
		border-radius: 50% ; 
		overflow: hidden;
		position: relative;
		max-height: 185px;

	}

	.avatar-show{
		
		width: 100%;
		height: 100%;

	}

	.flou{

		/* Safari */
		-webkit-filter: blur(2px);
		/* Firefox */
		-moz-filter: blur(2px);
		/* Opera */
		-o-filter: blur(2px);
		/* Internet Explorer */
		-ms-filter: blur(2px);
		/* Filtre de floutage CSS3 standard */
		filter: blur(2px);
		
	}

	.avatar-cache-over{
		
		position: absolute;
		background-color: rgba(69, 86, 102, 0.7) ; 
		top: 0;left: 0;right: 0;bottom: 0;
		cursor: pointer;
		text-align: center;
		
	}


	.avatar-cache-over span{
		color: #FFF ; 
		border: 1px solid #FFF ; 
		position: relative;
		top: 50%;
		transform: translateY(-50%);
		padding: 0.7rem 1.6rem;
	}
	

	#image-loaded{
		
		max-height: 185px;
		max-width: 185px;

	}

	.error-avatar{
		display: block;
		width: 100%;
		margin-top: 20px;
		color: red ; 
	}

</style>