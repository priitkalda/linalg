// see fail on mõeldud php-ga skripti sisse trükkimiseks, mitte html päisesse panekuks
var printMurd = function(murrusõne, kasSulud){
	var ava = "";
	var sulge = "";
	if (murrusõne.indexOf("-") >= 0 && kasSulud == true  ){
		var ava = "(";
		var sulge = ")";
	}
	if( murrusõne.indexOf("/") >= 0) {
		
		var ava = "";
		var sulge = "";
		var tulem = "<div style=\"  vertical-align: middle;   display: inline-block;\">";
		/*
		if (murrusõne.indexOf(" ") >= 0 ){
			
			tulem += "<div style=\"    float:left;\">"+murrusõne.split(" ")[0]+"</div>";
			murrusõne = murrusõne.split(" ")[1];
			
		}else{
			if( murrusõne.indexOf("-") >= 0 ){
				tulem += "<div style=\"    float:left;\">-</div>";
				murrusõne=murrusõne.replace("-","");
			}
		}*/
		
   
		return ava + tulem + "<div style=\"    float: right;font-size: 16px;\"><div style=\"    border-bottom: solid 1px;\">" 
		+ murrusõne.split("/")[0] + "</div><div>"+ murrusõne.split("/")[1] +"</div></div></div>" + sulge;

	}else{
		return ava + murrusõne + sulge;
	}
	
}