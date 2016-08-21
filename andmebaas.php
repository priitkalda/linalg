
<?php
$conn;

function ühenda(){
	global $conn;

	$servername = "localhost";
	$username = "root";
	$password = "";
	$andmebaas = "maatriksid";


	try {
		$conn = new PDO("mysql:host=$servername;dbname=$andmebaas", $username, $password);
		$conn->exec("SET CHARACTER SET utf8");

		// set the PDO error mode to exception

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// echo "Connected successfully";

	}

	catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
};

// kui pole php myadminiga ligipääsu, siis saab selle ajutiselt sisse kommenteerida ja avada selle lehe, siis kirjutatakse andmebaas üle uue versiooniga
/*
if ($fh = fopen($_SERVER['DOCUMENT_ROOT']."/maatriksid.sql", "r")) {
$loetud = fread($fh,filesize($_SERVER['DOCUMENT_ROOT']."/maatriksid.sql"));
fclose($fh);
}
ühenda();
$stmt = $conn->prepare($loetud);
$stmt->execute();
$conn = null;
*/

// serveripoolne vastuse kontroll
function kontrolli_vastust($vastus, $id){
	ühenda();
	global $conn;
	$stmt = $conn->prepare("SELECT tüüp,vastus FROM ülesanne WHERE id= :id");
	$stmt->execute(array('id' => $id) );	
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$result = $stmt->fetchAll();
	
	if($result[0]['tüüp']  == 3){
		// LVS
		//echo  implode(" ",$vastus);
		//echo gettype($vastus);
		echo 'N';
		//include_once('kontroll_lvs.php');
		
	}
	
    // kliendi pool otsustab tagasisaadud ühe või nulli põhjal, kas vastus on õige
	else if ($result[0]['vastus']  == null){
		echo 'N';
	}
	else if ($result[0]['tüüp']  == 1 or $result[0]['tüüp']  == 4 ){
		// astak või determinant
		if ($result[0]['vastus']  == $vastus){
			echo '1';
		}else{
			echo '0';
		}
	
	}else if($result[0]['tüüp']  == 2){
		// pöördmaatriks
		$asendatav = array("[","]","\n","\r","\r\n",","," ");
		$arg1 = str_replace($asendatav,"",$result[0]['vastus']);
		$arg2 = str_replace($asendatav,"",$vastus);
		
		if ( $arg1 == $arg2 ){
			echo '1';
		}else{
			echo '0';
		}
	}

	global $conn;
}

// joonistab (echo'b) ekraanile maatriksi ja lingi sellele, nagu on avalehel 
function renderda_ülesande_link($sql_rida){
			
	global $vajalikud;	
	$hakkliha = explode("\n", $sql_rida['sisu']);
	$suvaline_v2rv = sprintf( "#%06X\n", mt_rand( 0, 0x222222 ));
	$tulem = "<div class=\"kast_avaleht\" style=\"background-color:" .$suvaline_v2rv. "\">";
	
	$tulem = $tulem . "<div class=\"maatriksi_konteiner_avaleht\">";
	if(isset($_GET['lk'])){
		$tulem = $tulem . '<a href="lahenda.php?lk='.$_GET['lk'].'&ülesanne=' . $sql_rida['id'] . '">ülesanne ' . $sql_rida['id'] . '</a> ';
	}else{
		$tulem = $tulem . '<a href="lahenda.php?ülesanne=' . $sql_rida['id'] . '">ülesanne ' . $sql_rida['id'] . '</a> ';
	}
	if ($sql_rida['allikas'] != null){
		$tulem = $tulem . '<sup><a href="#' . $sql_rida['allikas'] . '">['.$sql_rida['allikas'].']</a></sup>';
		array_push ($vajalikud, $sql_rida['allikas']);
	}
	$tulem = $tulem . "<table><tbody>";
	foreach($hakkliha as $result) {
		$tulem = $tulem . "<tr>";
		$hakkliha2 = explode(" ", $result);
		
		foreach($hakkliha2 as $result2) {
			$tulem = $tulem . "<td><div>";
			
			if ( strpos($result2, '/') !== false ) {
				//$hakkliha3 = explode("/", $result2);
				
				$tulem = $tulem .  '<script>document.write(printMurd("'.str_replace(array("\r\n", "\r", "\n"), "", $result2).'"))</script>' ;
			}
			else {
				$tulem = $tulem . $result2 ;
			}
			$tulem = $tulem . "</div></td>";
		}

		$tulem = $tulem . "</tr>";
	}
	$tulem = $tulem . "</tbody></table></div></div>";			
	
	echo $tulem;
}
		
