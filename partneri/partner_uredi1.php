<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html" />
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		
		<link rel="stylesheet" type="text/css" href="../include/footable/footable.core.css">
		<script src="../include/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
		<script src="../include/footable/footable.js?v=2-0-1" type="text/javascript"></script>
		<title>Partner</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			require("../include/DbConnection.php");

			if (isset($_POST['trazip'])){
				?>
				<table id="tabele" class="footable">
					<thead>
						<tr>
							<th>Sifra</th>
							<th>Ime</th>
							<th data-hide="phone,tablet">Post. broj</th>
							<th data-hide="phone">Mesto</th>
							<th data-hide="phone">Ulica</th>
							<th data-hide="phone,tablet">Ugo. rabat</th>
							<th data-hide="phone,tablet">Ziro racun</th>
							<th data-hide="phone,tablet">Ziro racun 2</th>
							<th data-hide="phone,tablet">Telefon</th>
							<th data-hide="phone,tablet">PIB</th>
							<th data-hide="phone,tablet">Maticni br.</th>
						</tr>
					</thead>
					<tbody>
				<?php
				$trazip=$_POST['trazip'];
				$upit= mysql_query("SELECT * FROM dob_kup WHERE naziv_kup LIKE '%$trazip%' ");
				while ($partneri1 = mysql_fetch_array($upit))
				{
					$sifrap=$partneri1["sif_kup"];
					?>
					<tr>
						<td style="text-align:left;"><?php echo $sifrap;?></td>
						<td><?php echo $partneri1["naziv_kup"];?></td>
						<td><?php echo $partneri1["postbr"];?></td>
						<td><?php echo $partneri1["mesto_kup"];?></td>
						<td><?php echo $partneri1["ulica_kup"];?></td>
						<td><?php echo $partneri1["rab_ugo"];?></td>
						<td><?php echo $partneri1["ziro_rac"];?></td>
						<td><?php echo $partneri1["ziro_rac2"];?></td>
						<td><?php echo $partneri1["tel"];?></td>
						<td><?php echo $partneri1["pib"];?></td>
						<td><?php echo $partneri1["mat_br"];?></td>
						<td>
							<form action='partner_uredi2.php' method='post'>
								<input type='hidden' name='sifra_p' value='<?php echo $sifrap;?>'/>
								<input type='image' src='../include/images/plus.png' alt='Odaberi' />
							</form>
						</td>
					</tr>
				<?php	
				}
				?>
				</tbody>
				</table>
			<?php
			}	
			?>
			<form action="" method="post">
				<label>Pretraga partnera:</label>
				<input type="text" name="trazip" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_zeleno">Trazi</button>
			</form>
			<form action="../index.php" method="post">
				<button type="submit" class="dugme_crveno">Otkazi</button>
			</form>
			<div class="cf"></div>
		</div>
		<script type="text/javascript">
			$(function () {$('table').footable();});
        </script>
	</body>
</html>