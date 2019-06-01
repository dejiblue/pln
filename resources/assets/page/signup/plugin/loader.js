import Vue from 'vue';
import lang from '../../lib/lang';

let plugLoader = {};
let fun = null ; 

plugLoader.install = function (Vue, options) {
  	
  	Vue.prototype.$showLoader = ( data = null )=>{

  		if ( ! data ) {
  			return fun(true,'','rgba(255, 255, 255, 0.1)') ; 
  		}
  		fun(true,`<i>${data.type}</i><br>${data.fullname?data.fullname:data.url} <em>${'('+data.now+'/'+data.totale+')'}</em>`,'rgba(255, 255, 255, 0.9)') ; 

  	}; 

  	Vue.prototype.$hideLoader = ()=>{
  		fun(false) ; 
  	}; 

  	Vue.prototype.$initLoader = ( _ )=>{
  		fun = _ ;
  	}; 

}

Vue.use(plugLoader);

export default plugLoader ; 