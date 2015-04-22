<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<link rel="stylesheet" type="text/css" href="../include/jquery/css/jquery.ui.all.css">
	<title>Izvestaj usluga</title>
	<script src="../include/jquery/jquery-1.6.2.min.js"></script>
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
require("../include/ConfigFirma.php");
if (isset($_POST['pretraga'])){
	if (isset($_POST['datumod'])&& ($_POST['datumdo'])){ 
		$od=$_POST['datumod'];
		$od2=strtotime( $od );
		$datumod=date("Y-m-d",$od2);

		$do=$_POST['datumdo'];
		$do2=strtotime( $do );
		$datumdo=date("Y-m-d",$do2);
	}
	$sortiranje=$_POST['sortiranje'];
	?>
	<div class="nosac_sa_tabelom">
		<div class='memorandum screen_hide'>
			<?php include("../include/ConfigFirma.php");
			echo $inkfirma;?>
		</div>
		<div class="cf"></div>
		<?php if (isset($_POST['datumod'])&& ($_POST['datumdo'])){ ?>
			<h2>Izvestaj usluga za period od <?php echo date("d.m.Y",$od2);?> do <?php echo date("d.m.Y",$do2);?></h2>
		<?php
		}?>
		<table>
			<tr>
				<th>Broj</th>
				<th>Konto</th>
				<th>Opis</th>
				<th>Dokument</th>
				<th>Datum</th>
				<th>Iznos</th>
				<th>PDV</th>
				<th>Dobavljac</th>
			</tr>
		<?php
		if (isset($_POST['datumod'])&& ($_POST['datumdo'])){ 
			if ($sortiranje=="broj") {
				$upit = mysql_query("SELECT * FROM usluge WHERE datum >= '$datumod' AND datum <= '$datumdo' ORDER BY br_usluge");
			}
			else{
				$upit = mysql_query("SELECT * FROM usluge WHERE datum >= '$datumod' AND datum <= '$datumdo' ORDER BY datum");
			}
		}
		elseif (isset($_POST['brojusluge'])) {
			$brojusluge = $_POST['brojusluge'];
			$upit = mysql_query("SELECT * FROM usluge WHERE br_usluge='$brojusluge'");
		}
		else{ ?>
			<h2>Greska</h2>
		<?php }


		while($niz = mysql_fetch_array($upit))
		{	
			$br_usluge=$niz['br_usluge'];
			$datumrad=strtotime( $niz['datum'] );
			$datum=date('d-m-Y',$datumrad);
			$partner=$niz['sifusluge'];
			
			$iznosus=$niz['iznosus'];
			$pdv_us=$niz['pdv'];
		
			$upitpar = mysql_query("SELECT naziv_kup FROM dob_kup WHERE sif_kup='$partner'");
			$nizpar = mysql_fetch_array($upitpar);
			$partnerime=$nizpar['naziv_kup'];
			?>
			<tr>
				<td><?php echo $br_usluge;?></td>
				<td><?php echo $niz['kontous'];?></td>
				<td><?php echo $niz['opis'];?></td>
				<td><?php echo $niz['br_dok_us'];?></td>
				<td><?php echo $datum;?></td>
				<td><?php echo number_format($iznosus, 2,".",",");?></td>
				<td><?php echo number_format($pdv_us, 2,".",",");?></td>
				<td><?php echo $partnerime;?></td>
				<td class="print_hide">
					<form action="brisi_uslugu.php" method="post">
						<input type="hidden" name="broj_usluge" value="<?php echo $br_usluge;?>"/>
						<input type="image" src="../include/images/mini2.png" title="Brisi" id="btnPrint" />
					</form>
				</td>
			</tr>
			<?php
		}
		if (isset($_POST['datumod'])&& ($_POST['datumdo'])){ 
			$upit3 = mysql_query("SELECT SUM(iznosus) AS aaa2, SUM(pdv) AS bbb2 FROM usluge WHERE datum >= '$datumod' AND datum <= '$datumdo'");
			$niz3 = mysql_fetch_array($upit3);
			$sumiznosus=$niz3['aaa2'];
			$sumpdv=$niz3['bbb2'];

			$upit4 = mysql_query("SELECT SUM(iznosus) AS prethsaldo FROM usluge WHERE datum < '$datumod'");
			$niz4 = mysql_fetch_array($upit4);
			$prethsaldo=$niz4['prethsaldo'];

			$upit5 = mysql_query("SELECT SUM(iznosus) AS novisaldo FROM usluge WHERE datum <= '$datumdo'");
			$niz5 = mysql_fetch_array($upit5);
			$novisaldo=$niz5['novisaldo'];
			?>
				<tr>
					<td colspan='5'>Ukupno:</td>
					<td><?php echo number_format($sumiznosus, 2,".",",");?></td>
					<td><?php echo number_format($sumpdv, 2,".",",");?></td>
					<td></td>
				</tr>
				<tr>
					<td colspan='2'>Prethodni saldo:</td>
					<td colspan='5'><?php echo number_format($prethsaldo, 2,".",",");?></td>
				</tr>
				<tr>
					<td colspan='2'>Saldo blagajne:</td>
					<td colspan='5'><?php echo number_format($novisaldo, 2,".",",");?></td>
				</tr>
		<?php } ?>
		</table>
		<br>
		<a href="uslugeizv.php" class="dugme_zeleno_92plus4 print_hide">Nazad</a>
		<a href="../index.php" class="dugme_plavo_92plus4 print_hide">Pocetna strana</a>
		<a href="#" onClick="window.print();return false" class="dugme_plavo_92plus4 print_hide">Stampaj</a>
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
{
	?>
	<div class="nosac_glavni_400">
		<form method="post">
			<label>Datum od:</label>
			<input id="biracdatuma" type="text" name="datumod" value="" class="polje_100_92plus4" />
			<label>Datum do:</label>
			<input id="biracdatuma2" type="text" name="datumdo" value="" class="polje_100_92plus4" />
			<label>Sortiraj po:</label>
			<select id='sortiranje' name='sortiranje' size='1' class='polje_100'>
				<option value='broj'>Po broju</option>
				<option value='datum'>Po datumu</option>
			</select>
			<label>Broj usluge:</label>
			<input type="text" name="brojusluge" class="polje_100_92plus4"/>
			<button name="pretraga" type="submit" value="pretraga" class="dugme_zeleno">Pretrazi</button>
		</form>
		<form action="../index.php" method="post">
			<button type="submit" class="dugme_crveno">Ponisti</button>
		</form>
		<div class="cf"></div>
	</div>
	<?php
} ?>
</body>
</html>