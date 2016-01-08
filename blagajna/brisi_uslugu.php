<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Usluge</title>
</head>
<body>
<div class="nosac_glavni_400">
	<?php require("../include/DbConnection.php");
	$broj_usluge=$_POST['broj_usluge'];

	$upit = mysql_query("SELECT * FROM plate
		WHERE
		id_usluge_porez='$broj_usluge' OR
		id_usluge_pio_rad='$broj_usluge' OR
		id_usluge_zdrav_rad='$broj_usluge' OR
		id_usluge_nezap_rad='$broj_usluge' OR
		id_usluge_pio_pred='$broj_usluge' OR
		id_usluge_zdrav_pred='$broj_usluge' OR
		id_usluge_nezap_pred='$broj_usluge'
		");
	$num_rows = mysql_num_rows($upit);
	$row_iz_plate_za_usl = mysql_fetch_array($upit);
	

	if ($num_rows>0){
		$danstr=strtotime($row_iz_plate_za_usl['datum_plate']);
		$datum_za_prikaz=date("d.m.Y",$danstr);
		echo "<p>Ova usluga moze da se izbrise samo iz pregleda plata.</p><p>ID plate za ovu uslugu je ";
		echo $row_iz_plate_za_usl['id_plate'] . " kreirana " . $datum_za_prikaz . "</p>";
	}

	else{
		echo "<h2>Izbrisano.</h2>";
		mysql_query("DELETE FROM usluge WHERE br_usluge='$broj_usluge'");
	}
	?>
	<form action="uslugeizv.php" method="post">
		<button type="submit" class="dugme_zeleno">Dalje</button>
	</form>
	<div class="cf"></div>
</div>
</body>