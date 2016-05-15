 <?php
	// ülesande toomine andmebaasist
	include_once 'andmebaas.php';
	if (isset($_GET['ülesanne'])){
		$result = ülesanne($_GET['ülesanne']);
		
		if( !(array_key_exists ( 0 ,$result) )){
			include_once('viga.html');
			die();
		}
		$toores = ($result[0]['sisu']);
		$tüüp = ($result[0]['tüüp']);
		$sammud = ($result[0]['sammud']);
		$hakkliha = explode("\n", $toores);
		$tulem = "[[";
		foreach($hakkliha as $result) {
			$tulem = $tulem . "[";
			$hakkliha2 = explode(" ", $result);
			foreach($hakkliha2 as $result2) {
				if (strpos($result2, '/') !== false) {
					$hakkliha3 = explode("/", $result2);
					$tulem = $tulem . "math.fraction(" . $hakkliha3[0] . ", " . $hakkliha3[1] . "),";
				}
				else if (strpos($result2, '.') !== false) {
					$tulem = $tulem . "math.fraction(" . $result2 . "),";
				}
				else {
					$tulem = $tulem . "math.fraction(" . $result2 . ", 1),";
				}
			}

			$tulem = $tulem . "],";
		}

		$tulem = $tulem . "]]";
		
		$a = [$tulem, $tüüp, $sammud];
	}else{
		include_once('viga.html');
		die();
	}
	
	function ava_js ($nimi){
		if ($fh = fopen($nimi, "r")) {
			return fread($fh,filesize($nimi));
			fclose($fh);
		}			
	}
 ?>
