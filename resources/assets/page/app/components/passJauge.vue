<template>
	<div id="password_strength" data-appdesk="9934999385024993342" class="password_strength read-appdesk" style="height: 30px;">
		<div style="position: relative; width: 100%; height: 100%;">
			<div style="position: absolute; left: 0px; width: 100%; height: 100%; text-align: center; z-index: 1; padding: 0px; margin: 0px;">
				<table style="width: 100%; height: 100%; padding: 0px; margin: 0px;">
					<tbody>
						<tr style="padding: 0px; margin: 0px;">
							<td v-if="waitpassJauge" valign="middle" style="padding: 0px; margin: 0px; ">
								<b-progress style="height: 100%	" :value="100" variant="secondary" striped animated >
									<b-progress-bar :value="100">
								        please wait ... 
								    </b-progress-bar>
								</b-progress>
							</td>
							<td v-if="!waitpassJauge" valign="middle" style="padding: 0px; margin: 0px;">{{pass_info_jauge.txt}} ({{passJauge<0?'0':passJauge}}/100)</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div v-if="!waitpassJauge" :style="{ width : passJauge +'%' , backgroundColor : pass_info_jauge.color }" style="position: absolute; left: 0px; height: 100%; background-color: rgb(255, 0, 0);"></div>
		</div>
	</div>
</template>

<script>
	
	//, params: { id: item.id }}

	export default {

		props : ['passJauge','waitpassJauge'], 

		data(){

            return { 
            	wait : this.waitpassJauge , 
            }
    
        },

        computed : {

        	pass_info_jauge : function ( ) {

        		let res_jauge = {} ; 

        		if ( this.passJauge <= 0 ) {
        			let color = '#FFF';
        			if ( this.passJauge == 0 ) {
        				color = '#FF0000' ; 
        			}
        			res_jauge = {
	        			txt 	: 'Très faible' ,
	        			color ,
	        		}
        		}else if ( this.passJauge < 40 ) {
        			res_jauge = {
	        			txt 	: 'faible' ,
	        			color 	: '#FF9837' 
	        		}
        		}else if ( this.passJauge < 60 ) {
        			res_jauge = {
	        			txt 	: 'ok' ,
	        			color 	: '#F1FF4D' 
	        		}
        		}else if ( this.passJauge < 80 ) {
        			res_jauge = {
	        			txt 	: 'Élevé' ,
	        			color 	: '#C5FF00' 
	        		}
        		}else if ( this.passJauge > 80 ) {
        			res_jauge = {
	        			txt 	: 'Très élevé' ,
	        			color 	: '#28a745' 
	        		}
        		}

        		return res_jauge ; 

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

</style>