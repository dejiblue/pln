<template>
	<b-form-group>
        <b-input-group>
            <span class="input-icon" >
                <img :src="'./img/bande2.png'" alt="">
            </span>
            <b-form-input id="name"
                    class="input-icon-txt"
                    v-bind:value="value"
                    @input="$emit('input', $event.target.value);">
            </b-form-input>

            <input type="text">
            <!--<b-form-invalid-feedback id="ErreurName">
                <span vi-if="!txt.name">
                    {{$lang('sinup.form.nameError')}}
                </span>
                <span vi-if="txt.name">
                    {{txt.name}}
                </span>
            </b-form-invalid-feedback>-->
        </b-input-group>
    </b-form-group>
</template>

<script>

	export default {

		props : [ 'value' , 'txt' ], 

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


</style>