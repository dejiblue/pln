<template>
	<div class="guide">
		<div style="position: relative;">
		    <div class="callout callout-info">
		        <h2>How to</h2> <span>Quick guide to create multi account and CMS installation</span>
		        <br>
		        <br>
		        <button @click="show_guide('collapse2')" type="button" data-toggle="collapse" data-target="#1" class="btn btn-info collapsed" aria-controls="collapse2" aria-expanded="false" style="display: block; width: 100%; font-size: 18px;">Create an account with random ip</button>
		        <div v-show="showed.collapse2" id="collapse2" class="collapse collapse" style="display: block ;background-color: rgba(255, 255, 255, 0.5); padding: 1%; border-radius: 4%; font-size: 15px;">
		            <br>
		            <ul>
		                <li>Enter the domain name</li>
		                <li>Select a Package</li>
		                <li>Type your Password</li>
		                <li>Enter your email</li>
		                <li>Click on "have fun!"</li>
		            </ul>
		        </div>
		        <button @click="show_guide('collapse3')" type="button" data-toggle="collapse" data-target="#3" class="btn btn-info collapsed" aria-controls="collapse3" aria-expanded="false" style="display: block; width: 100%; font-size: 18px;">Create an account with ip 'Domain &amp; ip'</button>
		        <div v-show="showed.collapse3" id="collapse3" class="collapse collapse" style="display: block ;background-color: rgba(255, 255, 255, 0.5); padding: 1%; border-radius: 4%; font-size: 15px;">
		            <br> <span>if you want to create multiple accounts with selected ips</span> <span>enter the list of domains in the Domain &amp; ip part in this way:</span>
		            <br>
		            <ul>
		                <li>domaine.com 192.168.1.1</li>
		                <li>domaine.com 192.168.1.2</li>
		                <li>domaine.com 192.168.1.3</li>
		                <li>domaine.com 192.168.1.3</li>
		                <li>Click on "have fun!"</li>
		            </ul>
		        </div>
		        <p></p>
		        <button @click="show_guide('collapse4')" type="button" data-toggle="collapse" data-target="#4" class="btn btn-info collapsed" aria-controls="collapse4" aria-expanded="false" style="display: block; width: 100%; font-size: 18px;">To install WordPress automatically</button>
		        <div v-show="showed.collapse4" id="collapse4" class="collapse collapse" style="display: block ;background-color: rgba(255, 255, 255, 0.5); padding: 1%; border-radius: 4%; font-size: 15px;">
		            <br>
		            <ul>
		                <li>Check WordPress install</li>
		                <li>Change the language if necessary</li>
		                <li>Auto install plugins * </li>
		                <li>Click on "have fun!"</li>
		            </ul> <span>* Please select each plugin you want to activate by using CTRL button on you keyboard by pressing</span></div>
		        <button @click="show_guide('collapse5')" type="button" data-toggle="collapse" data-target="#5" class="btn btn-info collapsed" aria-controls="collapse5" aria-expanded="false" style="display: block; width: 100%; font-size: 18px;">Ip Fonction</button>
		        <div v-show="showed.collapse5" id="collapse5" class="collapse collapse" style="display: block ;background-color: rgba(255, 255, 255, 0.5); padding: 1%; border-radius: 4%; font-size: 15px;">
		            <br>
		            <ul>
		                <li>Ip dedicated random : use free dedicted ip</li>
		                <li>Ip shared random : use reseller shared ip</li>
		            </ul> <span>you can use both options, PLN Manager will then create the counters randomly using the dedicated and shared ips available on the server.</span>
		            <br> <span>If you want to use only dedicated ips you can uncheck "ip shared" and vice versa.</span>
		            <br> <span>If you want to choose the same ip for all accounts uncheck the two boxes and then you can choose the ip.</span>
		            <br>
		        </div>
		        <button @click="show_guide('collapse6')" data-toggle="collapse" class="btn btn-info collapsed" aria-controls="collapse6" aria-expanded="false" style="display: block; width: 100%; font-size: 18px;">Reports</button>
		        <div v-show="showed.collapse6" id="collapse6" class="collapse collapse" style="display: block ;background-color: rgba(255, 255, 255, 0.5); padding: 1%; border-radius: 4%; font-size: 15px;">
		            <br> <span>after the creation and / or installation of the CMS you will receive several emails on the address that you have filled in</span>
		            <br>
		            <br> <span>Basic WordPress email with information</span>
		            <br> <span>Report e-mail from PLN Manager with all the information on the installation of cPanel and CMS, with a CSV export where all the information is resumed like: DOMAINE USERNAME PASSWORD IP PACKAGE wpsite wplogin wppass wpdbname wpdbpass</span>
		            <br>
		        </div>
		    </div>
		</div>
	</div>
</template>

<script>

	export default {

		props : ['placeholder'], 

		data(){

            return {
		    	
		    	showed : {
		    		collapse2 : false , 
					collapse3 : false , 
					collapse4 : false , 
					collapse5 : false , 
					collapse6 : false 
		    	}

            }
    
        },

        methods : {

        	show_guide : function (e) {

        		if ( !this.showed[e] ) {
        			this.showed[e]=true;
        		}else{
        			this.showed[e]=false;
        		}

        	}

        },

        computed : {
        	 
        },
		
		created(){ 

		}

	}

</script>

<style>

	.loader{
		position: fixed;
		top: 0;left: 0;right: 0;
		height: 100%; width: 100%;
		z-index: 100;
		background-color: rgba(255,255,255,0.6);
	}
	
	.loader-content{
		position: absolute;
		top: 0;left: 0;right: 0;
		height: 200px;
		width: 200px;
		left: 50%;
		top : 50%;
		transform: translateX( -50% ) translateY( -50% );
	}

	.loader-content svg{
		width: 100%;
		height: 100%;
	}

</style>