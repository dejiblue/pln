<template>
	<div class="header-profile">
		<b-row>

			<!--<b-col xl="3" lg="3" md="4" sm="12">
    			<Avatar :unchange="true" :src="user.avatar" ></Avatar>
    		</b-col>-->

    		<b-col>
    			<div class="profile-form">
    				
    				<b-row>

	    				<b-col sm="12">
	    					<h3>{{user.name + ' ' + user.forname}}</h3>
	    				</b-col>

	    				<b-col sm="12" style="margin-top: 0.3rem;">	
	    					<span>{{user.email}}</span>
	    				</b-col>
						
						<b-col sm="12" style="margin-top: 1rem;">
	    					<b-button @click="$emit('edit')" style="flex: left;" variant="primary">{{$lang('profile.view.edit')}}</b-button>
	    				</b-col>

					</b-row>

    			</div>
    		</b-col>

    	</b-row>
	</div>
</template>

<script>

	export default {

		props : [], 

		data(){

            return {
            	
            	user : {},

            }
    
        },

        methods : {

        },

        computed : {

        	 
        },
		
		created(){ 
			

			this.user = Object.assign({} , document.user ) ;  

			console.log( document.user );

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

</style>