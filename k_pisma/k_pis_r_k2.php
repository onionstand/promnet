<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Knjizno pismo robno</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php require("../include/DbConnection.php"); 
			$brojkalk=$_POST["broj_kalk"];
			if (isset($_POST['br_k_pis'])){
				$br_k_pis=$_POST['br_k_pis'];
			}
			else {
				mysql_query("INSERT INTO k_pism_r (kod_p, dos_kal, dat_k) VALUES
				(1,'$brojkalk',CURDATE())");
				/*broja knjiznog pisma*/
				$br_k_pis=mysql_insert_id();
				echo "<h2>Broj knjiznog pisma: ".$br_k_pis."</h2>";
				
			}
			?>

			<table>
				<tr>
					<th>Redni<br />broj</th>
					<th>Roba</th>
					<th>Kolicina</th>
					<th>Cena</th>
					<th>Kol. za knizenje</th>
				</tr>
			<?php
			$i=1;
			$kalkupit=mysql_query("SELECT ulaz.id, ulaz.kol_kalk, ulaz.cena_k, ulaz.srob_kal,ulaz.rab_kalk, roba.naziv_robe FROM ulaz LEFT JOIN roba 
			ON ulaz.srob_kal=roba.sifra WHERE br_kal='$brojkalk'");
			while ($kalkniz=mysql_fetch_array($kalkupit))
			{
			 	?>
			 	<tr>
			 		<td><?php echo $i++;?></td>
			 		<td><?php echo $kalkniz['naziv_robe'];?></td>
			 		<td><?php echo $kalkniz['kol_kalk'];?></td>
			 		<td><?php echo $kalkniz['cena_k'];?></td>
			 		<td>
			 			<form action="k_pis_r_k3.php" method="post">
			 				<input type="hidden" name="broj_kalk" value="<?php echo $brojkalk;?>"/>
			 				<input type="hidden" name="br_k_pis" value="<?php echo $br_k_pis;?>"/>
			 				<input type="hidden" name="ulaz_id" value="<?php echo $kalkniz['id'];?>"/>
			 				<input type="hidden" name="sif_rob" value="<?php echo $kalkniz['srob_kal'];?>"/>
			 				<input type="hidden" name="rab_kalk" value="<?php echo $kalkniz['rab_kalk'];?>"/>
			 				<input type="hidden" name="naziv_robe" value="<?php echo $kalkniz['naziv_robe'];?>"/>
			 				<input type="hidden" name="kalk_cena" value="<?php echo $kalkniz['cena_k'];?>"/>
			 				<input type="hidden" name="kol_kalk" value="<?php echo $kalkniz['kol_kalk'];?>"/>
			 				<input type="text" name="k_kol" class="input"/>
			 		</td>
			 		<td>
			 				<input type="image" src="../include/images/olovka.png" title="Odaberi" />
			 			</form>
			 		</td>
			 	</tr>
			<?php
			}
			?>
		</div>
		<div class="cf"></div>
	</body>
</html>