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
		<table>
			<tr>
				<th>Naziv<br />dobavljaca</th>
				
				<th>Nabavna<br />vrednost</th>
				<th>Ukalkul.<br />RUC</th>
				<th>Prodajna<br />vrednost</th>
				<th>Akontacija poreza</th>
			</tr>
			<?php
			$od=$_POST['datumod'];
			$od2=strtotime( $od );
			$datumod=date("Y-m-d",$od2);
			$do=$_POST['datumdo'];
			$do2=strtotime( $do );
			$datumdo=date("Y-m-d",$do2);
			echo "<div class='memorandum screen_hide'>";
			include("../include/ConfigFirma.php");
			echo $inkfirma;
			echo "</div><div class='cf'></div>";
			echo "<h2>Izvestaj nabavke za period od " . date("d.m.Y",$od2) . " do " . date("d.m.Y",$do2) . "</h2>";
		
			//$upit = mysql_query("SELECT kalk.broj_kalk, date_format(kalk.datum, '%d. %m. %Y.') AS datum_formatiran, kalk.nabav_vre, kalk.odora, kalk.pro_vre,kalk.ukal_porez,dob_kup.naziv_kup 
			//	FROM kalk
			//	LEFT JOIN dob_kup ON kalk.sif_firme=dob_kup.sif_kup
			//	WHERE datum >= '$datumod' AND datum <= '$datumdo'");
				
				
			$upit = mysql_query("SELECT kalk.sif_firme,
				SUM(kalk.nabav_vre) AS nabav_vre_sum,
				SUM(kalk.ukal_porez) AS ukal_porez_sum,
				SUM(kalk.pro_vre) AS pro_vre_sum,
				
				dob_kup.naziv_kup
				FROM kalk
				LEFT JOIN dob_kup ON kalk.sif_firme=dob_kup.sif_kup
				WHERE datum >= '$datumod' AND datum <= '$datumdo'
				GROUP BY kalk.sif_firme ORDER BY nabav_vre_sum DESC");
				
			while($niz = mysql_fetch_array($upit))
			{
				$razlika_u_ceni=($niz['pro_vre_sum'])-(($niz['nabav_vre_sum'])-$niz['ukal_porez_sum']);
				?>
				<tr>
					<td><?php echo $niz['naziv_kup'];?></td>
					
					<td><?php echo number_format(($niz['nabav_vre_sum']-$niz['ukal_porez_sum']), 2,".",",");?></td>
					<td><?php echo number_format($razlika_u_ceni, 2,".",",");?></td>
					<td><?php echo number_format($niz['pro_vre_sum'], 2,".",",");?></td>
					<td><?php echo number_format($niz['ukal_porez_sum'], 2,".",",");?></td>
				</tr>
				<?php
			}
			$upit2 = mysql_query("SELECT SUM(kalk.nabav_vre) AS sum_nabav_vre, SUM(kalk.pro_vre) AS sum_pro_vre, SUM(kalk.ukal_porez) AS sum_ukal_porez
				FROM kalk
				WHERE datum >= '$datumod' AND datum <= '$datumdo'");
			$niz_sum = mysql_fetch_array($upit2);
			$sum_nabav_vre=$niz_sum['sum_nabav_vre'];
			$sum_pro_vre=$niz_sum['sum_pro_vre'];
			$sum_ukal_porez=$niz_sum['sum_ukal_porez'];

			$sum_razlika_u_ceni=$sum_pro_vre-($sum_nabav_vre-$sum_ukal_porez);
			?>
			<tr>
					<td>Zbir:</td>
				
					<td><?php echo number_format(($sum_nabav_vre-$sum_ukal_porez), 2,".",",");?></td>
					<td><?php echo number_format($sum_razlika_u_ceni, 2,".",",");?></td>
					<td><?php echo number_format($sum_pro_vre, 2,".",",");?></td>
					<td><?php echo number_format($sum_ukal_porez, 2,".",",");?></td>
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