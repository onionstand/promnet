<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Promena napomene</title>
</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		require("../include/DbConnectionPDO.php");

		
		if (!empty($_POST['napomena'])) {
			$sql = 'UPDATE profak SET napomena = ? WHERE broj_prof = ?';
			$stmt = $baza_pdo->prepare($sql);
			$done = $stmt->execute(array($_POST['napomena'], $_POST['broj_profak']));
	  		if ($done) {
	  			?>
	  			<h2>Ispravljeno...</h2>
	  			<form method="post" action="profak5.php">
					<input type="hidden" name="broj_profak" value="<?php echo $_POST['broj_profak'];?>"/>
					<button type="submit" class="dugme_crveno">Nazad</button>
				</form>
				<div class="cf"></div>
			<?php
	  		}
		}
		else{
			$brojfak=$_GET['brojfak'];
			
			$sql = "SELECT napomena FROM profak WHERE broj_prof = $brojfak";
			$result = $baza_pdo->query($sql);
			$row = $result->fetch();
			$napomena=$row['napomena'];
			?>
			<form method="post">
				<label>Napomena:</label>
				<textarea name="napomena" class="polje_100_92plus4" rows="12">
					<?php echo $napomena;?>
				</textarea>
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form method="post" action="profak5.php">
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_crveno">Nazad</button>
			</form>
			<div class="cf"></div>
			<?php
		}
		?>
	</div>
</body>
</html>