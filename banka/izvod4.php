<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		$("#unesi_dugme").focus();
	});
	 </script>
	<title>Izvod</title>
</head>
<body>
<div class="nosac_glavni_400">
	<?php
	require("../include/DbConnection.php");
	$idbank=$_POST['id_banke'];
	$broj_izvoda=$_POST['broj_izvoda'];

	$datum=$_POST['datum'];
	$dan2=strtotime( $datum );
	$datum_za_bazu=date("Y-m-d",$dan2);

	$broj_dok=$_POST['broj_dok'];
	$izlaz_novca=number_format($_POST['izlaz_novca'], 2,".","");
	$ulaz_novca=number_format($_POST['ulaz_novca'], 2,".","");
	$ziro_r=$_POST['ziro_r'];
	$svrha=$_POST['svrha'];


	$sifra_par=$_POST['partnersif'];

	$ubacivanje="INSERT INTO bankaupis (br_izvoda, datum_izv, sifra_par, broj_dok, ulaz_novca, izlaz_novca, ziro_rac, banka, svrha)
	VALUES
	('$broj_izvoda','$datum_za_bazu','$sifra_par','$broj_dok','$ulaz_novca','$izlaz_novca','$ziro_r','$idbank','$svrha')";
	if (!mysql_query($ubacivanje))
	  {die('Greska: ' . mysql_error());}
	echo "<p>Podaci su ubaceni...</p>";

	mysql_query("UPDATE dob_kup SET ziro_rac='".$ziro_r."' WHERE sif_kup=".$sifra_par) or die(mysql_error());
	?>
	<form action="izvod5.php" method="post">
		<input type="hidden" name="id_banke" value="<?php echo $idbank; ?>"/>
		<input type="hidden" name="datum" value="<?php echo $datum; ?>"/>
		<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda; ?>"/>
		<button type="submit" class="dugme_zeleno" id="unesi_dugme">Dalje</button>
	</form>
	<div class="cf"></div>
</div>
</body>
</html>