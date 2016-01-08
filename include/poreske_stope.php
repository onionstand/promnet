<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/stil2.css">
		<title>Poreske stope</title>
		<script type="text/javascript" src="jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="form/jquery.validity.js"></script>
		<link rel="stylesheet" type="text/css" href="form/jquery.validity.css">

		<link rel="stylesheet" href="jquery/css/jquery.ui.all.css">
		<script src="jquery/jquery.ui.core.js"></script>
		<script src="jquery/jquery.ui.widget.min.js"></script>
		<script src="jquery/jquery.ui.datepicker.min.js"></script>
		<script src="jquery/jquery.ui.datepicker-sr-SR.js"></script>

		<script type="text/javascript">
		 jQuery(document).ready(function() {

			$("#obaveznaf").validity(function() {
		                    $("#procenat")
		                    	.require("Neophodno polje...")
								.match("number","Mora biti broj.");
							$("#opis")
		                        .require("Neophodno polje...");
		                    $("#tarifa")
								.match("number","Mora biti broj.")
								.require("Neophodno polje...");
							$("#biracdatuma")
								.require("Neophodno polje...");
		                });
			
			$(".input:visible:first").focus();
			$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			});
		</script>
	</head>
	<body>
	<div class="nosac_glavni_400">
		<?php require("DbConnection.php");

		if (isset($_POST['opis']) && ($_POST['datum']))
			{$datep=$_POST['datum'];
			$datep2=strtotime( $datep );
			$datum_por_stop=date("Y-m-d",$datep2);
			mysql_query("INSERT INTO poreske_stope (opis_stope, porez_procenat, tarifa_stope, porez_datum)
								VALUES('".$_POST['opis']."', '".$_POST['procenat']."', '".$_POST['tarifa']."', '".$datum_por_stop."')");
			}

		$result = mysql_query('SELECT * FROM poreske_stope');
		if (mysql_num_rows($result) > 0){
		?>
			<table class="tabele">
					<tr>
						<td>Kod: </td>
						<td>Opis: </td>
						<td>Procenat: </td>
						<td>Tarifa: </td>
						<td>Datum: </td>
					</tr>
			
			<?php while ($r = mysql_fetch_array($result)) { ?>
					<tr>
						<td><?php echo $r['id_poreske_stope'];?></td>
						<td><?php echo $r['opis_stope'];?></td>
						<td><?php echo $r['porez_procenat'];?></td>
						<td><?php echo $r['tarifa_stope'];?></td>
						<td><?php echo date("d.m.Y",strtotime($r['porez_datum']));?></td>
					</tr>
				
				<?php } ?>
			</table>
			<?php } ?>
		<div class="cf"></div>

		<p>Nova poreska stopa:</p>
		<form method="post" id="obaveznaf">
			<label>Opis:</label>
			<input type='text' name='opis' class='polje_100_92plus4' id='opis'/>
			<label>Procenat:</label>
			<input type='text' name='procenat' class='polje_100_92plus4' id='procenat'/>
			<label>Tarifa:</label>
			<input type='text' name='tarifa' class='polje_100_92plus4' id='tarifa'/>
			<label>Datum:</label>
			<input id="biracdatuma" type="text" name="datum" value="" class="polje_100_92plus4" />
			<button type='submit' class='dugme_zeleno'>Unesi</button>
		</form>
		<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
		<div class="cf"></div>
	</div>
	</body>
</html>