<!doctype html>
<html lang="et">
     <head>
		<meta charset="utf-8">
        <title><?php echo "Ülesanne ".$_GET['ülesanne']; ?></title>
        <META name="Priit Kalda" content="Name">
		<link rel="stylesheet" type="text/css" href="stiil.css">
        <link rel="stylesheet" type="text/css" media="print" href="print.css">
		<!-- lae kohalikud koopiad javascriptidest, kui pilves olevad pole saadaval -->
		<script src="http://cdnjs.cloudflare.com/ajax/libs/mathjs/3.1.0/math.min.js"></script>
		<script>
		if (typeof math == 'undefined') {
			document.write(unescape("%3Cscript src='math.min.js' type='text/javascript'%3E%3C/script%3E"));
		}
		</script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script>
		if (typeof jQuery == 'undefined') {
			document.write(unescape("%3Cscript src='jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
		}
		</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sylvester/0.1.3/sylvester.js"></script>
		<script>
		if (typeof Matrix == 'undefined') {
			document.write(unescape("%3Cscript src='sylvester.js' type='text/javascript'%3E%3C/script%3E"));
		}
		</script>
		<script src="renderda_murd.js"></script>
		<script>

// põhiprogramm
var kasVeerud = false;
var kasVahetada = false;
var kasSama = false;
var kasOotan = false;
var lukk = false;
var vajadus_õppida = false;

var kasArenda = false;
var kasDet = false;
var kasLvs = false;
var kasAstmed = false;
var kasPöörd = false;
			
var kasLvs_esitamine = false;	
var viimati_erind=false;
var tagasi=false;

// ajutised muutujad
var liidetav = 0;
var aktiivne = 0;
var s = 0;
var t = 0;

// ülesande andmed
var samme = <?php if($a[2]!=null) {echo $a[2];}else{echo '"?"';} ?>;
var samme_hetk = 0;

var ülesande_number = <?php echo $_GET['ülesanne']; ?>; // ülesande number
var a = <?php echo $a[0]; ?>; 				// maatriksite list
var ak = [math.fraction(1,1)];				// maatriksi kordajate list
var ad = [1];								// aktiivsete maatriksite arv

// kui pöördmaatriksi ülesanne, siis tee sylvester teegi abil ühikmaatriks kõrvale
<?php 
if ($a[1]==2){
	echo ava_js ('tee_yhikm.js');
}
?>

// näidis maatriksite listist
/*
var a =[ 
[ 
  [3, 2, 7, 5],
  [2, 6, 4, 3],
  [2, 6, 4, 3],
  [0, 1, 1, 2]
]
]
;
*/

var teated = {
"0" : "Peaks olema nullist erinev arv",
"õpi_juhis" : "Sisestage sinise kontuuriga tähistatud determinandi esine kordaja",
"vale" : "Päris nii see siiski pole",
"õige" : "Väga tubli. Õige.",
"vigane_sisend" : "Ei suuda tõlgendada teie kirjutatut",
"vale_lõpp" : "Päris nii see siiski pole. Võtke mõned sammud tagasi ja proovige midagi muud.",
"õige_lõpp" : "Väga tubli. Olete jõudnud õige vastuseni.",
"desync_klient1_serv0" : "Kuidagiviisi leiab teie arvuti, et teie vastus on õige, kuid meie andmete põhjal on teie vastus vale.",
"desync_klient0_serv1" : "Väga tubli. Meie andmetel on teie vastus õige.",
"lvs_esit_pooleli" : "Teil on lahendite esitamine pooleli. Kui te olete ümber mõelnud, vajutage tagasi.",
}

var sisend_v2li = '<input type="text" name="kordaja" id="kordaja" class="sisend_väli_kordaja" value="">';
var sisend_v2li_õpi = '<input type="text" name="õpi" id="õpi" class="sisend_väli_kordaja õpi" value="">';
var kordaja_hetk = 1;
var getInfoLahter = function(){
	$('#veeru_info' +(aktiivne-1) + (t-1)).empty();
	$('#rea_info_' +(aktiivne) + (s-1)).empty();
	if (kasVeerud){
		return $('#veeru_info' +(aktiivne-1) + (t-1));
	}else{
		return $('#rea_info_' +(aktiivne) + (s-1));
	}	
}
var teata = function(mitmessõne){
	
	$('#teatekeskus').empty();
	if(mitmessõne != undefined){
		$('#teatekeskus').append(teated[mitmessõne]);
		//setTimeout(function() {$("#teatekeskus").empty()}, 5000);
		
	}
}

var võta_samm_tagasi = function(){
	kordaja_hetk = 1;
	// kui pole algseisus
	teata();
	kasOotan = false;
	tagasi = true;
	lukk = false;
	vajadus_õppida = false;
	if(ad.length>1){
		samme_hetk--;
		värskenda_sammuloendurit();
		kasLvs_esitamine = false;
		
		for (var i = ad[ad.length-2]; i > 0 ; i-- ){
			rakenda_hiire_sündmused(a.length-i-ad[ad.length-1]);
			//$('#kast_'+ (	a.length-i-ad[ad.length-1]) ).css("background-color","orange");
			$('#kast_'+ (	a.length-i-ad[ad.length-1]) ).attr("class", "kast aktiivne");
			$('#kast_'+ (	a.length-i-ad[ad.length-1]) ).show();
		}
		
		for (var i = ad[ad.length-1]; i > 0 ; i-- ){
			$('#kast_'+(	a.length-i  ) ).remove();
		}
		
		for (var i = ad[ad.length-1]; i > 0 ; i-- ){
			a.pop();
			ak.pop();
		}
		ad.pop();
	}
}



	
var renderda_maatriks = function(mitmes){
	
	if (mitmes == undefined){
		mitmes = a.length-1;
	}
	
	aktiivne = aktiivne+1;
	
	var kast = document.createElement('div');
	
	var vaheinfo = document.createElement('div');
    vaheinfo.setAttribute('class', 'järelinfo');
    vaheinfo.setAttribute('id', 'järelinfo_'+(mitmes));
	vaheinfo.textContent = "->";
	
	var vaheinfo2 = document.createElement('div');
	
	if( 1== <?php echo $a[1] ?> && a[mitmes].length==0  ){
		vaheinfo2.setAttribute('class', 'eelinfo ainult');
	}else{
		vaheinfo2.setAttribute('class', 'eelinfo');
	}
    vaheinfo2.setAttribute('id', 'eelinfo_'+(mitmes));
	
	// ülesande tüüp on determiant
	if( 1== <?php echo $a[1] ?>   ){
		vaheinfo.textContent = "=";
		var korrutusmärk = "";
		if (a[mitmes].length > 0){
		    korrutusmärk = "*";
		}
		
		if (ak[mitmes].compare(1) !=0 || (a[mitmes].length <= 0 )  )  {
			vaheinfo2.innerHTML =  printMurd(ak[mitmes].toFraction(false), true)+korrutusmärk;
		}
		// ülesande tüüp on astak
	}else if(4== <?php echo $a[1] ?> ){
		
		vaheinfo.textContent = "=";
		vaheinfo2.textContent =  "rank";
	}
	
	kast.appendChild(vaheinfo2);
	
	var matDiv = document.createElement('div');
    matDiv.setAttribute('class', 'maatriksi_konteiner');
    matDiv.setAttribute('id', 'maatriks_'+(mitmes));
    kast.setAttribute('class', 'kast aktiivne');
    kast.setAttribute('id', 'kast_'+(mitmes));
	
	var tbl = document.createElement('table');
    tbl.setAttribute('id', 'maatriksi_tabel_'+(mitmes));
	// ülesande tüüp on determiant
	if( 1== <?php echo $a[1] ?>){
		
		tbl.setAttribute('style', 'border-left: 2px solid #000;border-right: 2px solid #000;');
		
	}
	
    var tbdy = document.createElement('tbody');
	
	
	for (var j = 0; j< a[mitmes].length; j++){
		
        var tr = document.createElement('tr');
		
		for (var jj = 0; jj< a[mitmes][j].length; jj++){
			
            var td = document.createElement('td'); 
			td.setAttribute('id', 'maatriksi_sisu');
			var tabeli_sisu_div = document.createElement('div');
			
			if(jj == (a[mitmes][j].length-1)){
				var metalahter_div = document.createElement('div');
				metalahter_div.setAttribute('id', 'rea_info_'+(mitmes+1)+j);
				metalahter_div.setAttribute('class', 'rea_info');
				tabeli_sisu_div.appendChild(metalahter_div);
			}
			if(j==0){
				var metalahter_div = document.createElement('div');
				metalahter_div.setAttribute('id', 'veeru_info'+(mitmes)+jj);
				metalahter_div.setAttribute('class', 'veeru_info');
				tabeli_sisu_div.appendChild(metalahter_div);
			}
											// ülesande tüüp on pöördmaatriks
			if(jj == a[mitmes].length && 2== <?php echo $a[1] ?>){
				td.setAttribute('style', 'border-left: 2px solid #000;');
			}
												// ülesande tüüp on lineaarvõrrandisüsteem
			if(jj == a[mitmes][j].length-2 && 3== <?php echo $a[1] ?>){
				td.setAttribute('style', 'border-right: 2px solid #000;');
			}

			//var node = document.createTextNode(   a[mitmes][j][jj].toFraction(false)  ) ;
			tabeli_sisu_div.innerHTML +=( printMurd(  a[mitmes][j][jj].toFraction(false)  ) );
			//tabeli_sisu_div.appendChild(node);
			
			td.appendChild(tabeli_sisu_div);
			tr.appendChild(td);
		}
		
		tbdy.appendChild(tr);
	}
	
	
    tbl.appendChild(tbdy);
	
	matDiv.appendChild(tbl);
	kast.appendChild(matDiv);
	
	kast.appendChild(vaheinfo);
	

	
	document.getElementById('keha').appendChild(kast);
	
	//document.getElementById('keha').appendChild(vaheinfo);
	rakenda_hiire_sündmused(mitmes);

};	

var värskenda_sammuloendurit = function(){
	$('#sammuloendur').empty();
	$('#sammuloendur').append(samme_hetk + '/' + samme);
};

var renderda_aktiivsed_maatriksid = function(mitmes){
	
	kordaja_hetk = 1;
	teata();
	samme_hetk++;
	värskenda_sammuloendurit();
	for (var i = ad[ad.length-1]; i > 0 ; i-- ){
		
		renderda_maatriks(a.length-i);	
		//console.log(mitmes);
		// õpiprogramm
								// ülesande tüüp on determiant
		if((aktiivne-1)==(mitmes) && 1== <?php echo $a[1] ?> && vajadus_õppida){
			lukk = true;
			vajadus_õppida = false;
			teata("õpi_juhis");
			
			$('#eelinfo_'+(mitmes) ).empty();
			$('#eelinfo_'+(mitmes) ).append(sisend_v2li_õpi);
			$('#eelinfo_'+(mitmes) ).append("*");
			$('#kast_'+(mitmes) ).attr("class", "kast õpi");
			$('#õpi').focus();
			
			$('#õpi').keypress(function(e) {
				if (e.which == 13) {
					//console.log("enter");
						try{ 
							if (  math.fraction( $('#õpi').val()).compare( ak[mitmes] )==0  ){
								lukk = false;
								$('#kast_'+(mitmes) ).attr("class", "kast aktiivne");
								console.log($('#õpi').val() + ", "+ ak[mitmes]);
								//$('#õpi').remove();
								$('#eelinfo_'+(mitmes) ).empty();
								$('#eelinfo_'+(mitmes) ).append(printMurd(ak[mitmes].toFraction(), true) + "*");
								teata("õige");
							}else{
								console.log($('#õpi').val() + ", "+ ak[mitmes]);
								$('#õpi').attr("class", "sisend_väli_kordaja vale");
								teata("vale");
							}
						}
						catch(e){
							vigane_kasutaja_sisend(e);
							
						}
				}
			});
			
		}		
		
		if ((a.length-i) != (a.length-1)){
			$('#järelinfo_'+(aktiivne-1) ).empty();
			$('#järelinfo_'+(aktiivne-1) ).append("+");
		}
		
	}

	for (var i = ad[ad.length-2]; i > 0 ; i-- ){
		
		$('#maatriksi_tabel_'+ (a.length - i - ad[ad.length-1]) +' td').unbind('click');
		$('#maatriksi_tabel_'+ (a.length - i - ad[ad.length-1]) +' td').unbind('mouseenter mouseleave');
		$('#eelinfo_'+ (a.length - i - ad[ad.length-1])).unbind('click');
		//$('#kast_'+ (a.length - i - ad[ad.length-1]) ).css("background-color","gray");
		$('#kast_'+ (a.length - i - ad[ad.length-1]) ).attr("class", "kast passiivne");
	}
}

// teeb koopia aktiivsetest maatriksitest, et siis koopiat muuta. Eelmine säilitatakse, et saaks sammu tagasi võtta
var klooni = function(){

	tempa = [];
	tempak = [];
	for (var i = ad[ad.length-1]; i > 0 ; i-- ){
			temp = [];
			for (var j = 0; j< a[a.length-i].length; j++){
				temp2 = [];
				for (var jj = 0; jj< (a[a.length-i][j]).length; jj++){
					temp2.push( a[a.length-i][j][jj].clone() );
				}
				temp.push(temp2.slice());
			}
			tempa.push(temp.slice());
			tempak.push(ak[ak.length-i].clone());

	}
	
	for (var j = 0; j< tempa.length; j++){
		a.push(tempa[j].slice());
		ak.push(tempak[j].clone());
	}
	ad.push(ad[ad.length-1]);	
}

// kui on mitu väikest determinanti siis võta ära nullidega liikmed
var eemalda_nullid = function(){

	tempa = [];
	tempak = [];
	eemalda_adst = 0;
	var summa;
	
	for (var i = ad[ad.length-1]; i > 0 ; i-- ){
		
		//$('#kast_' + (ak.length-i)).css("background-color", "red");
		//$('#kast_' + (ak.length-i)).fadeOut(1000);
		//$('#kast_' + (ak.length-i)).hide();
		
		if( ak[ak.length-i]!=0 
		//|| eemalda_adst > ad[ad.length-1]-2 		
		){
			temp = [];
			for (var j = 0; j< a[a.length-i].length; j++){
				temp2 = [];
				for (var jj = 0; jj< (a[a.length-i][j]).length; jj++){
					temp2.push( a[a.length-i][j][jj].clone() );
				}
				temp.push(temp2.slice());
			}
			
			if(a[a.length-i].length==0){
				if(summa===undefined){
					tempa.push(temp.slice());
					tempak.push(ak[ak.length-i].clone());
					summa = tempak.length-1;
				}else{
					tempak[summa] = math.add(tempak[summa], ak[ak.length-i].clone());
					eemalda_adst++;
				}
			}else{
				tempa.push(temp.slice());
				tempak.push(ak[ak.length-i].clone());
			}
			
		}else{
			eemalda_adst++;
		}
	}
	
	//kas üldse on vaja teha?
	if( tempa.length!=ad[ad.length-1] ){
		
		//peidame vanad ekraanilt
		for (var i = ad[ad.length-1]; i > 0 ; i-- ){
			//$('#kast_' + (ak.length-i)).css("background-color", "red");
			//$('#kast_' + (ak.length-i)).fadeOut(1000);
			$('#kast_' + (ak.length-i)).hide();
		}
		
		// erijuht, kui kõik on nullid
		if(ad[ad.length-1]-eemalda_adst==0){
			a.push([]);
			ak.push(math.fraction(0));
			ad.push(1);
			
		}else{
			// muudel juhtudel
			for (var j = 0; j< tempa.length; j++){
				a.push(tempa[j].slice());
				ak.push(tempak[j].clone());
			}
			ad.push(ad[ad.length-1]-eemalda_adst);			
		}
		

		renderda_aktiivsed_maatriksid();
		
		// kas on vaja vastust kontrollida
		if (ad[ad.length-1]==1 && a[a.length-1].length==0){
			kontrolli_vastust();
			$('#järelinfo_' + (ak.length-1)).empty();
			vormista_kast_arvuliseks_vastuseks(ak.length-1);
		}
	}
}


		<?php
		
		//vastuse automaatkontrolli testimise abi. Teeb muutuja alg kättesaadavaks brauseri konsoolis.
		/*
		if ($a[1]==1){
			echo '
		var alg = $M(a[0]);		
			';
		}else if ($a[1]==2){
			echo '
		var hetk = $M(a[a.length-1] );';
			
		}else if ($a[1]==3){
			echo '
			
		var alg = $M(a[0]);
			';
			
		}else{
			echo '
			
		var alg = $M(a[0]);
';
			
		} */
		?>
		
var kontrolli_vastust = function(üks){
	if(ad[ad.length-1]==1 ){
		tagasi = false;
		// kliendi poolel
		$kliendi_arvamus = -1;
		<?php
		if ($a[1]==1){
			echo ava_js ('kontrolli_determinanti.js');			
		}else if ($a[1]==2){
			echo ava_js ('kontrolli_p22rdmaatriksi.js');
			
		}else if ($a[1]==3){
			echo ava_js ('kontrolli_lvs.js');
			
		}else if ($a[1]==4){
			echo ava_js ('kontrolli_astakut.js');
		}
		?>
		
		// serveri poolel, POST-päring: vastus, ülesande number; server võrdleb andmebaasi väljaga
		$.post( 
		'kontroll.php', 
		{ v: 		<?php
		if ($a[1]==1){
			echo '(ak[ak.length-1]).toFraction(false)';			
		}else if ($a[1]==2){
			echo 'hetk_a_sõne';
			
		}else if ($a[1]==3){
			echo 'lahendid_alg';
			
		}else if ($a[1]==4){
			echo '(ak[ak.length-1]).toFraction(false)';
		}
		?>
		, i: ülesande_number  }, 
		function( data ){ 
			if (tagasi == false){
			if(data.indexOf("N") > -1){
				
				// usalda klienti igal juhul (serveris on NULL)
				if($kliendi_arvamus==1){
					//$("#kast_"+ (	a.length-1 )).css("background-color","darkgreen");
					
					$("#kast_"+ (	a.length-1 )).attr("class", "kast õige");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo õige");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Õige!");
					teata("õige_lõpp");
					
				}else if($kliendi_arvamus==0){
					//$("#kast_"+ (	a.length-1) ).css("background-color","darkred");
					$("#kast_"+ (	a.length-1) ).attr("class", "kast vale");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo vale");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Vale!");
					teata("vale_lõpp");
				}else{
					// siia ei jõua niikuinii (klient pole midagi arvanud)
					//$("#kast_"+ (	a.length-1 )).css("background-color","darkgoldenrod");
					$("#kast_"+ (	a.length-1 )).attr("class", "kast");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo");
					$("#järelinfo_"+ (	a.length-1 )).empty();
				}
				
			}
			else if(data.indexOf("1") > -1){
				// serveri arvates on vastus õige
				if($kliendi_arvamus==1){
					// SYNC (nii klient kui ka server nõustusid teineteisega, et on ÕIGE)
					//$("#kast_"+ (	a.length-1 )).css("background-color","darkgreen");	
					$("#kast_"+ (	a.length-1 )).attr("class", "kast õige");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo õige");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Õige!");
					teata("õige_lõpp");
				}else if($kliendi_arvamus==0){
					// DESYNC, usalda serverit (server ütles et on ÕIGE, klient ütles et on VALE), ilmselt sylvesteri või fractioni arvutuse viga
					//$("#kast_"+ (	a.length-1 )).css("background-color","darkgreen");	
					$("#kast_"+ (	a.length-1 )).attr("class", "kast õige");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo õige");
					//alert("DESYNC. Klient ütles et on VALE, server ütles et on ÕIGE");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Õige!");
					teata("desync_klient0_serv1");
				}else{
					// siia ei jõua niikuinii (klient pole midagi arvanud)
					//$("#kast_"+ (	a.length-1 )).css("background-color","darkgreen");
					$("#kast_"+ (	a.length-1 )).attr("class", "kast õige");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo õige");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Õige!");
					teata("õige_lõpp");
				}
			}else{
				// serveri arvates on vastus vale
				if($kliendi_arvamus==1){
					// DESYNC, usalda serverit (klient ütles et on ÕIGE, server ütles et on VALE), ilmselt pettus
					//$("#kast_"+ (	a.length-1) ).css("background-color","darkred");
					$("#kast_"+ (	a.length-1) ).attr("class", "kast vale");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo vale");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Vale!");
					//alert("DESYNC. Klient ütles et on ÕIGE, server ütles et on VALE");
					teata("desync_klient1_serv0");
				}else if($kliendi_arvamus==0){
					// SYNC (nii klient kui ka server nõustusid teineteisega, et on VALE)
					//$("#kast_"+ (	a.length-1) ).css("background-color","darkred");
					$("#kast_"+ (	a.length-1) ).attr("class", "kast vale");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo vale");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Vale!");
					teata("vale_lõpp");
				}else{
					// siia ei jõua niikuinii (klient pole midagi arvanud)
					//$("#kast_"+ (	a.length-1) ).css("background-color","darkred");
					$("#kast_"+ (	a.length-1) ).attr("class", "kast vale");
					$("#eelinfo_"+ (	a.length-1 )).attr("class", "eelinfo vale");
					$("#järelinfo_"+ (	a.length-1 )).empty();
					$("#järelinfo_"+ (	a.length-1 )).append("Vale!");
					teata("vale_lõpp");
				}
			}
			//console.log("server: " + data);
			}
		});

		$("#eelinfo_"+ (	a.length-1 )).unbind("click");		
		
		
		
	}
}

