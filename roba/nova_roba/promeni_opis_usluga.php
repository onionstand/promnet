<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html" />
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../../include/css/stil2.css">
    <title>Promena Usluge</title>
    <script type="text/javascript" src="../../include/jquery/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="../../include/form/jquery.validity.js"></script>
    <link rel="stylesheet" type="text/css" href="../../include/form/jquery.validity.css">
    <script type="text/javascript">
      jQuery(document).ready(function() {

      	$("#obaveznaf").validity(function() {
      						$("#naziv_robe,#opis_usluge")
      						.require("Neophodno polje.");
      					});
      });
	</script>
</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		require("../../include/DbConnectionPDO.php");

		
		if (!empty($_POST['sifra_robe_ispravka'])) {
			$sql = 'UPDATE roba SET naziv_robe = ?, usluga_opis = ? WHERE sifra = ?';
			$stmt = $baza_pdo->prepare($sql);
			$done = $stmt->execute(array($_POST['naziv_robe'], $_POST['opis_usluge'], $_POST['sifra_robe_ispravka']));
	  		if ($done) {
	  			?>
	  			<h2>Ispravljeno...</h2>
	  			<form method="post" action="../../index.php">
					<button type="submit" class="dugme_crveno">Nazad</button>
				</form>
				<div class="cf"></div>
			<?php
	  		}
		}
		else{
			$roba_id=$_POST['sifra_robe'];
			
			$sql = "SELECT naziv_robe, usluga_opis FROM roba WHERE sifra = $roba_id";
			$result = $baza_pdo->query($sql);
			$row = $result->fetch();
			$naziv_robe=$row['naziv_robe'];
			$opis_robe=$row['usluga_opis'];
			?>
			<form method="post">
				<label>Naziv usluge:</label>
				<input type="text" name="naziv_robe" value="<?php echo $naziv_robe;?>" class="polje_100_92plus4" id="naziv_robe"/></td>
				<label>Opis usluge:</label>
				<textarea name="opis_usluge" class="polje_100_92plus4" rows="12" id="opis_usluge"><?php echo $opis_robe;?></textarea>
				<input type="hidden" name="sifra_robe_ispravka" value="<?php echo $roba_id;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form method="post" action="../../index.php">
				<input type="hidden" name="broj_fak_stampa" value="<?php echo $roba_id;?>"/>
				<button type="submit" class="dugme_crveno">Odustani</button>
			</form>
			<div class="cf"></div>
			<?php
		}
		?>
	</div>
</body>
</html>