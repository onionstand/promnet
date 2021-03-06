<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Izvestaj prodaje</title>
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">

	<script src="../include/jquery/jquery-1.6.2.min.js" type="text/javascript"></script>

	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>

	<script>
		$(function() {
			$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			$( "#biracdatuma2" ).datepicker($.datepicker.regional[ "sr-SR" ]);
		});
	</script>
</head>
<body>
<?php require("../include/DbConnection.php");
if (isset($_POST['datumod'])&& ($_POST['datumdo']))
{
	?>
	<div class="nosac_sa_tabelom">

		<div class='memorandum screen_hide'>
			<?php include("../include/ConfigFirma.php");
			echo $inkfirma; 
			$od=$_POST['datumod'];
			$od2=strtotime( $od );
			$datumod=date("Y-m-d",$od2);
			$do=$_POST['datumdo'];
			$do2=strtotime( $do );
			$datumdo=date("Y-m-d",$do2);
			?>
		</div>
		<div class='cf'></div>
		<h2>Periodicni izvestaj fakturisane realizacije za period od <?php echo date("d.m.Y",$od2);?> do <?php echo date("d.m.Y",$do2);?></h2>
		<table>
			<tr>
				<th>Naziv<br />kupca</th>
				<th>Broj<br />racuna</th>
				<th>Datum</th>
				<th>Iznos<br />racuna</th>
				<th>Iznos<br />rabata</th>
				<th>Neto<br />RUC-a</th>
				<th>Razduzenje</th>
			</tr>
			<?php
			$upit = mysql_query("SELECT dosta.broj_dost, date_format(dosta.datum_d, '%d. %m. %Y.') AS datum_formatiran,
				dosta.izzad, dosta.ispor, dosta.odo_rab, dosta.bruc, dob_kup.naziv_kup
				FROM dosta
				LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
				WHERE datum_d >= '$datumod' AND datum_d <= '$datumdo'");
			while($niz = mysql_fetch_array($upit))
			{
				$razduzenje=$niz['izzad']-$niz['ispor']+$niz['odo_rab'];
				$nruc=$niz['bruc']-$niz['odo_rab'];
				
				?>
				<tr>
					<td><?php echo $niz['naziv_kup'];?></td>
					<td><?php echo $niz['broj_dost'];?></td>
					<td><?php echo $niz['datum_formatiran'];?></td>
					<td><?php echo number_format($niz['izzad'], 2,".",",");?></td>
					<td><?php echo number_format($niz['odo_rab'], 2,".",",");?></td>
					<td><?php echo number_format($nruc, 2,".",",");?></td>
					<td><?php echo number_format($razduzenje, 2,".",",");?></td>
				</tr>
				<?php
			}

			$upit2 = mysql_query("SELECT SUM(dosta.izzad) AS sum_izzad,SUM(dosta.ispor) AS sum_ispor, SUM(dosta.odo_rab) AS sum_odo_rab, SUM(dosta.bruc) AS sum_bruc
				FROM dosta
				WHERE datum_d >= '$datumod' AND datum_d <= '$datumdo'");

			$niz_sum = mysql_fetch_array($upit2);
			$sum_izzad=$niz_sum['sum_izzad'];
			$sum_odo_rab=$niz_sum['sum_odo_rab'];
			$sum_bruc=$niz_sum['sum_bruc'];
			$sum_ispor=$niz_sum['sum_ispor'];
			$sum_nruc=$sum_bruc-$sum_odo_rab;
			?>
				<tr>
					<td>Zbir:</td>
					<td></td>
					<td></td>
					<td><?php echo number_format($sum_izzad, 2,".",",");?></td>
					<td><?php echo number_format($sum_odo_rab, 2,".",",");?></td>
					<td><?php echo number_format($sum_nruc, 2,".",",");?></td>
					<td><?php echo number_format(($sum_izzad-$sum_ispor+$sum_odo_rab), 2,".",",");?></td>
				</tr>
		</table>
		<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>
		<a href="#" onClick="window.print();return false" class="dugme_plavo_92plus4 print_hide">Stampa</a>
	</div>
	<?php
}
else 
{?>
	<div class="nosac_glavni_400">
		<form method="post">
			<label>Datum od:</label>
			<input id="biracdatuma" type="text" name="datumod" value="" class="polje_100_92plus4" />
			<label>Datum do:</label>
			<input id="biracdatuma2" type="text" name="datumdo" value="" class="polje_100_92plus4" />
			<button type="submit" class="dugme_zeleno">Unesi</button>
			<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
		</form>
		<div class="cf"></div>
	</div>
	<?php
}?>
</body>