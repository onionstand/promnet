<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$(".dugme_zeleno").focus();
			});
		</script>
		<title>Kalkulacija</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php"); 
			$sifra_kalk=$_POST['broj_kalkulaci'];
			$id_ulaz=$_POST['id_kalk'];

			$prestanje=mysql_query("SELECT * FROM ulaz WHERE id='$id_ulaz'");
			$prestanje2=mysql_fetch_array($prestanje);
			$prestanje3=$prestanje2['kol_kalk'];
			$sifrob=$prestanje2['srob_kal'];
			$cena_kalk=$prestanje2['cena_k'];
			$rabat2=$prestanje2['rab_kalk'];


			$robsta=mysql_query("SELECT * FROM roba WHERE sifra='$sifrob'");
			$robsta2=mysql_fetch_array($robsta);
			$robsta3=$robsta2['stanje'];
			$cena_robe=$robsta2['cena_robe'];
			$rob_ruc=$robsta2['ruc'];
			/*RUC*/	
				$kalkcena_min_rab=($cena_kalk/100)*(100-$rabat2);
				$m1=($cena_robe/($kalkcena_min_rab/100))-100;
				$m2=($m1*100)/(100+$m1);
				$novaruc=(($robsta3*$rob_ruc)-($prestanje3*$m2))/($robsta3-$prestanje3);
				echo "<h2>Izbrisano.</h2><p>Nova razlika u ceni robe: ".$novaruc."</p>";
				
			mysql_query("UPDATE roba SET ruc = '$novaruc' WHERE sifra='$sifrob'");
				
			/*RUC*/	

			mysql_query("DELETE FROM ulaz WHERE id='$id_ulaz' AND br_kal='$sifra_kalk'");

			$izmenastanja=$robsta3-$prestanje3;
			mysql_query("UPDATE roba SET stanje = '$izmenastanja' WHERE sifra='$sifrob'");
			echo "<p>Stanje robe: ".$izmenastanja."</p>";
			?>
			<form action="kalk_nov6.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $sifra_kalk; ?>"/>
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>