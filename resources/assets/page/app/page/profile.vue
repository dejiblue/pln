<template>
	<div>
        <b-container>
        	<b-row>

        		<b-col cols="12"> 
                    <Updateprofile @successupdate="$emit('alert',$lang('profile.update.alert'))" @advcprofile="advcprofile" v-if="profile_edit" @cancel="profile_edit=false;" ></Updateprofile>
                    <UpdateprofileWPCP @successupdate="$emit('alert',$lang('profile.update.alert'))" @advcprofile="advcprofile" v-if="profile_edit" @cancel="profile_edit=false;" ></UpdateprofileWPCP>
                    <UpdateprofileAPI @successupdate="$emit('alert',$lang('profile.update.alert'))" @advcprofile="advcprofile" v-if="profile_edit" @cancel="profile_edit=false;" ></UpdateprofileAPI>
                    <UpdateprofileSH @successupdate="$emit('alert',$lang('profile.update.alert'))" @advcprofile="advcprofile" v-if="profile_edit" @cancel="profile_edit=false;" ></UpdateprofileSH>
                    
                    <Viewprofile v-if="!profile_edit" @edit="profile_edit=true;"></Viewprofile>
                </b-col>

        		<b-col cols="12">
        			<b-row>
        				<b-col md="6" cols="12">
        					<Filesgoogle></Filesgoogle>
        				</b-col>

        				<b-col md="6" cols="12">
        					<Categorie @edit="editca" @newca="$emit('modale', newca )"></Categorie>
        				</b-col>
        			</b-row>
        		</b-col>

        	</b-row>
        </b-container>
	</div>
</template>

<script>
	
	import { get } from '../lib/api'

	export default {

		props : [ ], 

		data(){

            return {
            	
            	profile_edit : false , 

            	newca : {comp:'CategorieEdit',title:this.$lang('cat.form.title'),btn:{ submit:this.$lang('cat.form.btn'), cancel : this.$lang('cat.form.btn.cancel')}}

            }
    
        },


        methods : {

        	changeinput : function ( champ ) {
                
                if ( this.form[champ] ) {
                    this.error[champ]=null ; 
                }

            },

            editca : function ( data ) {
            	
            	this.$emit('modale', Object.assign({}, { data } , {comp:'CategorieEdit',title:this.$lang('cat.form.title'),btn:{ submit:this.$lang('cat.form.update.btn'), cancel : this.$lang('cat.form.update.btn.cancel')}} ) ) 

            }, 

            //advency profile edit
            advcprofile : function ( data ) {

                this.$emit('modale', Object.assign({}, { data } , {comp:'ProfileUpdateModale',title:this.$lang('modale.profile.title'),btn:{ submit:this.$lang('modale.profile.btn') , cancel : this.$lang('modale.profile.btn.cancel')}} ) ) 

            }

        },
		
		created(){
		
			//
		}

	}

</script>

<style>

	
	.header-profile{
		background-color:  #FFF ; 
		margin-top: 4rem;
		padding: 1rem 1rem;
	}

	.header-lst{
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: -o-flex;
		display: flex;
	}

	.header-lst .s{
		flex: 1;
		margin-left: 10px;
	}
	
	.list-ctn{
		margin-top: 20px;
	}

    .card-body{
        padding: 0rem;
    }

    .list-group-item{
        border: none;
    }

</style>