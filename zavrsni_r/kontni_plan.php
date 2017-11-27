<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Pregled Plata</title>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
		<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
		<script type="text/javascript">
		 jQuery(document).ready(function() {

			$("#form_konto_novi").validity(function() {
		                    $("#broj_kont")
		                        .require("Popuni polje.");
							$("#naziv_kont")
								.require("Popuni polje.");
								
		                });
			});
		</script>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<h2>Kontni plan</h2>
			<?php require("../include/DbConnectionPDO.php");
			if (isset($_GET['id_konta_brisanje'])) {
				$id_konta_brisanje=$_GET['id_konta_brisanje'];
				$upit_brisi = 'DELETE FROM konto WHERE id_kont = ?';
				$stmt_brisi = $baza_pdo->prepare($upit_brisi);
				$stmt_brisi->execute(array($id_konta_brisanje));
				
				$izbrisano = $stmt_brisi->rowCount();
				if (!$izbrisano) {
					if ($stmt_brisi->errorCode() == 'HY000') {
						$error = 'That record has dependent files in a child table, and cannot be deleted.';
					} else {
						$error = 'There was a problem deleting the record.';
					}
				}
			}

			if (isset($_POST['broj_kont_izmena'])) {
				$sql_ubaci = 'UPDATE konto SET broj_kont = ?, naziv_kont = ? WHERE id_kont = ?';
				$stmt_ubaci = $baza_pdo->prepare($sql_ubaci);
				$stmt_ubaci->execute(array($_POST['broj_kont_izmena'], $_POST['naziv_kont_izmena'], $_POST['id_kont_izmena']));
				$done = $stmt_ubaci->rowCount();
				if ($done) {echo "<p>Izmenjeno...</p>";}
			}

			if (isset($_GET['id_konta_uredi'])) {
				$id_konta_uredi=$_GET['id_konta_uredi'];
				$sql = 'SELECT id_kont, broj_kont, naziv_kont FROM konto
						  WHERE id_kont = ?';
				$query_uredi = $baza_pdo->prepare($sql);
				$query_uredi->bindColumn(1, $id_kont);
				$query_uredi->bindColumn(2, $broj_kont);
				$query_uredi->bindColumn(3, $naziv_kont);
				$OK = $query_uredi->execute(array($id_konta_uredi));
				$query_uredi->fetch();

				?>
				<p><b>Uredi:</b></p>
				<form method="post" action="kontni_plan.php" id="form_kont_izmena">
					<label>Broj konta:</label>
					<input type="text" name="broj_kont_izmena" class="polje_100_92plus4" value="<?php echo $broj_kont;?>" id="broj_kont_izmena" />
					<label>Naziv konta:</label>
					<input type="text" name="naziv_kont_izmena" class="polje_100_92plus4" value="<?php echo $naziv_kont;?>" id="broj_kont_izmena"/>
					<input type="hidden" name="id_kont_izmena" value="<?php echo $id_kont;?>"/>
					<button type="submit" class="dugme_zeleno" style="margin-bottom:30px;">Unesi</button>
				</form>
				<?php
			}

			if (isset($_POST['naziv_kont'])) {
				$stmt_ubaci = $baza_pdo->prepare ("INSERT INTO konto (broj_kont, naziv_kont) VALUES (:broj_kont, :naziv_kont)");
				$stmt_ubaci->bindValue (":broj_kont", $_POST['broj_kont']);
				$stmt_ubaci->bindValue (":naziv_kont", $_POST['naziv_kont']);
				$stmt_ubaci->execute ();
			}

			
			$sql = "SELECT * FROM konto ORDER BY broj_kont";
			$result = $baza_pdo->query($sql);
			$error = $baza_pdo->errorInfo();
			if (isset($error[2])) die($error[2]);
			?>
			<table>
				<tr>
					<th>ID Konta</th>
					<th>Broj konta</th>
					<th>Ime konta</th>
					<th></th>
					<th></th>
				</tr>
				<?php 
				foreach($result as $niz){
					?>
					<tr>
						<td><?php echo $niz['id_kont'];?></td>
						<td><?php echo $niz['broj_kont'];?></td>
						<td><?php echo $niz['naziv_kont'];?></td>
						<td>
							<a href="kontni_plan.php?id_konta_uredi=<?php echo $niz['id_kont'];?>">
								<img src='../include/images/olovka.png' alt='Pregledaj' title='Pregledaj'>
							</a>
						</td>
						<td>
							<a href="kontni_plan.php?id_konta_brisanje=<?php echo $niz['id_kont'];?>" onclick="return confirm('Potvrdite brisanje.');">
								<img src='../include/images/iks.png' alt='Pregledaj' title='Pregledaj'>
							</a>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
			<p><b>Novi konto:</b></p>
			<form method="post" id="form_konto_novi" action="kontni_plan.php">
				<label>Broj konta:</label>
				<input type="text" name="broj_kont" class="polje_100_92plus4" id="broj_kont" />
				<label>Naziv konta:</label>
				<input type="text" name="naziv_kont" class="polje_100_92plus4" id="naziv_kont"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Odustani</a>
		</div>
	</body>
</html>