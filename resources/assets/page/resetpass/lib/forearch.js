class forearch{
	
	constructor(data,func){

		if (typeof(data) =='number') {
			let tempdata = [] ; 
			for (var i = 0; i < data; i++) {
				tempdata.push(i) ; 
			}
			data = tempdata ; 
		}
		else if (typeof(data)!='object') {
			return false;
		}

		this.progressFuncs = [];
		this.beforeEndFuncs = [];
		this.firstFuncs = [];
		this.endFuncs = [];
		this.data = data;
		this.callFunction = func; 

	}

	progress(callback){
		if (typeof(callback)=='function') {
			this.progressFuncs.push(callback);
		}
	}

	/*
	beforeEnd(callback){
		if (typeof(callback)=='function') {
			this.beforeEndFuncs.push(callback);
		}
	}*/

	first(callback){
		if (typeof(callback)=='function') {
			this.firstFuncs.push(callback);
		}
	}

	end(callback){
		if (typeof(callback)=='function') {
			this.endFuncs.push(callback);
		}
	}

	thisParcourWithFunc(){

		this.callFunction(this.data[this.compteur],(res)=>{

			let isReady = true;
			if (this.compteur==0&&this.firstFuncs.length>0) {
				isReady = false;
				for (var a = 0; a < this.firstFuncs.length; a++) {
					if (typeof(this.firstFuncs[a])=='function') {
						this.firstFuncs[a](res,this.data[this.compteur]);
					}
				}
			};
			if ((this.compteur>=this.parcour-1&&this.endFuncs.length==0)||(this.compteur<this.parcour-1&&isReady)) {
				isReady = false;
				for (var a = 0; a < this.progressFuncs.length; a++) {
					if (typeof(this.progressFuncs[a])=='function') {
						this.progressFuncs[a](res,this.data[this.compteur]);
					}
				}
			}

			this.compteur++;
			if (this.compteur>=this.parcour) {
				if (isReady) {
					for (var a = 0; a < this.endFuncs.length; a++) {
						if (typeof(this.endFuncs[a])=='function') {
							this.endFuncs[a](res,this.data[this.compteur-1]);
						}
					}
				}
			}
			else{
				this.thisParcourWithFunc();
			}

		});

	}

	//Lancement du foreach
	run(){

		if (this.data.length==0) {
			for (var a = 0; a < this.endFuncs.length; a++) {
				if (typeof(this.endFuncs[a])=='function') {
					this.endFuncs[a](null);
				}
			}
			return true;
		}
		this.parcour = this.data.length ; 
		this.compteur = 0 ; 

		if (typeof(this.callFunction)=='function') {
			this.thisParcourWithFunc();
		}else{

			for (var i = 0; i < this.parcour; i++) {
				let isReady = true;
				if (i==0&&this.firstFuncs.length>0) {
					isReady = false;
					for (var a = 0; a < this.firstFuncs.length; a++) {
						if (typeof(this.firstFuncs[a])=='function') {
							this.firstFuncs[a](this.data[i]);
						}
					}
				};
				if (isReady&&i>=this.parcour-1&&this.endFuncs.length>0) {
					isReady = false;
					for (var a = 0; a < this.endFuncs.length; a++) {
						if (typeof(this.endFuncs[a])=='function') {
							this.endFuncs[a](this.data[i]);
						}
					}
				}
				if (isReady) {
					for (var a = 0; a < this.progressFuncs.length; a++) {
						if (typeof(this.progressFuncs[a])=='function') {
							this.progressFuncs[a](this.data[i]);
						}
					}
				}
			}
		}
	}

}
 
export default function (data,func) {
	return new forearch(data,func);
};