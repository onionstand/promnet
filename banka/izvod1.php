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
		$idbank=$_POST['id_banke'];

		$upitbank = mysql_query("SELECT ime_banke FROM banke WHERE id_banke='$idbank' ");
		$nizbank= mysql_fetch_array($upitbank);
		$bank=$nizbank['ime_banke'];
		?>
			<?php
			echo "<h2>Banka: ".$bank."</h2>";

			$upitizv = mysql_query("SELECT MAX(br_izvoda) AS brizvoda FROM bankaupis where banka='$idbank'");
			$nizizv= mysql_fetch_array($upitizv);
			$izvod=($nizizv['brizvoda'])+1;
			?>
			<form method="post" id="validity_form" action="izvod2.php">
				<label>Datum</label>
				<input id="biracdatuma" type="text" name="datum" value="" class="polje_100_92plus4" />
				
				<p>Broj izvoda: <?php echo $izvod;?></p>
				<input type="hidden" name="broj_izvoda" value="<?php echo $izvod;?>"/>
				
				<input type="hidden" name="id_banke" value="<?php echo $idbank;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form action="../index.php" method="post">
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
		<div class="cf"></div>
	</div>
</body>
</html>