// rakenda hiire üle lohistamise sündmused just renderdatud maatriksitele
var rakenda_hiire_sündmused = function(mitmes){

	//$('td').unbind('click');
	//$('td').unbind('mouseenter mouseleave')

	
	
	if (mitmes == undefined){
		mitmes = a.length-1;
	}
	aktiivne = mitmes+1;  // spagetid
	var hetkelAktiivne = aktiivne;
	$('#maatriksi_tabel_'+(aktiivne-1)+' td').hover(
		function() {
			if (!lukk){
			if (aktiivne != hetkelAktiivne){
				kasOotan = false;
			}
			aktiivne = hetkelAktiivne;
			
			 s = parseInt($(this).closest('tr').index())+1;
			 t = parseInt($(this).index()) + 1;
			 
			if(kasArenda){
				getInfoLahter().append('A');
				
				if(kasVeerud == true){
					// v2rvimine
					$('#maatriksi_tabel_'+(aktiivne-1)+' td:nth-child(' + t + ')').addClass('värvitud_sinine');
				}else{
					// read
					$($(this).closest('tr')).find('td').each (function() {
						$(this).addClass('värvitud_sinine');
					}); 
				}
			}
			else if(kasDet){				
	
				$('#maatriksi_tabel_'+(aktiivne-1) +' td').addClass('värvitud_oranž');
				getInfoLahter().append('=' +sisend_v2li );
				kordaja_tekstivälja_tegevused();
			}
			else if(kasLvs){
					getInfoLahter().append('-' );
					var x = document.getElementById('maatriksi_tabel_'+(aktiivne-1)).getElementsByTagName("td");
					liidetav = 0;
					for (var j = 0; j<a[aktiivne-1][0].length; j++){
						
						if (  (a[aktiivne-1][s-1][j].compare( math.fraction(0) ))  == 0 ){

							x[j + (a[aktiivne-1][0].length*(s-1) ) ].setAttribute('class', 'värvitud_roheline');
						}else{
							liidetav=1;
							x[j + (a[aktiivne-1][0].length*(s-1) ) ].setAttribute('class', 'värvitud_punane');
						} 
					}
			}
			else if(kasAstmed){
				//astmed
				
				var x = document.getElementById('maatriksi_tabel_'+(aktiivne-1)).getElementsByTagName("td");
				//console.log(s +", "+ t+"= "+ (t + (a[aktiivne-1][0].length*(s-1))-1 )  );
				//x[t + (a[aktiivne-1][0].length*(s-1) )-1 ].setAttribute('class', 'värvitud_kollane');
				
				
				var astak = a[aktiivne-1].length;
				var aste = 0;
						// ülesande tüüp on lineaarvõrrandisüsteem
						if( 3== <?php echo $a[1] ?>){				
							var kaks=1;
						}else{
							var kaks=0;
						}
				
				for (var jj = 0; jj<a[aktiivne-1][0].length-kaks; jj++){
					var j6udnud_astmeni = false;
					var juba_liidetud = false;
					

					for (var j = a[aktiivne-1].length-1; j>-1; j--){
						
						if (  ((a[aktiivne-1][j][jj].compare( math.fraction(0) ))  == 0) && !j6udnud_astmeni  ){
							//console.log(aktiivne-1 +", "+ j+", "+ jj);
							x[jj + (a[aktiivne-1][0].length*(j) ) ].setAttribute('class', 'värvitud_oranž');
							if(!juba_liidetud){
								aste +=1;
								juba_liidetud=true;
							}
						}else{
							j6udnud_astmeni=true;			
							//x[jj + (a[aktiivne-1][0].length*(j) ) ].setAttribute('class', 'värvitud_roheline');
						} 
						
					} 				
				}
				//console.log(aste);
				getInfoLahter().append('r=' +sisend_v2li );
				$('#kordaja').val("");
				kordaja_tekstivälja_tegevused();
				
			}
			else if (kasPöörd){
				getInfoLahter().append('A<sup>-1<sup>');
				var x = document.getElementById('maatriksi_tabel_'+(aktiivne-1)).getElementsByTagName("td");
				liidetav = 0;
				for (var j = 0; j<a[aktiivne-1].length; j++){
					
					for (var jj = 0; jj<a[aktiivne-1][j].length; jj++){
						
						//x[j + (a[aktiivne-1].length*(jj) ) ].setAttribute('class', 'värvitud_kollane');
						
						
						if(jj >= a[aktiivne-1].length){
							x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_kollane');
						}
						else if(  (a[aktiivne-1][j][jj].compare( math.fraction(0) ))  == 0 && j != jj ){
							x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_roheline');
							liidetav += 1;
							
						}else if(  (a[aktiivne-1][j][jj].compare( math.fraction(1,1) ))  == 0 && j==jj ){
							x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_roheline');
							liidetav += 1;
							
						}else{
							x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_punane');
							
						}
						
					}   
				}				
			}
			else if(kasOotan){
				if(kasVeerud == true){
					// v2rvimine
					
					$('#maatriksi_tabel_'+(aktiivne-1)+' td:nth-child(' + t + ')').addClass('värvitud_oranž');
					
					// veerud
					if(kasVahetada) {
						getInfoLahter().empty();
						getInfoLahter().append('<span class=vaheta_ikoon_tekst>&#x21bb;</span>'+ 'V<sub>' + liidetav + '</sub>');
						// siin pole midagi vaja teha
					}
					//kasSama
					else if(liidetav == t) {
						// veeru korrutamine nullist erineva arvuga, vastavate t2histe joonistamine
						getInfoLahter().append('*'+ sisend_v2li);  //joonista t2his
						kordaja_tekstivälja_tegevused();
					}
					else{
						// veerule mingi muu veeru*k liitmine, vastavate t2histe joonistamine
						getInfoLahter().append('+' +  sisend_v2li+ 'V<sub>' + liidetav + '</sub>');
						
						kordaja_tekstivälja_tegevused();


					}
					

				}else{
					// read
					//v2rvimine TODO
					$($(this).closest('tr')).find('td').each (function() {
						$(this).addClass('värvitud_oranž');
					}); 

					if(kasVahetada) {
						getInfoLahter().append('<span class=vaheta_ikoon_tekst>&#x21bb;</span>'+ 'R<sub>' + liidetav + '</sub>');
						// siin pole midagi vaja teha
					}
					//kasSama
					else if(liidetav == s) {
						// rea korrutamine nullist erineva arvuga, vastavate t2histe joonistamine
						getInfoLahter().append('*' +sisend_v2li);
						kordaja_tekstivälja_tegevused();
					}
					else{
						// reale mingi muu rea*k liitmine, vastavate t2histe joonistamine
						getInfoLahter().append('+' +sisend_v2li + 'R<sub>' + liidetav + '</sub>');
						kordaja_tekstivälja_tegevused();
					}
				}
			}else{
				$('#hetke_kordinaadid').empty();
				$('#hetke_kordinaadid').append("("+s + ", " + t+")");
				
				if(kasVahetada) {
					getInfoLahter().append('<span class=vaheta_ikoon_tekst>&#x21bb;</span>');
				}
				
				if(kasVeerud == true){
					// v2rvimine
					$('#maatriksi_tabel_'+(aktiivne-1)+' td:nth-child(' + t + ')').addClass('värvitud_kollane');
				}else{
					// read
					$($(this).closest('tr')).find('td').each (function() {
						$(this).addClass('värvitud_kollane');
					}); 
				}
			}
		}},
		function() {
			getInfoLahter().empty();
			$('#maatriksi_tabel_'+(aktiivne-1)+' td').removeClass('värvitud_kollane');
			$('#maatriksi_tabel_'+(aktiivne-1)+' td').removeClass('värvitud_oranž');
			$('#maatriksi_tabel_'+(aktiivne-1)+' td').removeClass('värvitud_sinine');
			$('#maatriksi_tabel_'+(aktiivne-1)+' td').removeClass('värvitud_punane');
			$('#maatriksi_tabel_'+(aktiivne-1)+' td').removeClass('värvitud_roheline');
		}
	);
	
	// ülesande tüüp on determiant
	if( 1== <?php echo $a[1] ?>  ){
		// kordajate kokkuvõtmine, kui osadeterminandid on leitud
		$('#eelinfo_'+(aktiivne-1)).click(
		function() {
			if(!lukk){
				eemalda_nullid();
			}
		}
		);
	}	

// vaja teha iga kord, kui ette tuua sisestuse dialoog
var kordaja_tekstivälja_tegevused = function(){
	
	$( "#kordaja" ).keyup(function() {
		//console.log($( "#kordaja" ).val());
	    kordaja_hetk = $( "#kordaja" ).val();
	});
	
	if(kasAstmed==false){
		$('#kordaja').val(kordaja_hetk);	
		
	}
	
	$('#kordaja').focus();
	$('#kordaja').select();						  
	
	$('#kordaja').keypress(function(e) {
		if (e.which == 13) {
			//console.log("enter");
			hiirekliki_tegevus(mitmes+1);
		}
	});
}

function erind_vale_sisend(teade) {
   this.teade = teade;
}

var hiirekliki_tegevus = function(aktiivne){
	
		var milline = aktiivne-1+ad[ad.length-1];			
		if(kasArenda){
				
				klooni();
				ad.pop();
				// muuda klooni
				//a.pop();
				//ak.pop();
				
				a.splice(milline,1);
				var vanema_kordaja = ak[milline].clone();
				ak.splice(milline,1);
				
				milline = aktiivne-1;
				var b = [];
				var bak = [];
				
				if(kasVeerud == true){
					for (var ja = 0; ja<a[milline].length; ja++){
						
						temp = [];
						for (var j = 0; j< a[milline].length; j++){
							temp2 = [];
							if(j!=ja ){
								for (var jj = 0; jj< (a[milline][j]).length; jj++){
									if ((t-1)!=jj){
										temp2.push( a[milline][j][jj].clone() );
									}
								}
								temp.push(temp2.slice());
							}else{
									bak.push( math.multiply(vanema_kordaja, a[milline][j][t-1].clone() ) );
									if(  ((j+1 + t)%2)   !=0 ){
										bak[bak.length-1]=math.multiply(math.fraction(-1,1), bak[bak.length-1]);
									}
								}
						}
						b.push(temp.slice());
					}
				}else{
													
						for (var ja = 0; ja<a[milline].length; ja++){
							
							temp = [];								
							for (var j = 0; j< a[milline].length-1; j++){
											temp.push( [] );
									}
							for (var jj = 0; jj< (a[milline][0]).length; jj++){
								
								
								if(jj!=ja ){
									

									var tk = 0;
									for (var j = 0; j< a[milline].length; j++){
										if ((s-1)!=j){
											temp[tk].push( a[milline][j][jj].clone() );
											tk++;
										}
									}
								}else{
										bak.push( math.multiply(vanema_kordaja, a[milline][s-1][jj].clone() ) );
										if(  ((jj+1 + s)%2)   !=0 ){
											bak[bak.length-1]=math.multiply(math.fraction(-1,1), bak[bak.length-1]);
										}
									}
							}
							b.push(temp.slice());
						}
					}
					
				var adsse = ad[ad.length-1]-1;
				
				for(var j = 0; j < b.length; j++){
					a.push(b[j].slice() );
					
					ak.push( bak[j].clone() );
					adsse = adsse + 1;
					
					
				}
				ad.push(adsse);
				renderda_aktiivsed_maatriksid();
				

		}
		else if(kasDet){
			try{
				var kordaja = math.fraction(math.eval($('#kordaja').val() ));
				
				klooni();
				getInfoLahter().empty();
				
				getInfoLahter().append('='+printMurd( kordaja.toFraction(false) ));  //joonista t2his
				a[milline] = [];
				ak[milline] = math.multiply(kordaja,ak[milline]);
				renderda_aktiivsed_maatriksid();
				console.log(milline);
				if(ad[ad.length-1]==1){
					$('#järelinfo_' + milline).empty();
					vormista_kast_arvuliseks_vastuseks(milline);
					kontrolli_vastust();
				}else{

					var algg = $M(a[aktiivne-1]);
					console.log(algg.inspect());
					// erijuht, kui algne maatriks on 1x1
					if (algg.cols()>1){	
						console.log("Sylvesteri arvates on detrminant: " + algg.determinant());
						var det = algg.determinant();
					}else{
						var det = a[aktiivne][0][0].clone();
					}

					if(  kordaja.compare( det )==0 ){
						
						$('#maatriksi_tabel_'+(aktiivne-1) +' td').addClass('värvitud_roheline');
					}else{
						
						$('#maatriksi_tabel_'+(aktiivne-1) +' td').addClass('värvitud_punane');
					}

				}
			}catch(e){
				vigane_kasutaja_sisend(e);
			}
		}
		// nullidest rea eemaldamine
		else if(kasLvs){
			if (liidetav == 0){
				klooni();
				a[milline].splice(s-1, 1);
				renderda_aktiivsed_maatriksid();
			}
			
		}
		// astaku esitamine
		else if(kasAstmed){
			try{
				var kordaja = math.fraction($('#kordaja').val());
				getInfoLahter().empty();
				
				getInfoLahter().append('r='+printMurd( kordaja.toFraction(false) ));  //joonista t2his
				
					klooni();
					// ülesande tüüp on lineaarvõrrandisüsteem
					if( 3== <?php echo $a[1] ?>){

						var algg = $M(a[milline]);
						console.log("Sylvesteri arvates on astak: " + alg.rank());
							
						//console.log(getInfoLahter());
						if(  kordaja.compare(algg.rank()) == 0 ){
							$('#maatriksi_tabel_'+(aktiivne-1) +' td' + '.värvitud_oranž').addClass('värvitud_roheline');
							
						}else{
							$('#maatriksi_tabel_'+(aktiivne-1) +' td' + '.värvitud_oranž').addClass('värvitud_punane');
						}

					
						renderda_aktiivsed_maatriksid();
					}
					
				// ülesande tüüp on astak
				if( 4== <?php echo $a[1] ?>){
					a[milline] = [];
					ak[milline] = math.multiply(kordaja,ak[milline]);
					renderda_aktiivsed_maatriksid();
					$('#järelinfo_' + milline).empty();
					$('#eelinfo_' + milline).empty();
					$('#eelinfo_' + milline).append(''+printMurd( kordaja.toFraction(false) ));
				    vormista_kast_arvuliseks_vastuseks(milline);
					kontrolli_vastust();
				}
			}catch(e){
				vigane_kasutaja_sisend(e);
			}
		}
		// pole enam kasutusel
		else if(kasPöörd){
			
			//if(liidetav == (a[milline-1].length) * (a[milline-1].length)  ){
				klooni();
				for (var j=0;j<a[milline].length;j++){
					a[milline][j].splice(0, a[milline].length);
				}
				
				renderda_aktiivsed_maatriksid();
				
				$('#järelinfo_' + milline).empty();
				$('td').unbind('click');
				$('td').unbind('mouseenter mouseleave')
				
				kontrolli_vastust();
			//}
		}
		// jäta meelde esimene klõps
		else if(kasOotan){
			
			
			//var kordaja = $('#kordaja').val();
			try{   // exception handling for user input
				
				if (!kasVahetada){
					var väärtus = $('#kordaja').val();
					// var regex_email = /^\d*[0-9](|.\d*[0-9]| \d*[0-9])?$/;
					
					if( math.fraction(0).compare( math.fraction(väärtus  )) ==0){
						teata("0");
						throw new erind_vale_sisend("Arv_null");
					}
					
					// else if ( regex_email.test(väärtus)  ){
						// console.log("jah");
					// }else{
						// console.log("ei");
						// throw new erind_vale_sisend("Ei");
					// }
					
					var kordaja = math.fraction($('#kordaja').val());
				}
				kasOotan = false;
				
				//clone
				klooni();
				
				getInfoLahter().empty();
				
				if (kordaja<0){
					var pluss = '';
				}else{
					var pluss = '+';
				}
				
				if(kasVeerud){
					// veerud
					if(kasVahetada) {
						// veerud vahetada
						vajadus_õppida = true;
						getInfoLahter().append('<span class=vaheta_ikoon_tekst>&#x21bb;</span>'+ 'V<sub>' + liidetav + '</sub>');
						
						//muuda klooni
						for (var j = 0; j<a[milline].length; j++){
							var temp = a[milline][j][t-1].clone();
							a[milline][j][t-1] = a[milline][j][liidetav-1].clone();
							a[milline][j][liidetav-1] = temp.clone();
						}
						// ülesande tüüp on determiant
						if( 1== <?php echo $a[1] ?>  ){
							
							if(liidetav != t){
								ak[milline] = math.multiply(math.fraction(-1,1), ak[milline]); 
							}
												
						}

					}
					else if(liidetav == t) {
						// veeru korrutamine nullist erineva arvuga
						vajadus_õppida = true;
						getInfoLahter().append('*'+ printMurd( kordaja.toFraction(false), true ) );  //joonista t2his
						//muuda klooni
						for (var j = 0; j<a[milline].length; j++){
							a[milline][j][t-1] = math.multiply(kordaja, a[milline][j][t-1])  ;                //muuda m2lus
						}
						// ülesande tüüp on determiant
						if( 1== <?php echo $a[1] ?>  ){
							ak[milline] = math.multiply(kordaja.inverse(), ak[milline]); 
						}
					}
					else{
						// veerule mingi muu veeru*k liitmine
						getInfoLahter().append(pluss + printMurd( kordaja.toFraction(false),true )   + '*V<sub>' + liidetav + '</sub>');  //joonista t2his
						  
						//muuda klooni
						for (var j = 0; j<a[milline].length; j++){
							a[milline][j][t-1] = math.add( a[milline][j][t-1], math.multiply(kordaja, a[milline][j][liidetav-1]) ) ;                //muuda m2lus
						}
					}
					
				}else{
					// read
					if(kasVahetada) {
						// read vahetada
						vajadus_õppida = true;
						getInfoLahter().append('<span class=vaheta_ikoon_tekst>&#x21bb;</span>'+ 'R<sub>' + liidetav + '</sub>');
						
						//muuda klooni
						for (var j = 0; j<a[milline][0].length; j++){
							var temp = a[milline][s-1][j].clone();
							a[milline][s-1][j] = a[milline][liidetav-1][j].clone();
							a[milline][liidetav-1][j] = temp.clone();
						}
						// ülesande tüüp on determiant
						if( 1== <?php echo $a[1] ?>  ){
							if(liidetav != s){
								ak[milline] = math.multiply(math.fraction(-1,1), ak[milline]); 
							}
						}
					}
					else if(liidetav==s) {
						// rea korrutamine nullist erineva arvuga
						vajadus_õppida = true;
						getInfoLahter().append('*' +printMurd( kordaja.toFraction(false), true ) );
						
						//muuda klooni
						for (var j = 0; j<a[milline][0].length; j++){
							a[milline][s-1][j] = math.multiply(kordaja, a[milline][s-1][j])  ;                //muuda m2lus
						}
						// ülesande tüüp on determiant
						if( 1== <?php echo $a[1] ?>  ){
							ak[milline] = math.multiply(kordaja.inverse(), ak[milline]); 
						}
						
					}
					else{
						// reale mingi muu rea*k liitmine
						getInfoLahter().append(pluss + printMurd( kordaja.toFraction(false),true ) + '*R<sub>' + liidetav + '</sub>');
						
						//muuda klooni
						for (var j = 0; j<a[milline][0].length; j++){
							a[milline][s-1][j] = math.add( a[milline][s-1][j], math.multiply(kordaja, a[milline][liidetav-1][j]) ) ;                //muuda m2lus
						}
						
					}
			}

			$('#maatriksi_tabel_'+(aktiivne-1)+' td').removeClass('värvitud_oranž');
			$('#maatriksi_tabel_'+(aktiivne-1)+' td').removeClass('värvitud_kollane');
			
			renderda_aktiivsed_maatriksid(milline);
							}
		catch ( e ) {
				viimati_erind=true;
			  vigane_kasutaja_sisend(e);
		   }

			
			
		}else{
			kasOotan = true;
			// jäta meeldi esimesena klõpsatud veerg või rida
			if(kasVeerud == true){		
				liidetav = t;
			}else{
				liidetav = s;
			}
			
			
		}
		
} 

$('#maatriksi_tabel_'+(aktiivne-1)+' td').click(
	function(e) {
		// kas on pooleli kasutaja poolt mingi kordaja üle küsimine
		if (!lukk){
			//console.log("e.target.className: "+e.target.className+", this: "+this);
			if (e.target.className == "rea_info" || e.target.className == "veeru_info"|| e.target.className == "sisend_väli_kordaja"  ){
				return;
			}
			hiirekliki_tegevus(aktiivne);
			if(!viimati_erind){
				$(this).mouseover();
			}else{
				viimati_erind=false;
			}
		}
	}
);


}

