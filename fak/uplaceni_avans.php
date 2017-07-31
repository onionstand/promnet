<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Uplaceni AVANS</title>
</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		require("../include/DbConnectionPDO.php");

		
		if (isset($_POST['uplaceni_avans'])) {
			$sql = 'UPDATE dosta SET uplaceni_avans = ? WHERE broj_dost = ?';
			$stmt = $baza_pdo->prepare($sql);
			$done = $stmt->execute(array($_POST['uplaceni_avans'], $_POST['broj_fak_stampa']));
	  		if ($done) {
	  			?>
	  			<h2>Ispravljeno...</h2>
	  			<form method="post" action="faktura.php">
					<input type="hidden" name="broj_fak_stampa" value="<?php echo $_POST['broj_fak_stampa'];?>"/>
					<button type="submit" class="dugme_crveno">Nazad</button>
				</form>
				<div class="cf"></div>
			<?php
	  		}
		}
		else{
			$brojfak=$_GET['brojfak'];
			
			$sql = "SELECT uplaceni_avans FROM dosta WHERE broj_dost = $brojfak";
			$result = $baza_pdo->query($sql);
			$row = $result->fetch();
			$uplaceni_avans=$row['uplaceni_avans'];
			?>
			<form method="post">
				<label>Uplaceni avans:</label>
				<input type="text" name="uplaceni_avans" value="<?php echo $uplaceni_avans;?>" class="polje_100_92plus4"/>
				<input type="hidden" name="broj_fak_stampa" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form method="post" action="faktura.php">
				<input type="hidden" name="broj_fak_stampa" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_crveno">Nazad</button>
			</form>
			<div class="cf"></div>
			<?php
		}
		?>
	</div>
</body>
</html>