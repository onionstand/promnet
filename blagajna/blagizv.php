<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
	<link rel="stylesheet" type="text/css" href="../include/jquery/css/jquery.ui.all.css">
	<title>Izvestaj blagajne</title>
	<script src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<script>
		$(function() {
			$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			$( "#biracdatuma2" ).datepicker($.datepicker.regional[ "sr-SR" ]);

			$("#obaveznaf").validity(function() {
				$("#biracdatuma")
				.require("Polje je neophodno...");
				$("#biracdatuma2")
				.require("Polje je neophodno...");
			});

		});
	</script>
</head>
<body>
<?php require("../include/DbConnection.php");
require("../include/ConfigFirma.php");
if (isset($_POST['datumod'])&& ($_POST['datumdo']))
{
	$od=$_POST['datumod'];
	$od2=strtotime( $od );
	$datumod=date("Y-m-d",$od2);
	$do=$_POST['datumdo'];
	$do2=strtotime( $do );
	$datumdo=date("Y-m-d",$do2);
	?>
	<div class="nosac_sa_tabelom">
	<div class='memorandum screen_hide'>
		<?php include("../include/ConfigFirma.php");
		echo $inkfirma;?>
	</div>
	<div class="cf"></div>
	<h2>Blagajnicki izvestaj za period od <?php echo date("d.m.Y",$od2);?> do <?php echo date("d.m.Y",$do2);?></h2>
	<table>
		<tr>
			<th>Broj</th>
			<th>Opis</th>
			<th>Ulaz</th>
			<th>Izlaz</th>
			<th>Od.PDV</th>
			<th>Saldo</th>
			<th>Konto</th>
			<th>Datum</th>
		</tr>
	<?php
	$upit = mysql_query("SELECT * FROM blagajna WHERE datum >= '$datumod' AND datum <= '$datumdo' ORDER BY datum ASC");
	$pdv_izn_zbir=0;
	while($niz = mysql_fetch_array($upit))
	{	$br_blag=$niz['br_blag'];
		$datumrad=strtotime( $niz['datum'] );
		$datum=date('d-m-Y',$datumrad);
		//stanje
		$upit2 = mysql_query("SELECT SUM(blagulaz) AS blagulaz_sum, SUM(blagizn) AS blagizn_sum FROM blagajna WHERE br_blag<='$br_blag'");
		$niz2 = mysql_fetch_array($upit2);
		$ulazzbir=$niz2['blagulaz_sum'];
		$izlazzbir=$niz2['blagizn_sum'];
		$saldo=$ulazzbir-$izlazzbir;
		$pdv_izn_zbir+=$niz['pdv_izn'];
		//stanje
		?>
		<tr>
			<td><?php echo $br_blag;?></td>
			<td><?php echo $niz['opis_troska'] . " - ". $niz['napomena'];?></td>
			<td><?php echo number_format($niz['blagulaz'], 2,".",",");?></td>
			<td><?php echo number_format($niz['blagizn'], 2,".",",");?></td>
			<td><?php echo number_format($niz['pdv_izn'], 2,".",",");?></td>
			<td><?php echo number_format($saldo, 2,".",",");?></td>
			<td><?php echo $niz['br_konta'];?></td>
			<td><?php echo $datum;?></td>
			<td class="print_hide">
				<form action="brisi_blag.php" method="post">
					<input type="hidden" name="broj_blag" value="<?php echo $br_blag;?>"/>
					<input type="image" src="../include/images/iks.png" title="Brisi" id="btnPrint"/>
				</form>
			</td>
		</tr>
	<?php
	}
	$upit3 = mysql_query("SELECT SUM(blagulaz) AS aaa2, SUM(blagizn) AS bbb2 FROM blagajna WHERE datum >= '$datumod' AND datum <= '$datumdo'");
	$niz3 = mysql_fetch_array($upit3);
	$blagulaz=$niz3['aaa2'];
	$blagizn=$niz3['bbb2'];

	$upit4 = mysql_query("SELECT ((SUM(blagulaz))-(SUM(blagizn))) AS prethsaldo FROM blagajna WHERE datum < '$datumod'");
	$niz4 = mysql_fetch_array($upit4);
	$prethsaldo=$niz4['prethsaldo'];

	$upit5 = mysql_query("SELECT ((SUM(blagulaz))-(SUM(blagizn))) AS novisaldo FROM blagajna WHERE datum <= '$datumdo'");
	$niz5 = mysql_fetch_array($upit5);
	$novisaldo=$niz5['novisaldo'];
	?>
	<tr>
		<td colspan='2'>Promet Blagajne:</td>
		<td><?php echo number_format($blagulaz, 2,".",",");?></td>
		<td><?php echo number_format($blagizn, 2,".",",");?></td>
		<td><?php echo number_format($pdv_izn_zbir, 2,".",",");?></td>
		<td colspan='2'></td>
	</tr>
	<tr>
		<td colspan='2'>Ukupan izlaz:</td>
		<td><?php echo number_format($blagizn, 2,".",",");?></td>
		<td colspan='4'></td>
	</tr>
	<tr>
		<td colspan='2'>Prethodni saldo:</td>
		<td><?php echo number_format($prethsaldo, 2,".",",");?></td>
		<td colspan='4'></td>
	</tr>
	<tr>
		<td colspan='2'>Saldo blagajne:</td>
		<td><?php echo number_format($novisaldo, 2,".",",");?></td>
		<td colspan='4'></td>
	</tr>
</table>
<br>
<a href="../index.php" class="dugme_plavo_92plus4 print_hide">Pocetna strana</a>
<a href="#" onClick="window.print();return false" class="dugme_plavo_92plus4 print_hide">Stampa</a>
<div id="potpis0">
	<div class="potpis1">
		<p>Overava</p>
	</div>
	<div class="potpis2">
		<p>Blagajnik</p>
	</div>
</div>
</div>
<?php
}
else 
{ ?>
<div class="nosac_glavni_400">
	<form method="post" id="obaveznaf">
		<label>Datum od:</label>
		<input id="biracdatuma" type="text" name="datumod" value="" class="polje_100_92plus4" />
		<label>Datum do: </label>
		<input id="biracdatuma2" type="text" name="datumdo" value="" class="polje_100_92plus4" />
		<button type="submit" class="dugme_zeleno">Unesi</button>
	</form>
	<form action="../index.php" method="post">
		<button type="submit" class="dugme_crveno">Ponisti</button>
	</form>
	<div class="cf"></div>
</div>
<?php
}
?>
</body>
</html>