var vigane_kasutaja_sisend = function(e){
	console.log("erind: " + e);
	//$('#kordaja').css("background-color","red");
	//$('#kordaja').attr("class", "second");
	if (e.teade!="Arv_null"){
		teata("vigane_sisend");
	}
	
	$('#kordaja').attr("class", "sisend_väli_kordaja punane");
	
	if($("#lvs_lah"+liidetav).hasClass("lvs1")){
		$("#lvs_lah"+liidetav).attr("class", "sisend_väli_kordaja lvs1 punane");
	}else{
		$("#lvs_lah"+liidetav).attr("class", "sisend_väli_kordaja lvs punane");
	}
	
	$("#õpi").attr("class", "sisend_väli_kordaja õpi punane");
	
	$('#kordaja').focus();
	$('#kordaja').select();
	  //alert("Error: " + e );
}

//argumentdiks false, kui maatriks pole pööratav kasutaja arvates, muul juhul kontrollitakse 
var pöördmaatriksi_kontrollimine = function(kasutaja_arvates_pööratav){
	if(kasutaja_arvates_pööratav){
		s=1;
		t=1;
		getInfoLahter().append('A<sup>-1<sup>');
		var x = document.getElementById('maatriksi_tabel_'+(aktiivne-1)).getElementsByTagName("td");
		liidetav = 0;
		for (var j = 0; j<a[aktiivne-1].length; j++){
			
			for (var jj = 0; jj<a[aktiivne-1][j].length; jj++){
				
				//x[j + (a[aktiivne-1].length*(jj) ) ].setAttribute('class', 'värvitud_kollane');
				
				
				if(jj >= a[aktiivne-1].length){
					x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_kollane');
				}
				else if(  (a[aktiivne-1][j][jj].compare( math.fraction(0) ))  == 0 && j != jj ){
					x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_roheline');
					liidetav += 1;
					
				}else if(  (a[aktiivne-1][j][jj].compare( math.fraction(1,1) ))  == 0 && j==jj ){
					x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_roheline');
					liidetav += 1;
					
				}else{
					x[jj + (a[aktiivne-1][j].length*(j) ) ].setAttribute('class', 'värvitud_punane');
					
				}
				
			}   
		}			

		klooni();
		for (var j=0;j<a[a.length-1].length;j++){
			a[a.length-1][j].splice(0, a[a.length-1].length);
		}
		
		renderda_aktiivsed_maatriksid();
		
		$('#järelinfo_' + (a.length-1)).empty();
		$('td').unbind('click');
		$('td').unbind('mouseenter mouseleave')
		
		kontrolli_vastust();	
	}else{
		a.push([[]]);
		ak.push(math.fraction(0));
		ad.push(1);
		renderda_aktiivsed_maatriksid();
		vormista_eelinfo_sõnaliseks_vastuseks("Pole pööratav");
		kontrolli_vastust(true);
		
	}
}

