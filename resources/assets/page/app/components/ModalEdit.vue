<template>
	<div class="list-ctn">
		<b-modal v-model="modale" :title="title">
			
			<component @close="$emit('close')" @alert="alert" :data="option.data" :SubmitSet="SubmitSet" v-bind:is="comp"></component>

		    <div slot="modal-footer">
	  			<b-button v-if="option.btn&&option.btn.cancel" @click="$emit('close')" class="btn-app" :size="'sm'" >{{option.btn.cancel}}</b-button>
	  			<b-button v-if="option.btn&&option.btn.submit" @click="submit" class="btn-app" variant="primary" :size="'sm'" >{{option.btn.submit}}</b-button>
	  		</div>

		</b-modal>
	</div>
</template>

<script>

	export default {

		props : ['shoed','title','comp','option'], 

		data(){

            return {

            	SubmitSet : false , 

            }
    
        },

        methods : {

        	submit : function () {
        		
        		this.SubmitSet = true ; 
        		setTimeout(()=>{
        			this.SubmitSet = false ; 
        		}, 400);

        	},

        	alert : function ( data ) {
        		
        		this.modale = false ; 
        		this.$emit('alert', data ) ; 

        	}

        },

        computed : {
			
			modale : {

				set : function ( value ) {
					if ( !value ) {
						this.$emit('close')
						return false;
					}
					return true;
				}, 

				get : function () {
					return this.shoed;
				}

			}
		},
		
		created(){ 
			



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