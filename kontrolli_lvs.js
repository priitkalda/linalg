// see fail on mõeldud php-ga skripti sisse trükkimiseks, mitte html päisesse panekuks
var lahendid_alg = [];
var lahendid = [];
var vabad = [];
var vabad_vaartused = [];
var alg = $M(a[0]);
// sisendi lugemine

	for(var i = 0;i<a[a.length-1][0].length-1; i++){

		//$("#lvs_lah"+i).attr("class", "sisend_väli_kordaja lvs");
		//console.log($("#lvs_lah"+i).val() );
		
		
		lahendid_alg.push(  $("#lvs_lah"+i).val()  );
		lahendid.push(  $("#lvs_lah"+i).val()  );
		//console.log(üks);
		if(üks != 1){
			$("#lvs_lah"+i).attr("class", "sisend_väli_kordaja lvs");
			vabad.push(  "c"+(i+1)  );
			if(Math.floor((Math.random() * 2) + 1) ==1){
				vabad_vaartused.push(  Math.floor((Math.random() * 100) + 1)  );
			}else{
				vabad_vaartused.push(  Math.floor((Math.random() * 100) + 1)*(-1)  );
			}
		}else{
			
			$("#lvs_lah"+i).attr("class", "sisend_väli_kordaja lvs1");
		}
	}
	//console.log(vabad);

	// vabade tundmatute asendamine
	
	if(üks != 1){
		for(var i = 0;i<lahendid.length; i++){
			for(var j = 0;j<vabad.length; j++){
				if(lahendid[i].indexOf(vabad[j]) > -1){
					var vaba = new RegExp(vabad[j],"g");
					lahendid[i]=lahendid[i].replace(vaba, vabad_vaartused[j]);
				}
			
			}

		}
	}

//console.log(lahendid_alg);
//console.log(lahendid);

// kontroll

lõppvastus = true;
if (lahendid.length >0){
	for(var i=0; i<a[0].length ; i++){
		var sum = 0;
		var summa_sõne = "";
		for(var j=0; j<a[0][0].length-1 ; j++){
			liidetav = j;
			sum = math.add(sum, math.multiply(a[0][i][j],  math.fraction( math.eval(lahendid[j])) ) );
			summa_sõne = summa_sõne + "+(" + a[0][i][j] + "*" + math.eval(lahendid[j]) + ")";
		}
		//console.log(sum);
		//console.log(a[0][i][j]);
		console.log(summa_sõne+" = "+a[0][i][j]);
		if(sum.compare(a[0][i][j]) != 0 ){
			lõppvastus = false;
		}
	}

}else{
	lahendid_alg = null; // et saaks POST päringule anda
	// kontrolli, et lahendid puuduvad, kui on olemas lahend, siis lõppvastus -> false
	//console.log(alg.minor(1,1,a[0].length,a[0][0].length-1).rank()  +" ja "+ alg.rank() );
	if(alg.minor(1,1,a[0].length,a[0][0].length-1).rank()  == alg.rank()    ){ // lahendid on olemas (astak ilma viimase tulbata == astak)
		lõppvastus = false; // seega kasutaja otsus, et lahendid puuduvad on vale
	}
}




if(lõppvastus){
	
	//$("#kast_"+ (	a.length-1) ).css("background-color","lightgreen");
	$("#kast_"+ (	a.length-1 )).attr("class", "kast õige");
	$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo õige");
	$kliendi_arvamus = 1;
	
	
}else{
	
	//$("#kast_"+ (	a.length-1) ).css("background-color","red");
	$("#kast_"+ (	a.length-1) ).attr("class", "kast vale");
	$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo vale");
	$kliendi_arvamus = 0;
}
