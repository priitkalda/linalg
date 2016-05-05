<?php
	
$lahendid = $vastus;
$vabad = [];
$vabad_vaartused = [];

//sisendi lugemine
$i = 0;
foreach($lahendid as $l) {

	array_push ( $vabad, "c".($i+1)  );
	array_push ( $vabad_vaartused,  4  );
	$i++;
}


//vabade tundmatute asendamine
$ll = sizeof($lahendid);
for($i = 0; $i < $ll;$i++){
	$lv = sizeof($vabad);
	for($j = 0; $j < $lv;$j++){
		if( strpos($lahendid[$i],$vabad[$j])!==false ){
			$lahendid[$i]= str_replace($vabad[$j], $vabad_vaartused[$j], $lahendid[$i]);
		}				
	}
}

//echo  implode(" ",$vabad);
//echo  implode(" ",$vabad_vaartused);
//echo  implode(" ",$lahendid);


//kontroll
$result = ülesanne($id);

	$lõppvastus = true;
	
	$toores = ($result[0]['sisu']);
	
	$hakkliha = explode("\n", $toores);
	
	foreach($hakkliha as $result) {
		$sum = 0;
		$hakkliha2 = explode(" ", $result);
		$j = 0;
		foreach($hakkliha2 as $result2) {
			if($j < $ll){
				eval( '$mat = (' . $result2 . ');' );
				
				eval( '$lah = (' . $lahendid[$j] . ');' );
				
				$sum += $sum + ( $mat * $lah );
			}
			$j++;
			
		}
		
		echo $sum;
		echo "\n";
		//echo implode(" ",$lahendid);
		
		eval( '$mat = (' . end($hakkliha2) . ');' );
		echo $mat;
		if( $mat  != $sum ){
			$lõppvastus = false;
		}
	}

	


if ( $lõppvastus ){
	echo '1';
}else{
	echo '0';
}
?>