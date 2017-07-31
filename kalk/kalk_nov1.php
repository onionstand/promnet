<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
		<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
		<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
		<script type="text/javascript">
			jQuery(document).ready(function() {
			    jQuery("#firma").AddIncSearch({
			        maxListSize: 4,
			        maxMultiMatch: 50,
			        selectBoxHeight: 400,
			        warnMultiMatch: 'top {0} matches ...',
			        warnNoMatch: 'nema poklapanja...'
			    });
				 $("#obaveznaf").validity(function() {
			                    $(".polje_100_92plus4",".polje_100")
			                        .require("Polje je neophodno...");
			                    $("#rok_placanja")
			                    	.match("number","Mora biti broj.");
			                });

				var $mail = $("#dostavnica_kopiraj");
				$("#faktura_kopiraj").keyup(function() {
			    	$mail.val( this.value );
				});

				$("#firma").focus();
				
			});
		</script>
	</head>
	<body>
		<?php require("../include/DbConnection.php"); ?>
		<div class="nosac_glavni_400">
			<form id="obaveznaf" action="kalk_nov2.php" method="post">
				<label>Dobavljac:</label>
				<select id='firma' name='partnersif' size='1' class='polje_100'>
					<option value=''>Odaberi</option>
					<?php
					$upit = mysql_query("SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup");
					while($red = mysql_fetch_array($upit)){
						$naziv_kup=$red['naziv_kup'];
						$sif_kup=$red['sif_kup'];
						?>
						<option value='<?php echo $sif_kup;?>'><?php echo $naziv_kup;?></option>
						<?php } ?>
				</select>
				<label>Faktura:</label>
				<input type="text" name="faktura" class="polje_100_92plus4" id="faktura_kopiraj"/>
				<label>Dostavnica:</label>
				<input type="text" name="dostavnica" class="polje_100_92plus4" id="dostavnica_kopiraj"/>
				<label>Rok placanja:</label>
				<input type="text" name="rok_placanja" class="polje_100_92plus4" id="rok_placanja"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form action="../index.php" method="post">
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>