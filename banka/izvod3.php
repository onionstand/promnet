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
	$sif_kup=$_POST['partnersif'];
	$broj_dok=$_POST['broj_dok'];
	$svrha=$_POST['svrha'];

	if (isset($_POST['uplata'])) {
			if ($_POST['uplata']=="uplata_dobavljacu"){
				$izlaz_novca=number_format($_POST['iznos_novca'], 2,".","");
				$ulaz_novca=0;
			}
			if ($_POST['uplata']=="uplata_od_kupca"){
				$izlaz_novca=0;
				$ulaz_novca=number_format($_POST['iznos_novca'], 2,".","");
			}
		}
	else{
		$izlaz_novca=0;
		$ulaz_novca=0;
	}

	//$izlaz_novca=number_format($_POST['izlaz_novca'], 2,".","");
	//$ulaz_novca=number_format($_POST['ulaz_novca'], 2,".","");

	$upit = mysql_query("SELECT naziv_kup,ziro_rac FROM dob_kup WHERE sif_kup='$sif_kup'");
	while($red = mysql_fetch_array($upit))
	  {
	  $zir_rac=$red['ziro_rac'];
	  $partner=$red['naziv_kup'];
	}
	?> 
	<p>Partner: <?php echo $partner; ?><br>
		Broj dokumenta: <?php echo $broj_dok; ?><br>
		<?php 
		if ($izlaz_novca!=0) echo "Uplata dobavljacu: " . $izlaz_novca;
		if ($ulaz_novca!=0) echo "Uplata od kupca: " . $ulaz_novca;
		?>
	</p>
		<form action="izvod4.php" method='post'>
			<label>Ziro racun:</label>
			<input type="text" name="ziro_r" value="<?php echo $zir_rac; ?>" class='polje_100_92plus4'/>
			<input type="hidden" name="partnersif" value="<?php echo $sif_kup; ?>"/>
			<input type="hidden" name="id_banke" value="<?php echo $idbank; ?>"/>
			<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda; ?>"/>
			<input type="hidden" name="broj_dok" value="<?php echo $broj_dok; ?>"/>
			<input type="hidden" name="izlaz_novca" value="<?php echo $izlaz_novca; ?>"/>
			<input type="hidden" name="ulaz_novca" value="<?php echo $ulaz_novca; ?>"/>
			<input type="hidden" name="datum" value="<?php echo $datum; ?>"/>
			<input type="hidden" name="svrha" value="<?php echo $svrha; ?>"/>
			<button type="submit" class="dugme_zeleno" id="unesi_dugme">Unesi</button>
		</form>
		<div class="cf"></div>
</div>
</body>
</html>