// toob selle id-ga ülesande
function ülesanne($id){
	ühenda();
	global $conn;
	//$stmt = $conn->prepare("SELECT sisu, tüüp, sammud FROM ülesanne WHERE id=" . $id . ";");
	//$stmt->execute();
	
	$stmt = $conn->prepare('SELECT sisu, tüüp, sammud FROM ülesanne WHERE id= :id');
	$stmt->execute(array('id' => $id) );	
	
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$result = $stmt->fetchAll();
	$conn = null;
	return $result;

};

// avalehe moodustamine
function kõik_ülesanded(){
	ühenda();
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM tüüp");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$result = $stmt->fetchAll();
	
	foreach($result as $r) {
		echo '<b>' . $r['kirjeldus'] . '</b><br><div class="kastide_organisaator_avaleht">';
		$stmt = $conn->prepare("SELECT id, sisu, allikas FROM ülesanne WHERE tüüp= :id and peida_kataloogist!=1");
		$stmt->execute(array('id' => $r['id'] ) );	
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		$i = 0;
		foreach($result as $s) {
			renderda_ülesande_link($s);
			//echo '<a href="lahenda.php?ülesanne=' . $s['id'] . '">ülesanne ' . $s['id'] . '</a>';
			$i+= 1;
			if ($i > 5) {
				break;
			}
		}

			echo '</div><br>';
		echo '<a href="index.php?tüüp=' . $r['id'] . '&lk=0">Rohkem ülesandeid</a>';
			echo '<br><br>';
	}

	$conn = null;
};

// ülesannete algallikate loetelu
function viited($vajalikud){	
	if (count($vajalikud) >0 ){
		ühenda();
		global $conn;	
		

		$stmt = $conn->prepare("SELECT * FROM viited WHERE id= :id");
		$stmt->execute(array('id' => implode(" or id=",$vajalikud)) );	

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		
			echo '<br><div class="kastide_organisaator_avaleht viited"><b>Viited</b><br>';
			foreach($result as $r) {
				echo '<a name="'.$r['Id'].'">'.$r['Id'].'. '.$r['pealkiri'].'<br></a>';

			}
			echo '</div>';
		
		$conn = null;
	}
};

// ülesannete kataloogi sirvimise lehekülg
function ülesanded($tp, $lk){
	ühenda();
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM tüüp WHERE id= :tp");
	$stmt->execute(array('tp' => $tp) );	
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$r = $stmt->fetchAll();
	
	$stmt = $conn->prepare("SELECT id,sisu,allikas FROM ülesanne WHERE tüüp=:tp and peida_kataloogist!=1");
	$stmt->execute(array('tp' => $tp) );	
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$result = $stmt->fetchAll();
	
	echo '<b>' . $r[0]['kirjeldus'] . '</b><br><div class="kastide_organisaator_avaleht">';

	$loendur = 0;
	
	for ($i = $lk * 10; $i < ($lk * 10) + 10; $i++) {
		if (isset($result[$i])) {
			$loendur++;
			renderda_ülesande_link($result[$i]);
			//echo '<a href="lahenda.php?ülesanne=' . $result[$i]['id'] . '">ülesanne ' . $result[$i]['id'] . '</a>';
			//echo '<br>'.nl2br ( $result[$i]['sisu']).'<br><br>';
		}
	}
	echo '</div>';
	
	if ($lk > 0) {
			$lkMiinusYks = $lk - 1;
			echo '<br><a href="index.php?tüüp=' . $tp . '&lk=' . $lkMiinusYks . '">Eelmised ülesanded</a><br><br>';
		}
	
	
	if ($loendur >= 10) {		

		$lkPlussYks = $lk + 1;
		echo '<br><a href="index.php?tüüp=' . $tp . '&lk=' . $lkPlussYks . '">Rohkem ülesandeid</a><br><br>';
		
		
	}
	else if ($loendur == 0) {
		echo 'Pole';
	}

	$conn = null;
};
?>