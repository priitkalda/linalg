// see fail on mõeldud php-ga skripti sisse trükkimiseks, mitte html päisesse panekuks
var alg = $M(a[0]).minor(1,1,a[0].length,a[0].length);
console.log(alg.inspect());

var hetk_a_sõne = "";
for (var j = 0; j<a[aktiivne-2].length; j++){
	
	for (var jj = 0; jj<a[aktiivne-2][j].length; jj++){
		
		
		if(jj >= a[aktiivne-1].length){
			hetk_a_sõne += a[aktiivne-2][j][jj].toFraction(false) + " ";
		}
		
	}   
}	
if (üks==true){
	hetk_a_sõne=null;
}
//console.log(hetk_a_sõne);


// erijuht, kui algne maatriks on 1x1

if (alg.cols()>1){
	if (alg.inverse() != null){
		console.log("Sylvesteri arvates on pöördmaatriks: \n" + alg.inverse().inspect());
		var inv = alg.inverse();
	}else{
		var inv = $M([[]]);
	}
	//$kliendi_arvamus = 1;
}else{
	var inv = $M([a[0][0][0].inverse() ]);
	//$kliendi_arvamus = 0;
}

var hetk = $M(a[a.length-1] );

if(inv.eql(hetk)  ){
	
	//$("#kast_"+ (	a.length-1 )).css("background-color","lightgreen");
	$("#kast_"+ (	a.length-1 )).attr("class", "kast õige");
	$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo õige");
	$kliendi_arvamus = 1;
}else{
	
	//$("#kast_"+ (	a.length-1) ).css("background-color","red");
	$("#kast_"+ (	a.length-1) ).attr("class", "kast vale");
	$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo vale");
	$kliendi_arvamus = 0;
}
	