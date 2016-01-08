<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Izvod</title>

	<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">
	<script src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>
	
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
	<script>
		$(function() {
			$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			
			$("#validity_form").validity(function() {
	                    $("#biracdatuma")
	                        .require()
	                    });
		});
	</script>

</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		require("../include/DbConnection.php");

		if (isset($_POST['datum_izmena'])) {
			$idbank=$_POST['id_banke'];
			$broj_izvoda=$_POST['broj_izvoda'];
			
			$datum_izmena=$_POST['datum_izmena'];
			$dan2=strtotime( $datum_izmena );
			$datum_za_bazu=date("Y-m-d",$dan2);

			mysql_query("UPDATE bankaupis SET datum_izv='$datum_za_bazu' WHERE br_izvoda='$broj_izvoda'") or die(mysql_error());
			?>
			<p>Datum je izmenjen.</p>
			<form action="izvod5.php" method="post">
				<input type="hidden" name="datum" value="<?php echo $datum_izmena;?>"/>
				<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda;?>"/>
				<input type="hidden" name="id_banke" value="<?php echo $idbank;?>"/>
				<button type="submit" class="dugme_zeleno">Zavrsi</button>
			</form>
			<div class="cf"></div>
			<?php
		}

		else {
			$idbank=$_POST['id_banke'];
	     	$datum=$_POST['datum'];
			$broj_izvoda=$_POST['broj_izvoda'];
			
			$upitizv = mysql_query("SELECT datum_izv FROM bankaupis where banka='$idbank' AND br_izvoda='$broj_izvoda' ");
			$nizizv= mysql_fetch_array($upitizv);
			$datum_izv=($nizizv['datum_izv']);
			?>
			<form method="post" id="validity_form">
				<label>Datum</label>
				<input id="biracdatuma" type="text" name="datum_izmena" value="<?php echo date("d-m-Y",(strtotime($datum)));?>" class="polje_100_92plus4" />
				<p>Broj izvoda: <?php echo $broj_izvoda;?></p>
				<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda;?>"/>
				<input type="hidden" name="id_banke" value="<?php echo $idbank;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form action="izvod5.php" method="post">
				<input type="hidden" name="datum" value="<?php echo $datum;?>"/>
				<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda;?>"/>
				<input type="hidden" name="id_banke" value="<?php echo $idbank;?>"/>
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
			<?php
		} ?>
	</div>
</body>
</html>