	
	
var alg = $M(a[0]);

// erijuht, kui algne maatriks on 1x1
if (alg.cols()>1){	
	console.log("Sylvesteri arvates on detrminant: " + alg.determinant());
	var det = alg.determinant();
	$kliendi_arvamus = 1;
}else{
	var det = a[0][0][0].clone();
	$kliendi_arvamus = 0;
}

if(  ak[ak.length-1].compare( det )==0 ){
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