var vormista_eelinfo_sõnaliseks_vastuseks = function(sõna){
		$('#eelinfo_' + (a.length-1)).css("right","0");
		$('#järelinfo_' + (a.length-1)).css("left","170px");
		$('#järelinfo_' + (a.length-1)).empty();
		$('#eelinfo_' + (a.length-1)).append(sõna);
}

var vormista_kast_arvuliseks_vastuseks = function(milline){
	$('#järelinfo_' + milline).css("transform", "translateY(100%)");
	$('#eelinfo_' + milline).css("left", "0");
	$('#eelinfo_' + milline).css("right", "initial");
}

var lvs_lah_puuduvad = function(){
	if(!kasLvs_esitamine){
		kasLvs_esitamine = true;
		a.push([[]]);
		ak.push(math.fraction(0));
		ad.push(1);
		renderda_aktiivsed_maatriksid();
		
		vormista_eelinfo_sõnaliseks_vastuseks("Pole lahenduv");
		kontrolli_vastust();
	}
	else{
	teata("lvs_esit_pooleli");
}	
}

// argumendiks 1, kui on täpselt 1 lahend
var lvs_lahendite_kontrollimine = function(üks){
	if(!kasLvs_esitamine){
		samme_hetk++;
		kasLvs_esitamine = true;
		$('td').unbind('click');
		$('td').unbind('mouseenter mouseleave');
		klooni();
		
		var kast = document.createElement('div');
		kast.setAttribute('class', 'kast aktiivne');
		kast.setAttribute('id', 'kast_'+(aktiivne));
		
		var matDiv = document.createElement('div');
		matDiv.setAttribute('class', 'maatriksi_konteiner lvs');
		matDiv.setAttribute('id', 'maatriks_'+(aktiivne));
		
		
		var tbl = document.createElement('table');
		
		tbl.setAttribute('id', 'maatriksi_tabel_'+(aktiivne));
		
		var tbdy = document.createElement('tbody');



		for(var i = 0;i<a[a.length-1][0].length-1; i++){
			
			$('#veeru_info'+(aktiivne-1) + i).empty();
			$('#veeru_info'+(aktiivne-1) + i).append('X<sub>'+(i+1)+'</sub>');
			
	
	
			var tr = document.createElement('tr');
		
			
            var td = document.createElement('td'); 
			td.setAttribute('id', 'maatriksi_sisu');
	
			//var node = document.createTextNode(  'X'+(i+1)+'' ) ;
			//td.appendChild(node);
			td.innerHTML =  'X<sub>'+(i+1)+'</sub>=' ;
			
			tr.appendChild(td);	
            var td = document.createElement('td'); 
			td.setAttribute('id', 'maatriksi_sisu');
			
			var inp = document.createElement('input');
			inp.setAttribute('type', 'text');
			inp.setAttribute('id', 'lvs_lah'+i);
			if (üks != 1){
				inp.setAttribute('class', 'sisend_väli_kordaja lvs'); 
				inp.setAttribute('placeholder', 'nt. c1+1/2'); 
			}else{
				inp.setAttribute('class', 'sisend_väli_kordaja lvs1');
				inp.setAttribute('placeholder', 'nt. 1/2');
			}
			//inp.setAttribute('style', 'width: 80px');
			td.appendChild(inp);
				
			tr.appendChild(td);	
				
			tbl.appendChild(tr);
				
		}
		/*
		var inp = document.createElement('div');
		inp.setAttribute('class', 'eelinfo lvs');
		inp.setAttribute('id', 'eelinfo_'+i);
		
		if (üks != 1){
			inp.textContent="∞ lahendeid";
		}else{
			inp.textContent="1 lahend";
		}
		
		kast.appendChild(inp);
		*/		
		
		var inp = document.createElement('div');
		inp.setAttribute('class', 'järelinfo');
		inp.setAttribute('id', 'järelinfo_'+(aktiivne));
		kast.appendChild(inp);
		
		var inp = document.createElement('div');
		inp.setAttribute('class', 'järelinfo lvs');
		inp.setAttribute('id', 'kontroll_nupp_'+(aktiivne));
		inp.textContent="Kontroll.";
		kast.appendChild(inp);
		
		
		
		matDiv.appendChild(tbl);
		if (üks != 1){
			//matDiv.innerHTML += ",kus c<sub>1</sub>,...,c<sub>r</sub> ∈ ℝ ja r on astak";
		}
		kast.appendChild(matDiv);
		document.getElementById('keha').appendChild(kast); 
		
		$('#kontroll_nupp_'+(aktiivne)).click(function() {
			try{
				
				kontrolli_vastust(üks);
			}
			catch(e){
				vigane_kasutaja_sisend(e);
				
			}
			
		});
			
			
		}else{
	teata("lvs_esit_pooleli");
}	

}



		</script>
		<script src="dokument_valmis.js"></script>
	</head>
		 
		 
		 
	<body>   
