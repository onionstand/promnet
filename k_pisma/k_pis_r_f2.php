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
		$brojfak=$_POST["broj_dost"];
		IF (isset($_POST['br_k_pis']))
			{$br_k_pis=$_POST['br_k_pis'];}
		ELSE {
			mysql_query("INSERT INTO k_pism_r (kod_p, dos_kal, dat_k) VALUES
			(2,'$brojfak',CURDATE())");
			/*broja knjiznog pisma*/
			$br_k_pis= mysql_insert_id();
			echo "<h2>Broj knjiznog pisma: " . $br_k_pis . "</h2>";
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
		$fakupit=mysql_query("SELECT izlaz.id, izlaz.koli_dos, izlaz.cena_d, izlaz.srob_dos, izlaz.rab_dos, roba.naziv_robe 
							FROM izlaz 
							LEFT JOIN roba 
							ON izlaz.srob_dos=roba.sifra 
							WHERE br_dos='$brojfak'");
		while ($kalkniz=mysql_fetch_array($fakupit))
		{?>
			<tr>
				<td><?php echo $i++;?></td>
				<td><?php echo $kalkniz['naziv_robe'];?></td>
				<td><?php echo $kalkniz['koli_dos'];?></td>
				<td><?php echo $kalkniz['cena_d'];?></td>
				<td>
					<form action="k_pis_r_f3.php" method="post">
						<input type="hidden" name="broj_fak" value="<?php echo $brojfak;?>"/>
						<input type="hidden" name="br_k_pis" value="<?php echo $br_k_pis;?>"/>
						<input type="hidden" name="izlaz_id" value="<?php echo $kalkniz['id'];?>"/>
						<input type="hidden" name="sif_rob" value="<?php echo $kalkniz['srob_dos'];?>"/>
						<input type="hidden" name="rab_dos" value="<?php echo $kalkniz['rab_dos'];?>"/>
						<input type="hidden" name="naziv_robe" value="<?php echo $kalkniz['naziv_robe'];?>"/>
						<input type="hidden" name="cena_d" value="<?php echo $kalkniz['cena_d'];?>"/>
						<input type="hidden" name="koli_dos" value="<?php echo $kalkniz['koli_dos'];?>"/>
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
		<div class="cf"></div>
	</div>
</body>
</html>