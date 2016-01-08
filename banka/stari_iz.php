<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Izvod</title>
</head>
<body>
<div class="nosac_sa_tabelom">
	<?php require("../include/DbConnection.php");
	require("../include/ConfigFirma.php");
	$idbank=$_POST['id_banke'];
	$ps = mysql_query("SELECT ime_banke,poc_stanje FROM banke WHERE id_banke='$idbank'");
	$psniz = mysql_fetch_array($ps);
	$poc_stanje=$psniz['poc_stanje'];
	$imebanke=$psniz['ime_banke'];

	$izvod0 = mysql_query("SELECT SUM(ulaz_novca) AS iznosta1, SUM(izlaz_novca) AS iznosta2 FROM bankaupis WHERE banka='$idbank'");
	$niz3sta = mysql_fetch_array($izvod0);
	$sum1sta=$niz3sta['iznosta1'];
	$sum1sta2=$niz3sta['iznosta2'];
	$tekucsta=$sum1sta+$poc_stanje-$sum1sta2;
	?>
	<h2>Izvodi</h2>
	<p>
		Banka: <?php echo $imebanke;?><br>
		Trenutno stanje: <b><?php echo number_format($tekucsta, 2,'.',',');?></b>
	</p>
	
	<?php
	$izvod = mysql_query("SELECT br_izvoda,datum_izv, date_format(datum_izv, '%d. %m. %Y.') AS datum_format FROM bankaupis WHERE banka='$idbank' GROUP BY br_izvoda");
	?>
	<table>
		<tr>
			<th>Broj izvoda</th>
			<th>Datum</th>
			<th>Stanje</th>
		</tr>
	<?php
	while($niz = mysql_fetch_array($izvod))
	{
		$broj_izvod=$niz['br_izvoda'];
		//stanje
		$upit2 = mysql_query("SELECT SUM(ulaz_novca) AS aaa, SUM(izlaz_novca) AS bbb FROM bankaupis WHERE br_izvoda<='$broj_izvod' AND banka='$idbank'");
		$niz2 = mysql_fetch_array($upit2);
		$ulazzbir=$niz2['aaa'];
		$izlazzbir=$niz2['bbb'];
		$stanjeiz=$ulazzbir+$poc_stanje-$izlazzbir;
		?>
		<tr>
			<td><?php echo $broj_izvod;?></td>
			<td><?php echo $niz['datum_format'];?></td>
			<td><?php echo number_format($stanjeiz, 2,'.',',');?></td>
			<td class="print_hide">
				<form action="izvod5.php" method="post">
					<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvod;?>"/>
					<input type="hidden" name="id_banke" value="<?php echo $idbank;?>"/>
					<input type="hidden" name="datum" value="<?php echo $niz['datum_izv'];?>"/>
					<input type="image" src="../include/images/olovka.png" title="Ispravi" />
				</form>
			</td>
		</tr>
	<?php	
	}
	?>
	</table>
	<br>
	<a href="../index.php" class="dugme_zeleno_92plus4">
		Pocetna strana
	</a>
	<div class="cf"></div>
</div>
</body>