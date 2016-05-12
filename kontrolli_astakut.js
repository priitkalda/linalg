
// erandite nimekiri
if( !(ülesande_number == 21 || ülesande_number == 72 || ülesande_number == 75)  ){
	var alg = $M(a[0]);
		console.log("Sylvesteri arvates on astak: " + alg.rank());
		
	if(  ak[ak.length-1].compare(alg.rank()) == 0 ){
		//$("#kast_"+ (	a.length-1) ).css("background-color","lightgreen");
		$("#kast_"+ (	a.length-1 )).attr("class", "kast õige");
		$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo õige");
		$kliendi_arvamus = 1;
		
	}else{
		
		//$("#kast_"+ (	a.length-1) ).css("background-color","red");
		$("#kast_"+ (	a.length-1) ).attr("class", "kast vale");
		$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo vale");
		$kliendi_arvamus=0;
	}
}