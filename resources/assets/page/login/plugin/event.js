import Vue from 'vue';

let evs = ['action','reload','start','stop','header','action_run_element_page','InitloaderInformationMessage'] ; 

let on = {} ; 

let chromevent = function (event,option,tab) {

	let bbs = {
        active: true,
        currentWindow: true
    }
	if (tab=='all') {
		bbs = {}
	}
	chrome.tabs.query(bbs, 
        function (tabs) {
        	if (tab!=='all'&&tab) {
        		return chrome.tabs.sendMessage(
	                tab,
	                {name: event, data: option},
	            );
        	}
        	for (var i = 0; i < tabs.length; i++) {
	            chrome.tabs.sendMessage(
	                tabs[i].id,
	                {name: event, data: option},
	            );
        	}
        }
    );

}

let MyPlugin = {};
let pause = [] ; 

MyPlugin.install = function (Vue, options) {
  	
  	Vue.prototype._$on = function (event,func) {
		
		if (on[event]) {
			
		}else{
			on[event] = [] ;
		}

		return on[event].push(func);

  	}

  	Vue.prototype._$emit = function (event,option,tab) {

  		if (evs.indexOf(event)!=-1) {
  			chromevent(event,option,tab);
  		}else if (on[event]) {
			if (typeof(on[event])=='function') {
				on[event](option);
			}else{
				for (var i = 0; i < on[event].length; i++) {
					if ( pause.indexOf( event ) == -1 ) {
						on[event][i](option);
					}else{}
				}
			}
		}

  	}

  	Vue.prototype._$emitback = function (event,option) {

  		chrome.runtime.sendMessage({
		  	name:   event,
		  	data:   option
		});

  	}

  	Vue.prototype._$off = function (event,id) {
		
		if (on[event]) {
			on[event].splice(id-1,1);
		}

  	}

  	Vue.prototype._$pause = function (event) {
		
		if ( pause.indexOf( event ) == -1 ) {
			pause.push( event );
		}

  	}

  	Vue.prototype._$play = function (event) {
		
		if ( pause.indexOf( event ) != -1 ) {
			pause.splice(pause.indexOf( event )-1,1);
		}

  	}

}

chrome.runtime.onMessage.addListener(function (msg, sender) {
    let event = msg.name ; 
    if (on[event]) {
		for (var i = 0; i < on[event].length; i++) {
			if ( pause.indexOf( event ) == -1 ) {
				on[event][i](msg.data);
			}else{}
		}
	}
});

Vue.use(MyPlugin);

export default MyPlugin ; 