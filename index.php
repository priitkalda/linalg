<!doctype html>

<html lang="et">
     <head>
			<meta charset="utf-8">
          <title>Lineaaralgebra</title>
          <META name="Priit Kalda" content="Name">
          
<link rel="stylesheet" type="text/css" href="stiil.css">
		  </head>
		  

	 <script src="renderda_murd.js"></script>
		  
		  <body>
		  
		  
		  <div class="keha" id="keha">
		  
		  
		 <div class="tööriistariba" id="tööriistariba">

	 
	
	
	<div class="hetke_kordinaadid" id="hetke_kordinaadid">
	 </div>
	 
	 
	 <div class="tööriistariba_parem" id="tööriistariba_parem">
	 	<a href="index.php">Avaleht</a>
		

	 </div>
	 
	 </div>
	 

	 
  <?php
		$vajalikud = [];
		include_once 'andmebaas.php';
		

		
		
		if (isset($_GET['tüüp'])){
			
			if(isset($_GET['lk'])){
				echo ülesanded($_GET['tüüp'], $_GET['lk']);
			}else{
				echo ülesanded($_GET['tüüp'], 0);
			}
			
		}
		else{
			echo "<b>Tere</b><br><div class=\"kastide_organisaator_avaleht viited\">
	 <a>Külastate hetkel Priit Kalda Tartu Ülikooli informaatika eriala bakalaurusetöö (juhendajad: Valdis Laan ja Siim Karus) raames loodud lineaaralgebra ülesannete, täpsemalt maatriksiülesannete lahendamise õppimist abistavat veebikeskkonda. See sisaldab ülesandekogu ja elementaarteisenduste rakendajat. Lahendaja peab endiselt otsustama, kuidas kasutades elementaarteisendusi saada maatriks ülesandes nõutud kujule. Kuid teisenduse rakendamine ehk maatriksi sisu (antud juhul arvude) algoritmiline töötlemine ei ole lahendaja poolt vajalik.</a>
	 </div>
	 <br><br>";
			echo kõik_ülesanded();
		}
		
		viited($vajalikud);
		
		
  ?>

		
		<!--<a href="lahenda.php?ülesanne=1">ülesanne 1</a>-->

	 </div>


	
     
     </body>
</html>