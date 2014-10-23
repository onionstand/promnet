<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$(".dugme_zeleno").focus();
			});
		</script>
		<title>profaktura</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnection.php");
			$brojprofak=$_POST['broj_profak'];
			$imerob=$_POST['naziv_robe'];
			$fak_kol=$_POST['fak_kol'];
			$fak_rab=$_POST['fak_rab'];
			$cena_r=$_POST['cena_r'];
			$porez=$_POST['porez'];
			$jed_mere=$_POST['jed_mere'];
			/*ubaci u bazu*/
			mysql_query("INSERT INTO profakrob (br_profak, naziv_robe, koli_profak, jed_mere, cena_profak, rab_dos, porez)
						VALUES
						('$brojprofak' , '$imerob','$fak_kol','$jed_mere','$cena_r','$fak_rab','$porez')");
			?>
			<p>Roba je uneta.</p>
			<div class="cf"></div>
			<form action="profak5.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $brojprofak; ?>"/>
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>