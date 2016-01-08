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
			require('../include/DbConnection.php');
			
			$query2= 'SELECT * FROM roba';
			$result2 = mysql_query($query2) or die ("Error in query: $query2 " . mysql_error());
			$row2 = mysql_fetch_array($result2);
			$num_results2 = mysql_num_rows($result2);
			if ($num_results2 > 0){
			echo "<p>Vec postoje podaci u bazi!</p><a class='dugme_crveno_92plus4' href='../index.php'>Pocetna strana</a>";
			}else{
			
				$query= 'SELECT * FROM prenos_stan';
				$result = mysql_query($query) or die ("Error in query: $query " . mysql_error());
				$num_results = mysql_num_rows($result);
				if ($num_results > 0){
					echo "<p>Sinhronizacija je uradjena. Pocetno stanje je ubaceno u robe.</p>
						<a class='dugme_crveno_92plus4' href='../index.php'>Pocetna strana</a>";
				
					while($row = mysql_fetch_array($result))
					{
						$naziv_robe=$row['naziv_robe'];
						$cena_robe=$row['cena_robe'];
						$porez=$row['porez'];
						$jed_mere=$row['jed_mere'];
						$ruc=$row['ruc'];
						$kolicina=$row['kolicina'];
						mysql_query("INSERT INTO roba (naziv_robe, cena_robe, porez, stanje, jed_mere, ruc, poc_stanje)
							VALUES('".$naziv_robe."', '".$cena_robe."', '".$porez."', '".$kolicina."', '".$jed_mere."', '".$ruc."', '".$kolicina."' ) ") or die(mysql_error());
					}
				}
				else{
				echo "<p>Nema podataka</p><a class='dugme_crveno_92plus4' href='../index.php'>Pocetna strana</a>";
				}
			}
		?>
		</div>
	</body>
</html>