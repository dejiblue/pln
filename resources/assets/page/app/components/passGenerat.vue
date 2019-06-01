<template>

    <div class="passgenerator">
        <b-popover :target="target"
           triggers="click"
           placement="bottom"
           :show.sync="popoverShow"
           ref="popover">
            
            <template slot="title">
                <b-btn @click="popoverShow = false ;" class="close" aria-label="Close">
                  <span class="d-inline-block" aria-hidden="true">&times;</span>
                </b-btn>
                Password Generator
            </template>

            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td>
                            <b-input-group size="sm" style="margin-bottom: 0.4rem;">
                                <b-form-input v-model="passvalue"/>
                                <b-btn @click="generatecode" size="sm" text="Button" variant="success">Generate Password</b-btn>
                            </b-input-group>
                        </td>
                    </tr>
                    <tr>
                        <td><span style="display: block; margin-bottom: 0.6rem; cursor: pointer;" @click="show_advency_code?show_advency_code=false:show_advency_code=true">Advanced Options »</span>
                            <div id="generate_password_advanced_options" style=" background-color: #eeeeee52; padding: 6px 4px;" v-show="show_advency_code" >
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2">Length:
                                                <input v-model="generate_password_length" id="generate_password_length" size="2" maxlength="2" type="text"> (10-18)</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Alpha Characters:</td>
                                            <td width="50%">Non Alpha Characters:</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input v-model="generate_password_alpha" name="generate_password_alpha" id="generate_password_mixed_alpha" checked="checked" type="radio" value="mixed_alpha">
                                                <label for="generate_password_mixed_alpha">Both (aBcD)</label>
                                            </td>
                                            <td>
                                                <input v-model="generate_password_nonalpha" name="generate_password_nonalpha" id="generate_password_mixed_nonalpha" checked="checked" type="radio" value="mixed_nonalpha">
                                                <label for="generate_password_mixed_nonalpha">Both (1@3$)</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input v-model="generate_password_alpha" name="generate_password_alpha" id="generate_password_lowercase" type="radio" value="lowercase">
                                                <label for="generate_password_lowercase">Lowercase (abc)</label>
                                            </td>
                                            <td>
                                                <input v-model="generate_password_nonalpha" name="generate_password_nonalpha" id="generate_password_numbers" type="radio" value="numbers">
                                                <label for="generate_password_numbers">Numbers (123)</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input v-model="generate_password_alpha" name="generate_password_alpha" id="generate_password_uppercase" type="radio" value="uppercase">
                                                <label for="generate_password_uppercase">Uppercase (ABC)</label>
                                            </td>
                                            <td>
                                                <input v-model="generate_password_nonalpha" name="generate_password_nonalpha" id="generate_password_symbols" type="radio" value="symbols">
                                                <label for="generate_password_symbols">Symbols (@#$)</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-if="!nativegenerate" style="display: block; width: 100%;">
                <input v-model="usedpass" id="generate_password_confirm" type="checkbox">
                <label for="generate_password_confirm">I have copied this password in a safe place.</label>
            </p>

            <b-btn @click="popoverShow=false; " size="sm">Cancel</b-btn>
            <b-btn :disabled="!usedpass&&!nativegenerate" @click="submit" size="sm" variant="primary">Use Password</b-btn>
 
        </b-popover>
    </div>

</template>

<script>
	
	//, params: { id: item.id }}

	export default {

		props : ['target' ,'nativegenerate'], 

		data(){

            return {

                popoverShow : false , 

            	passvalue : '' , 

            	//show advency
            	show_advency_code : false , 

            	usedpass : false , 

            	//
            	generate_password_alpha : 'mixed_alpha' ,

            	//
            	generate_password_nonalpha : 'mixed_nonalpha' ,

            	//
            	generate_password_length : 12 , 

            }
    
        },

        methods : {

        	entierAleatoire: function (min, max)
		    {
		     	return Math.floor(Math.random() * (max - min + 1)) + min;
		    },

            generatePassword : function ( charset , length ) {
		    
		        var length = length,
		            charset = charset,
		            retVal = "";
		        for (var i = 0, n = charset.length; i < length; ++i) {
		            retVal += charset.charAt(Math.floor(Math.random() * n));
		        }
		        return retVal;
		    
		    },

            placeTextCode : function ( $code , $items ) {
        
		        if (this.entierAleatoire(0 , 9)%2 == 0) {
		            $code = $code + $items ; 
		        }else{
		            $code = $items + $code ; 
		        }
		        return $code ; 
		    
		    },

		    submit(){

                this.popoverShow=false; 
                this.$emit('pass',this.passvalue) ; 

		    },

        	generatecode () {
        		
        		let mixed_alpha = 'ABCDEFGHIJKLMNOPQRSUVWXYZabcdefghijklmnopqrsuvwxyz' ; 
				let lowercase = 'abcdefghijklmnopqrsuvwxyz' ; 
				let uppercase = 'ABCDEFGHIJKLMNOPQRSUVWXYZ' ; 

				let mixed_nonalpha = "(!@$%^*()`~-_=+[{]}\\|;:'\",<.>/)123456789" ; 
				let numbers = "123456789";
				let symbols = "(!@$%^*()`~-_=+[{]}\\|;:'\",<.>/)" ;//#&

				let nbrSymbole = 0 ; 

				let leng = this.generate_password_length ; 

				if (isNaN(leng)) {
		            leng = 12 ; 
		        }else if(leng>18){
		            leng = 18 ; 
		        }else if(leng<10){
		            leng = 10 ; 
		        }

		        if(leng<=12){
		            nbrSymbole = this.entierAleatoire(3 , 4) ; 
		        }
		        else if(leng<=14){
		            nbrSymbole = this.entierAleatoire(4 , 6) ; 
		        }
		        else if(leng<=18){
		            nbrSymbole = this.entierAleatoire(4 , 8) ; 
		        }

		        this.generate_password_length = leng ;  

				var un = '' ; 
		        if (this.generate_password_alpha=='mixed_alpha') {
		            un = mixed_alpha ; 
		        }else if (this.generate_password_alpha=='lowercase') {
		            un = lowercase ; 
		        }else if (this.generate_password_alpha=='uppercase') {
		            un = uppercase ; 
		        }

		        var deux = '' ; 
		        if (this.generate_password_nonalpha=='mixed_nonalpha') {
		            deux = mixed_nonalpha ; 
		        }else if (this.generate_password_nonalpha=='numbers') {
		            deux = numbers ; 
		        }else if (this.generate_password_nonalpha=='symbols') {
		            deux = symbols ; 
		        }

		        var rest_deux = nbrSymbole ; 
        		var code = '';

				for (var i = 0; i < this.generate_password_length ; i++) {

		            if ((this.generate_password_length-i)-rest_deux<=0) {
		                code = this.placeTextCode( code , this.generatePassword( deux , 1 ) ) ;
		                rest_deux--;
		            }else if (this.entierAleatoire(0 , 9)%2 == 0) {
		                code = this.placeTextCode( code , this.generatePassword( un , 1 ) ) ;
		            }else{
		                if (rest_deux<=0) {
		                    code = this.placeTextCode( code , this.generatePassword( un , 1 ) ) ;
		                }else{
		                    code = this.placeTextCode( code , this.generatePassword( deux , 1 ) ) ;
		                    rest_deux--;
		                }
		            }
		        }

		        this.passvalue = code ; 

        	}

        },
		
		mounted(){ 
			
			this.generatecode()

		}

	}

</script>

<style>

    

</style>