<!--	<div id="abi_1" class="abi"></div>
<script src="pdfobject.min.js"></script>
<script>PDFObject.embed("pdf/baka.pdf", "#abi_1");</script>
-->
	<div>	 
		<div class="tööriistariba" id="tööriistariba">
			<input type="button" value="Tagasi" id="tagasi">
			 
			 <?php 
			echo '<div class="eraldaja"></div>';
			if ($a[1]==1  ||$a[1]==4  ||   isset($_GET['godmode'])  ){
				echo '<input type="radio" name="gender" id="raadio_veerud" value="Veerud">
					  <label for="raadio_veerud">Veerud</label>';
			}

			echo '<input type="radio" name="gender" id="raadio_read" value="Read">
				 <label for="raadio_read">Read</label>';	 
			echo '<div class="eraldaja0"></div>';
			echo '<input type="checkbox" name="tere" id="linnuke_liitkor" value="Liitmine ja korrutamine">
				 <label for="linnuke_liitkor">Liitmine ja korrutamine</label>';	 
			echo '<input type="checkbox" name="tere" id="linnuke_vahetus" value="Vahetus">
				 <label for="linnuke_vahetus">Vahetus</label>';	 
			echo '<div class="eraldaja2"></div>';
			
			if ($a[1]==1 || isset($_GET['godmode']) ){
				 echo '<input type="checkbox" name="tere" id="linnuke_arendamine" value="Arenda">
					   <label for="linnuke_arendamine">Arenda</label>   '		; 
				 echo '<div class="eraldaja2"></div>';
				 echo '<input type="checkbox" name="tere" id="linnuke_determinandi_esitamine" value="Determinandi">
					   <label for="linnuke_determinandi_esitamine">Determinant</label>';
				 echo '<div class="eraldaja2"></div>';
			}

			if ($a[1]==3  ||isset($_GET['godmode'])){
				 echo '<input type="checkbox" name="tere" id="linnuke_eemalda_nullidest_rida" value="Nullidest">
					   <label for="linnuke_eemalda_nullidest_rida">Nullidest rida</label>   '	 ;
				 echo '<div class="eraldaja2"></div>';
			}
			
			if ($a[1]==2|| isset($_GET['godmode'])){
			
				 // echo '<input type="checkbox" name="tere" id="linnuke_pöördmaatriksi_esitamine" value="pm">
				 // <label for="linnuke_pöördmaatriksi_esitamine">Pöördmaatriks</label>   '	 ;
				 // echo '<div class="eraldaja2"></div>';
				 
				 echo '<div id="rippmenüü" class="rippmenüü_hoidija"><div class="rippmenüü">
					   <input type="button" name="tere" id="nupp_pöörd_pole" value="Pöördmaatriksit ei leidu">
					   <input type="button" name="tere" id="nupp_pöörd_valmis" value="Pöördmaatriks on püstkriipsust paremal">
					   </div></div>';
				 echo '<input type="button" name="tere" id="nupp_pöörd" value="Pöördmaatriks">';
				 echo '<div class="eraldaja2"></div>';	 
			}

			if ($a[1]==4||
			//$a[1]==3|| 
			isset($_GET['godmode'])){
				echo '<input type="checkbox" name="tere" id="linnuke_astaku_esitamine" value="astk">
					  <label for="linnuke_astaku_esitamine">Astak</label>   ';	
				echo '<div class="eraldaja0"></div>';		 
			}	


			if ($a[1]==3|| isset($_GET['godmode'])){
			
				echo '<div id="rippmenüü_2" class="rippmenüü_hoidija"><div class="rippmenüü">		
					 <input type="button" name="tere" id="nupp_lvs_0" value="Ei leidu ühtegi lahendit">		 
					 <input type="button" name="tere" id="nupp_lvs_1" value="Leidub täpselt 1 lahend">		 
					 <input type="button" name="tere" id="nupp_lvs_1+" value="Leidub rohkem kui 1 lahend">		
					 </div></div>';
				echo '<input type="button" value="Üldlahend" id="nupp_lvs_esitamine">';
			}
			 
			 ?>

			<div class="tööriistariba_parem lahenda" id="tööriistariba_parem">
	 
				<!--<input type="button" name="tere" id="nupp_abi_1" value="Kasutusjuhend">-->
				<a href="pdf/baka.pdf" target="_blank">Kasutusjuhend</a>
				<?php 
					if (isset($_GET['lk'])){
					echo '<a href="index.php?tüüp='.$a[1].'&lk='.$_GET['lk'].'">Tagasi Kataloogi</a>';
					}
				?>
				<a href="index.php">Avaleht</a>
			</div>
	 
		</div>
 
		<div class="sammuloendur" id="sammuloendur"></div>
		<div class="hetke_kordinaadid" id="hetke_kordinaadid"></div>
		<div class="teatekeskus" id="teatekeskus"></div>
		<div class="laiuse_soovitus" id="laiuse_soovitus">Hoiatus: Nii kitsa akna suuruse korral võvad mõned programmi funktsioonid olla ekraani serva taga.</div>
		<div class="keha" id="keha"></div>


	 
	 </div>
     </body>
</html>