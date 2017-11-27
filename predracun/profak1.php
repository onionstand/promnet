<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Predracun</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
		<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
		<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#firma").AddIncSearch({
			        maxListSize: 4,
			        maxMultiMatch: 50,
			        selectBoxHeight: 400,
			        warnMultiMatch: 'top {0} matches ...',
			        warnNoMatch: 'nema poklapanja...'
		    	});
			$("#obaveznaf").validity(function() {
				$("#firma")
		        .require("Polje je neophodno...");
		        $("#rok_placanja")
		        .require("Polje je neophodno...")
				.match("number","Mora biti broj.");
		    });
			$("#firma").focus();
			
			});
		</script>
	</head>
	<body>
		<?php require("../include/DbConnectionPDO.php"); ?>
		<div class="nosac_glavni_400">
			<form id="obaveznaf" action="profak2.php" method="post">
			<label>Kupac:</label>
			<select id='firma' name='partnersif' size='1' class='polje_100'>
				<option value=''>Odaberi</option>
				<?php
				$upit = "SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup";
				foreach ($baza_pdo->query($upit) as $red) {
					$naziv_kup=$red['naziv_kup'];
					$sif_kup=$red['sif_kup'];
					echo "<option value='$sif_kup'>$naziv_kup</option>";
				}
				?>
			</select>
			<label>Rok placanja:</label>
			<input type="text" name="rok_placanja" class="polje_100_92plus4" id="rok_placanja"/>
			
			<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<div class="cf"></div>
			<a href="javascript: windowNoviKupac()" class="dugme_plavo_92plus4 print_hide">Novi Partner</a>
			<script>
				function windowNoviKupac(){
					window.open("../partneri/partner_novi1.html", "_blank","location=1,status=1,scrollbars=1, width=500,height=700");
				} 
			</script>
			<div class="cf"></div>
			<form action="../index.php" method="post">
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>