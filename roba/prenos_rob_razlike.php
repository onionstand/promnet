<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Prenos Robnih Razlika</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
		<?php
		require("../include/DbConnection.php");
		$imefajla = "prenosstanja.php";
		$fh = fopen($imefajla, 'w') or die("can't open file");
		?>

		<?php
		$podatak="<?php
		require('../../include/DbConnection.php');
		IF (mysql_query('TRUNCATE TABLE prenos_stan'))
		  {
		  echo '<!DOCTYPE html>
		  		<html>
				<head>
				<link rel=stylesheet type=text/css href=../../include/css/stil2.css>
				<title>Prenos auto</title>
				</head>
				<body>
				<div id=formpoz2>
				<p>Kreirana lista za prenos...</p>
				<a class=dugme_crveno_92plus4 href=../../index.php>Pocetna strana</a>
				</div>
				</body>
				</html>';
		  }
		ELSE
		  {
			echo 'Greska pri formatiranju tabele: ' . mysql_error();
		  }
		  ";
		fwrite($fh, $podatak);

		$upit = mysql_query("SELECT * FROM roba WHERE stanje>=1 OR 	kolicina>=1 ORDER BY naziv_robe ");
			while($niz = mysql_fetch_array($upit))
				{
				$naziv_robe=$niz['naziv_robe'];
				$kolicina=$niz['kolicina'];
				$cena_robe=$niz['cena_robe'];
				$ruc=$niz['ruc'];
				$porez=$niz['porez'];
				$jed_mere=$niz['jed_mere'];
				
				$zarez="'";
				
				$podatak='mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
				VALUES ('.$zarez.$naziv_robe.$zarez.', '.$zarez.$cena_robe.$zarez.', '.$zarez.$porez.$zarez.', '.$zarez.$jed_mere.$zarez.', '.$zarez.$ruc.$zarez.', '.$zarez.$kolicina.$zarez.')");
				';
				
				
				fwrite($fh, $podatak);
				}


				
		$podatak="?>";
		fwrite($fh, $podatak);
		fclose($fh);

		//zipovanje
		$zip = new ZipArchive();
		 
		 $ow = 1;
		 $file= "prenosstanja.zip";
		 if($zip->open($file,$ow?ZIPARCHIVE::OVERWRITE:ZIPARCHIVE::CREATE)===TRUE)
		 {
		   // Add the files to the .zip file
		   $zip->addFile("prenosstanja.php");
		   // Closing the zip file
		   $zip->close();
		   
		   // Above code will generate master.zip
		   // containing master.css
		 }

		//kraj zipovanja
		?>
		<p>Stanje je izvezeno... Preuzmi ga...</p>
		<a class="dugme_plavo_92plus4" href="prenosstanja.zip">Preuzmi prenos stanja.</a>
		<a class="dugme_crveno_92plus4" href="../index.php">Pocetna strana</a>
		</div>
	</body>
</html>