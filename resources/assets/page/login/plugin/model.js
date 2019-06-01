import Vue from 'vue';
import { groupe , config , friends , users , category , message , session
 } from '../../lib/bdd'

let plugModel = {};
plugModel.install = function (Vue, options) {
  	Vue.prototype.$groupe = groupe() ; 
  	Vue.prototype.$config = config() ; 
  	Vue.prototype.$friends = friends() ; 
  	Vue.prototype.$users = users() ; 
  	Vue.prototype.$category = category() ; 
  	Vue.prototype.$session = session() ; 
  	Vue.prototype.$message = message() ; 
}
Vue.use(plugModel);

export default plugModel ;