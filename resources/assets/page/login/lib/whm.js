

import { byMethode , get , post } from './api';

let env = 'prod' ; //local
let host = '' ; 
let code = '' ; 

var urlFormat = function () {
	
	let urlf = new URL( document.URL ) ; 
	return urlf.origin + urlf.pathname ;

}

let auth = function () {

	let url = '' ;

	url = '?vps=vps486218.ovh.net:2087' ;
	url += '&user=root' ;
	url += '&pass=HeldinoDEV2018' ;

	return url ;

}

export function find_url( ) {

	return urlFormat ;  

}

export function whm_pass( data , cbl ) {

	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/checkpass.php' + auth() ;
	}else{
		url = urlFormat() ;
		url += '?page=/compte/check' ;
	}

	url += '&password=' + data ;

    get( url ,{} )
        .then((response)=>{
            cbl( response.data.strength ) ; 
        })
        .catch(()=>{
            cbl( 0 ) ; 
        })

	return url ;  

}

export function whm_create( data , cbl ) {
 
	cbl( { domain : 'is note a domaine valide'}) ; 

}

export function history( cbl ) {

	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/history.php' + auth() ;
	}else{
		url = urlFormat() ;
		url += '?page=/wp/history' ;
	}

    byMethode('get', url ,{})
        .then((response)=>{
            cbl( response.data ) ; 
        })
        .catch(()=>{
            cbl( [] ) ; 
        })

	return url ;  

}

export function compte( cbl ) {

	let url = '' ;

	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/user.php' + auth() ;
	}else{
		url = urlFormat() ;
		url += '?page=/compte/wpcompte' ;
	}

    byMethode('get', url ,{})
        .then((response)=>{
            cbl( response.data ) ; 
        })
        .catch(()=>{
            cbl( [] ) ; 
        })

	return url ;  

}

export function liste( data , cbl ) {


	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/listing.php' + auth() ;
	}else{
		url = urlFormat() ;
		url += '?page=/wp/liste' ;
	}

	url += '&paged=' + data['paged'] ;
	url += '&domain=' + data['domain'] ;
	url += '&cpuser=' + data['cpuser'] ;

    byMethode('get', url ,{})
        .then((response)=>{
            cbl( response.data ) ; 
        })
        .catch(()=>{
            cbl( [] ) ; 
        })

	return url ;  

}

export function item( data , cbl ) {


	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/view.php' + auth() ;
		url += '&cpanel=' + data ;
	}else{
		url = urlFormat() ;
		url += '?page=/wp/item/' + data ;
	}

    byMethode('get', url ,{})
        .then((response)=>{
            cbl( response.data ) ; 
        })
        .catch(()=>{
            cbl( [] ) ; 
        })

	return url ;  

}


function parse(text,endmsg='END RECOMPTE') {

    let str = text.split(/\n---EVENT:\n|\n---ENDEVENT;\n/);
    let count = str.length ; 
    let last = null;
    let endprocess = false;
    const map1 = str.filter((x,s) => {if (x) {if (x.indexOf('\n---EVENTPROCESS---\n') !== -1) {endprocess=true;};return true;
    }return false;});
    let isjslast='';
    if (endprocess) {
        isjslast=map1[map1.length-1].replace("\n---EVENTPROCESS---\n", "");
    }
    else{isjslast = map1[map1.length-1];}
    try {
        let data = JSON.parse(isjslast);
        if (endprocess) {
            data = {steep:endmsg,data:data};
        }
        return data ;
    }
    catch(error) {return {};}

}

export function create( data , cbl , clbsteep ) {

	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/create.php' + auth() + '&';
	}else{
		url = urlFormat() ;
		url += '?page=/api/create' + '&' ;
	}

	let myData = data ;
	let out = [];

	for (let key in myData) {
	    if (myData.hasOwnProperty(key)) {
	        out.push(key + '=' + encodeURIComponent(myData[key]));
	    }
	}

	out = out.join('&');

	url += out ; 

    var request = $.ajax({
 	    url : url ,
 	    xhr: function () {
 	        var xhr = $.ajaxSettings.xhr();
 	        xhr.onprogress = function e(ev) {
 	            let event = parse(ev.currentTarget.response) ;
 	            if (event.steep) {
 	            	clbsteep( event.steep )
 	            }
 	        };
 	        return xhr;
 	    }
 	})

	request.done(function( e ) {

    	let event = parse( e ) ;
	    if ( event.data && event.data.success && event.data.success.length>0 ) {
	 		return cbl( event.data ) ; 
    	}
 		cbl( null ) ; 

	});

	request.fail(function( e ) {

	    if ( e.status==200 ) {
	    	if ( event.data && event.data.success && event.data.success.length>0 ) {
	    		let event = parse( e.responseText ) ;
		 		return cbl( event.data ) ; 
	    	}
	    }
 		cbl( null ) ; 

	});


}


export function install_theme( data , cbl ) {

	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/create.php' + auth() + '&';
	}else{
		url = urlFormat() ;
		url += '?page=/wp/theme' ;
	} 

	return post( url , data , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	        cbl( response.data ) ; 
	    })
	
	    .catch(()=>{
	        cbl( [] ) ; 
	    }) ;   

}

export function install_plugin( data , cbl ) {

	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/create.php' + auth() + '&';
	}else{
		url = urlFormat() ;
		url += '?page=/wp/plugin' ;
	}

	return post( url , data , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	        cbl( response.data ) ; 
	    })
	
	    .catch(()=>{
	        cbl( [] ) ; 
	    }) ;   

}


export function install_file( data , cbl ) {

	let url = '' ;
	
	if ( env == 'local' ) {	
		url = 'http://localhost/whmapi/create.php' + auth() + '&';
	}else{
		url = urlFormat() ;
		url += '?page=/wp/upload' ;
	} 

	return post( url , data , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	    	cbl( response.data ) ; 
	    })
	
	    .catch(()=>{
	    	cbl( [] ) ; 
	    }) ;   

}