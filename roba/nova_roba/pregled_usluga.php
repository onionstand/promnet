<?php
require("../../include/DbConnectionPDO.php");

function PretragaPoTerminu($ime_polja, $termin_pretrage,$query_tip){
		
		global $baza_pdo;
		$OK = false;
		if ($query_tip==1){	
			$sql = "SELECT * FROM roba WHERE ".$ime_polja." LIKE :termin_pretrage AND usluga_opis is not NULL";
		}
		if ($query_tip==2){
			$sql = "SELECT * FROM roba WHERE ".$ime_polja." = :termin_pretrage AND usluga_opis is not NULL";
		}
		if ($query_tip==3){
			$sql = "SELECT * FROM roba WHERE usluga_opis is not NULL";
		}
		
        $stmt = $baza_pdo->prepare($sql);

        //$stmt->bindParam(':ime_polja', $ime_polja, PDO::PARAM_STR);
        $stmt->bindParam(':termin_pretrage', $termin_pretrage, PDO::PARAM_STR);
		
		$stmt->bindColumn('sifra', $sifra);
		$stmt->bindColumn('naziv_robe', $naziv_robe);
		$stmt->bindColumn('cena_robe', $cena_robe);
		$stmt->bindColumn('porez', $porez);
		$stmt->bindColumn('stanje', $stanje);
		$stmt->bindColumn('ruc', $ruc);
		$stmt->bindColumn('usluga_opis', $usluga_opis);

        $stmt->execute();
        $OK = $stmt->rowCount();
		
		
		

        if ($OK) {?>
        	<table>
			<tr>
				<th>Sifra</th>
				<th>Naziv robe</th>
				<th>Cena</th>
				<th>Porez</th>
				<th>Stanje</th>
				<th>RUC</th>
				<th>Opis Usluge</th>
			</tr>
			<?php
			while ($stmt->fetch()){
			?>
				<tr>
					<td><?php echo $sifra;?></td>
					<td><?php echo $naziv_robe;?></td>
					<td><?php echo $cena_robe;?></td>
					<td><?php echo $porez;?></td>
					<td><?php echo $stanje;?></td>
					<td><?php echo $ruc;?></td>
					<td><?php echo $usluga_opis;?></td>
					<td><form action="../../roba/kartica_rob.php" method="post">
							<input type="hidden" name="sifra_robe" value="<?php echo $sifra;?>"/>
							<input type="image" src="../../include/images/kartica.png" title="Pogledaj karticu"/>
						</form>
					</td>
					<td><form action="promeni_opis_usluga.php" method="post">
							<input type="hidden" name="sifra_robe" value="<?php echo $sifra;?>"/>
							<input type="image" src="../../include/images/olovka.png" title="Promeni naziv ili opis"/>
						</form>
					</td>
				</tr>
			<?php
			}
			?>
			</table>
			<?php
		}
        else {
        	$error = $stmt->errorInfo();
        	if (isset($error[2])) {
        	  $error = $error[2];
			  echo $error;
            }
        }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../../include/css/stil2.css">
		<title>Roba</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
				<?php
				if (isset($_POST['ime_robe'])) {
					PretragaPoTerminu("naziv_robe", '%'.$_POST['ime_robe'].'%', 1);
				}
				if (isset($_POST['sifra_robe'])) {
					PretragaPoTerminu("sifra", $_POST['sifra_robe'],2);
				}
				if (isset($_POST['sva_roba'])) {
					PretragaPoTerminu("sifra", $_POST['sva_roba'],3);
				}
				?>
			<div class="cf"></div>
			<form method="post">
				<label>Ime usluge:</label>
				<input type="text" name="ime_robe" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_plavo">Trazi</button>
			</form>
			<form method="post">
				<label>Sifra usluge:</label>
				<input type="text" name="sifra_robe" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_plavo">Trazi</button>
			</form>
			<form method="post">
				<label>Sva roba:</label>
				<input type="hidden" type="text" name="sva_roba" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_plavo">Prikazi</button>
			</form>
			<div class="cf"></div>
			<a href="../../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>