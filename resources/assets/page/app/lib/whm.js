

import { byMethode , get , post } from './api';

let env = 'prod' ; //local
let host = '' ; 
let code = '' ; 

function parse(text,endmsg='END RECOMPTE') {

    let str = text.split(/\n---EVENT:\n|\n---ENDEVENT;\n/);
    let count = str.length ; 
    let last = null;
    let endprocess = false;
    const map1 = str.filter((x,s) => {if (x) {if (x.indexOf('---EVENTPROCESS---') !== -1) {endprocess=true;};return true;
    }return false;});
    let isjslast='';
    if (endprocess) {
        isjslast=map1[map1.length-1].replace("\n---EVENTPROCESS---\n", "").replace("---EVENTPROCESS---", "");
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



export function whm_check( serveur , cbl ) {

	let url = window.urlapp+'/check'  ;

	var formData = new FormData();

    formData.append("_method", 'post' );
    formData.append("token", document.csrf_token);
    formData.append("username", serveur.username);
    formData.append("accesstoken", serveur.accesstoken);
    formData.append("url", serveur.url);
    formData.append("port", serveur.port);

    post( url , formData , {headers: { 'content-type': 'application/x-www-form-urlencoded' }})

        .then((res)=>{
            cbl( res.data.strength ) ;
        })  
    
        .catch(()=>{
            cbl( 0 ) ; 
        })

	return url ;  

}


export function whm_pass( data , cbl ) {

	//@todo: Erreur sur les mots de passe qui on des ex : LojGAl#""bfM
	// console.log(document.getElementById('password').value);

	let url = window.urlapp+'/check'  ;

	var formData = new FormData();

    formData.append("pass", data ); 

	return post( url , formData , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	        cbl( response.data.strength ) ;
	    })
	
	    .catch(()=>{
	        cbl( 0 ) ;
	    }) ;   


}

//installation de internetbs sur les domaine crée 

export function internetbs( data , serveur , cbl ) {

	console.log( 'internetbs - DATA send : ' , data );
	
	let url = window.urlapp+'/site/internetbs'  ;

	var formData = new FormData();

	formData.append("_method", 'post' );
	formData.append("token", document.csrf_token);
	formData.append("internetbs", data.join("\n") );
	formData.append("serveur_id", serveur);

	return post( url , formData , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	        cbl( response.data ) ; 
	    })
	
	    .catch(()=>{
	        cbl( false ) ; 
	    }) ;  

}


export function runscript( data , serveur , cbl ) {

	console.log( ' --- runscript - DATA send : ' );
	
	let url = window.urlapp+'/site/runscript'  ;

	var formData = new FormData();

	formData.append("_method", 'post' );
	formData.append("token", document.csrf_token);
	formData.append("serveur_id", serveur);
	let dataSend = data.map( e => e.username ) ; 
	formData.append("data", dataSend.join("\n") );
	
	return post( url , formData , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{})
	
	    .catch(()=>{}) ;  

}

export function create( formData , categorie , serveur , cbl , clbsteep ) {

	let url = window.urlapp+'/site'  ;

    formData.append("_method", 'post' );
    formData.append("token", document.csrf_token);
    formData.append("serveur_id", serveur);
    formData.append("categorie_id", categorie);

    var request = $.ajax({

    	type: "POST",
 	    url : url ,
 	    data: formData ,
 	    processData: false,
        contentType: false,

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

		console.log( event );

		if ( event && event.success ) {
	    	return cbl( event.success ) ; 
    	}
 		cbl( null ) ; 

	});

	request.fail(function( e ) {

		console.log( e );

	    if ( e.status==200 ) {
	    	if ( event.data && event.data.success && event.data.success.length>0 ) {
	    		let event = parse( e.responseText ) ;
		 		return cbl( event.data , event.sh1unique ) ; 
	    	}
	    }
 		cbl( null ) ; 

	});


}


export function wordpress( formData , categorie , serveur , cbl , clbsteep ) {

	let url = window.urlapp+'/wp/install'  ;

    formData.append("_method", 'post' );
    formData.append("token", document.csrf_token);
    formData.append("serveur_id", serveur);
    formData.append("categorie_id", categorie);

    var request = $.ajax({

    	type: "POST",
 	    url : url ,
 	    data: formData ,
 	    processData: false,
        contentType: false,

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

		if ( event.success && event.success.data ) {
	    	return cbl( event.success.data ) ; 
    	}
 		cbl( null ) ; 

	});

	request.fail(function( e ) {

		console.log( e );

	    if ( e.status==200 ) {
	    	if ( event.data && event.data.success && event.data.success.length>0 ) {
	    		let event = parse( e.responseText ) ;
		 		return cbl( event.data , event.sh1unique ) ; 
	    	}
	    }
 		cbl( null ) ; 

	});


}

export function install_theme( data , cbl ) {

	let url = window.urlapp+'/wp/theme'  ;

	return post( url , data , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	        cbl( response.data ) ; 
	    })
	
	    .catch(()=>{
	        cbl( [] ) ; 
	    }) ;   

}

export function install_plugin( data , cbl ) {

	let url = window.urlapp+'/wp/plugin'  ;

	return post( url , data , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	        cbl( response.data ) ; 
	    })
	
	    .catch(()=>{
	        cbl( [] ) ; 
	    }) ; 

}


export function install_file( data , cbl ) {

	let url = window.urlapp+'/wp/post'  ;

	return post( url , data , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{
	        cbl( response.data ) ; 
	    })
	
	    .catch(()=>{
	        cbl( [] ) ; 
	    }) ; 

}


/*
*	Récupération du log de crétion et supression de l'ancien log 
*/

export function log_create( data , cbl ) {

	let url = window.urlapp+'/log/create'  ;

	return post( url , data , { headers: { 'Content-Type': 'multipart/form-data' }} )
	
	    .then((response)=>{

	    	if ( response.data.success ) {
	    		return cbl( response.data.success ) ;
	    	}

	    	cbl( null ) ;

	    })
	
	    .catch(()=>{
	        cbl( null ) ; 
	    }) ; 

}