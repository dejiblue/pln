import axios from 'axios';

export function get(url) {
	return axios.get(url) ; 
}

export function post(url,data ,option = {}) {
	return axios.post( url , data , option ) ; 
}

export function byMethode(method,url,data,option = {}) {
	return axios({
	  	method,
	  	url,
	  	data , 
	  	option
	})
}