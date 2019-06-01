

export function fun_domain( data ) {

	let datas = data.split(/\r|\n|\t| /) ;  
	let temps = [] ; 

	for (var i = 0; i < datas.length; i++) {
		if ( datas[i] ) {
			temps.push( datas[i].trim() ); 
		}
	}

	return temps ; 

}


export function fun_domainip( data ) {

	let datas = data.split(/\r|\n|\t| /) ;
	let temps = [] ;  

	let impaire = null ; 
	let paire = null ; 

	let falshed = false ; 

	datas = datas.filter(e=>e) ;

	for (var i = 0; i < datas.length; i++) {

		if ( i % 2 == 1 ) {
			falshed = true;
			paire = datas[i].trim() ; 
		}else{
			falshed = true;
			impaire = datas[i].trim() ; 
		}

		if ( impaire && paire ) {
			temps.push( { domain : impaire , ip : paire } ); 
			falshed = false;
			impaire = null ; 
			paire = null ; 
		}
	
	}

	if ( falshed && datas.length > 0 && datas[datas.length-1]!='') {
		return false;
	}

	if ( temps.length == 0 ) {
		return false ; 
	}

	return temps ; 

}


export function fun_ipvaluse() {

}


export function fun_ipvalue() {

}

