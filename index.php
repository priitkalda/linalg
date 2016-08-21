<!doctype html>

<html lang="et">
    <head>
		<meta charset="utf-8">
        <title>Lineaaralgebra</title>
        <META name="Priit Kalda" content="Name">
          
		<link rel="stylesheet" type="text/css" href="stiil.css">
		<script src="renderda_murd.js"></script>
		
	</head>
		 
	<body>
		<div class="keha" id="keha">
		  

		<div class="tööriistariba" id="tööriistariba">

			<div class="hetke_kordinaadid" id="hetke_kordinaadid"></div>
	 
	 
			<div class="tööriistariba_parem" id="tööriistariba_parem">
	 
				<a href="pdf/baka.pdf" target="_blank">Kasutusjuhend</a>
				<a href="index.php">Avaleht</a>
		
			</div>
	 
		</div>
	 

	 
		<?php
			$vajalikud = array();
			include_once 'andmebaas.php';
			
			if (isset($_GET['tüüp'])){
				// vale ülesande tüübi idendifikaator
				if (!($_GET['tüüp'] == 1 || $_GET['tüüp'] == 2|| $_GET['tüüp'] == 3 || $_GET['tüüp'] == 4 )){
					echo 'Vigane ülesande tüüp';
					die();
				}
				// ülesannete kataloog
				if(isset($_GET['lk'])){
					echo ülesanded($_GET['tüüp'], $_GET['lk']);
				}else{
					echo ülesanded($_GET['tüüp'], 0);
				}
				
			}
			else{
				// avaleht, url-i sees pole midagi
				include_once 'tutvustus.html';
				echo kõik_ülesanded();
			}
			// viidete loetelu jalusesse
			viited($vajalikud);
				
				
		 ?>


		</div> 
	</body>
</html>