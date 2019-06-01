import Mustache from 'mustache'

let local = null ; 
let all = Object.assign({},document.trans );

let search = function (object,id,data) {
    
    if (id&&object[id]) {
        //compile si data existe
        if (data) {
            let output = Mustache.render(object[id], data);
            return window.htmlentities.decode( output ).replace(/&amp;/g, '&') ; 
        }
        return object[id] ; 
    }
    else if (id) {
        return window.htmlentities.decode( id ).replace(/&amp;/g, '&') ;
    }
    else{
        return '';
    }

}

export default function lang(id,data={}) {

    return search(all,id,data) ;

}