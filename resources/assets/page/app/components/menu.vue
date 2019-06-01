<template>
	<div class="menu">

		 <b-container>
        	<b-row>
        		<b-col cols="12">
        			 <b-navbar toggleable="md">

					  	<b-navbar-brand href="#">
							<img :src="'./img/logo.png'" alt="logo" style="width: 35px; height: 35px;">
					  	</b-navbar-brand>

					  	<b-navbar-nav>

					  		<b-nav-item :to="{name:'profile'}">
					  			{{$lang('menu.profile')}}
					  		</b-nav-item>
					  		
					  		<b-nav-item :to="{name:'seveur'}">
					  			{{$lang('menu.seveur')}}
					  		</b-nav-item>

					  		<b-nav-item :to="{name:'installsite'}">
					  			{{$lang('menu.installsite')}}
					  		</b-nav-item>

					  		<b-nav-item :to="{name:'manager'}">
					  			{{$lang('menu.manager')}}
					  		</b-nav-item>

					  		<b-nav-item :to="{name:'history'}">
					  			{{$lang('menu.history')}}
					  		</b-nav-item>


					  	</b-navbar-nav>

						<!--					  	<b-navbar-nav class="ml-auto">
					  		<b-nav-item-dropdown size="sm" right>
					  			<template slot="button-content">
									<img :src="'./img/avatar.png'" alt="avatar" style="width: 35px; height: 35px; border-radius: 50%;">
					  			</template>
					          	<b-dropdown-item href="#">
									<b-nav-item :to="{name:'seveur'}">
					  					{{$lang('menu.logout')}}
							  		</b-nav-item>
					          	</b-dropdown-item>
					        </b-nav-item-dropdown>
					  	</b-navbar-nav>
-->
					</b-navbar>
        		</b-col>
        	</b-row>
        </b-container>
		
	</div>
</template>

<script>

	export default {

		props : [], 

		data(){

            return {
            
            }
    
        },

        methods : {

        },

		created(){ 

		}

	}

</script>

<style>
	
	.menu{
		box-shadow: 0px 0px 4px rgba(0,0,0,0.4) ; 
		background-color:  #FFF ; 
	}
	
	
</style>