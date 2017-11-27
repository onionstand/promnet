<?php
require("../include/DbConnectionPDO.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Glavna Knjiga</title>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<link rel="stylesheet" type="text/css" href="../include/tablesorter/style.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="../include/tablesorter/jquery.tablesorter.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#sorttabela").tablesorter();
			});
		</script>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<form method="post" id="knjiga_po_kontu" action="knjiga_po_kontu.php">
				<label>Broj konta:</label>
				<input type="text" name="naziv_kont" class="polje_100_92plus4" id="naziv_kont"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<?php
			if (isset($_POST['naziv_kont'])) { ?>

				
					<?php 
					$sql = 'SELECT
							glknjiga.id_glknjiga,
							glknjiga.sifradok,
							glknjiga.brdok,
							glknjiga.brkonta,
							glknjiga.datdok,
							glknjiga.duguje,
							glknjiga.potraz,
							glknjiga.opis,
							glknjiga.prokont,
							konto.naziv_kont
							FROM glknjiga
							LEFT JOIN konto ON glknjiga.brkonta=konto.broj_kont
							WHERE brkonta= :post_brkonta
							ORDER BY datdok';
					$stmt = $baza_pdo->prepare($sql);
					$stmt->bindParam(':post_brkonta', $_POST['naziv_kont'], PDO::PARAM_STR);
					$stmt->bindColumn('id_glknjiga', $id_glknjiga);
					$stmt->bindColumn('sifradok', $sifradok);
					$stmt->bindColumn('brdok', $brdok);
					$stmt->bindColumn('brkonta', $brkonta);
					$stmt->bindColumn('datdok', $datdok);
					$stmt->bindColumn('duguje', $duguje);
					$stmt->bindColumn('potraz', $potraz);
					$stmt->bindColumn('opis', $opis);
					$stmt->bindColumn('prokont', $prokont);
					$stmt->bindColumn('naziv_kont', $naziv_kont);
					$stmt->execute();
					$numRows = $stmt->rowCount();

					if ($numRows) { ?>
						<p>Tabela moze da se sortira</p>
						<table id="sorttabela" class="tablesorter">
							<thead>
								<tr>
									<th>ID</th>
									<th>Sifra dok.</th>
									<th>Br. dok.</th>
									<th>Br. konta</th>
									<th>Opis konta</th>
									<th>Datum</th>
									<th>Duguje</th>
									<th>Potrazuje</th>
									<th>Opis</th>
									<th>Prokont</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$duguje_zbir=0;
							$potraz_zbir=0;
							while ($stmt->fetch()) { 
								$duguje_zbir+=$duguje;
								$potraz_zbir+=$potraz;

								?>
								<tr>
									<td><?php echo $id_glknjiga; ?></td>
									<td><?php echo $sifradok; ?></td>
									<td><?php echo $brdok; ?></td>
									<td><?php echo $brkonta; ?></td>
									<td><?php echo $naziv_kont; ?></td>
									<td><?php echo $datdok; ?></td>
									<td><?php echo $duguje; ?></td>
									<td><?php echo $potraz; ?></td>
									<td><?php echo $opis; ?></td>
									<td><?php echo $prokont; ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
						<table>
							<thead>
								<tr>
									<th>Zbir duguje:</th>
									<th>Zbir potrazuje:</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo number_format($duguje_zbir, 2,".",",");?></td>
									<td><?php echo number_format($potraz_zbir, 2,".",",");?></td>
								</tr>
							</tbody>
						</table>
					<?php }
					else { echo "<p>Nema konta.</p>";}
			} ?>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
			<button class="dugme_plavo print_hide" onClick='window.print()' type='button'>Stampa</button>
			<div class="cf"></div>
		</div>
	</body>
</html>