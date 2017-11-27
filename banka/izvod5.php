<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Izvod</title>
</head>
<body>
<div class="nosac_sa_tabelom">
	<?php
	require("../include/DbConnection.php");
	require("../include/ConfigFirma.php");
	$idbank=$_POST['id_banke'];
	//$datum=$_POST['datum'];

	$broj_izvoda=$_POST['broj_izvoda'];

	$upit2 = mysql_query("SELECT date_format(datum_izv, '%d. %m. %Y.') as formatted_date, datum_izv FROM bankaupis WHERE br_izvoda='$broj_izvoda' AND banka='$idbank'");
	$niz2 = mysql_fetch_array($upit2);
	$datum_izvoda=$niz2['formatted_date'];
	$datum=$niz2['datum_izv'];

	$ps = mysql_query("SELECT * FROM banke WHERE id_banke='$idbank'");
	$psniz = mysql_fetch_array($ps);
	$poc_stanje=$psniz['poc_stanje'];
	$bank=$psniz['ime_banke'];
	?>
	<p><b><?php echo $inkfirmamin;?></b><br>
		Banka: <?php echo $bank;?><br>
		Izvod br. <?php echo $broj_izvoda;?><br>
		Datum izvoda: <?php echo $datum_izvoda;?>
	</p>
		<table>
			<tr>
				<th>Sifra</th>
				<th>Naziv partnera</th>
				<th>Svrha</th>
				<th>Ziro racun</th>
				<th>Dokument</th>
				<th>Potrazuje</th>
				<th>Duguje</th>
			</tr>
	<?php
	$resulta97 = mysql_query("SELECT
		bankaupis.id_upl,
		bankaupis.br_izvoda,
		bankaupis.datum_izv,
		bankaupis.sifra_par,
		bankaupis.broj_dok,
		bankaupis.ulaz_novca,
		bankaupis.izlaz_novca,
		bankaupis.ziro_rac,
		bankaupis.banka,
		bankaupis.svrha,
		dob_kup.sif_kup,
		dob_kup.naziv_kup
		FROM bankaupis RIGHT JOIN dob_kup ON bankaupis.sifra_par=dob_kup.sif_kup WHERE br_izvoda='$broj_izvoda' AND banka='$idbank'");
	while($rowa97 = mysql_fetch_array($resulta97)) 
	{
		?>
		<tr>
			<td><?php echo $rowa97['id_upl'];?></td>
			<td><?php echo $rowa97['naziv_kup'];?></td>
			<td><?php echo $rowa97['svrha'];?></td>
			<td><?php echo $rowa97['ziro_rac'];?></td>
			<td><?php echo $rowa97['broj_dok'];?></td>
			<td>
			<?php IF ($rowa97['ulaz_novca']==0){
					echo number_format($rowa97['izlaz_novca'], 2,'.',',');?>
			</td>
			<td></td><?php 
			}
			IF ($rowa97['izlaz_novca']==0){?>
			</td>
			<td><?php echo number_format($rowa97['ulaz_novca'], 2,'.',',');?></td>
			<?php } ?>
			<td class="td_za_brisanje">
				<form action='brisi_st_iz.php' method='post'>
					<input type='hidden' name='broj_izvoda' value='<?php echo $broj_izvoda;?>'/>
					<input type='hidden' name='id_banke' value='<?php echo $idbank;?>'/>
					<input type='hidden' name='datum' value='<?php echo $datum;?>'/>
					<input type='hidden' name='id_upl' value='<?php echo $rowa97['id_upl'];?>'/>
					<input type='image' id='btnPrint' src='../include/images/iks.png' title='Obrisi'/>
				</form>
			</td>
		</tr>
		<?php
	}
	$suma1 = mysql_query("SELECT SUM(izlaz_novca) AS iznostotal1 FROM bankaupis WHERE br_izvoda='$broj_izvoda' AND banka='$idbank'");
	$niz3 = mysql_fetch_array($suma1);
	$sum1=$niz3['iznostotal1'];
	$suma2 = mysql_query("SELECT SUM(ulaz_novca) AS iznostotal2 FROM bankaupis WHERE br_izvoda='$broj_izvoda' AND banka='$idbank'");
	$niz4 = mysql_fetch_array($suma2);
	$sum2=$niz4['iznostotal2'];
	?>
		<tr>
			<td class="cellev"></td>
			<td colspan="4">Zbir: </td>
			<td><?php echo number_format($sum1, 2,'.',',');?></td>
			<td><?php echo number_format($sum2, 2,'.',',');?></td>
		</tr>
	</table>
	<?php
	/*Prethodno stanje*/
	$suma1psta = mysql_query("SELECT SUM(izlaz_novca) AS iznopsta1, SUM(ulaz_novca) AS iznopsta2 FROM bankaupis WHERE br_izvoda < '$broj_izvoda' AND banka='$idbank'");
	$niz3psta = mysql_fetch_array($suma1psta);
	$sum1psta=$niz3psta['iznopsta1'];
	$sum1psta2=$niz3psta['iznopsta2'];
	$presta=$sum1psta2+$poc_stanje-$sum1psta;
	/*Tekuce stanje*/
	$suma1sta = mysql_query("SELECT SUM(izlaz_novca) AS iznosta1, SUM(ulaz_novca) AS iznosta2 FROM bankaupis WHERE br_izvoda <= '$broj_izvoda' AND banka='$idbank'");
	$niz3sta = mysql_fetch_array($suma1sta);
	$sum1sta=$niz3sta['iznosta1'];
	$sum1sta2=$niz3sta['iznosta2'];
	$tekucsta=$sum1sta2+$poc_stanje-$sum1sta;
	?>
	<br>
	<table>
		<tr>
			<td>Prethodno stanje: </td>
			<td><?php echo number_format($presta, 2,'.',',');?></td>
			<td>Tekuce stanje: </td>
			<td><?php echo number_format($tekucsta, 2,'.',',');?></td>
		</tr>
	</table>
	<div class="cf"></div>
	<form action="izvod2.php" method="post">
		<input type="hidden" name="datum" value="<?php echo $datum; ?>"/>
		<input type="hidden" name="id_banke" value="<?php echo $idbank; ?>"/>
		<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda; ?>"/>
		<button type='submit' class='dugme_zeleno print_hide'>Dodaj</button>
	</form>
	<form action="izvod_promeni_datum.php" method="post">
		<input type="hidden" name="datum" value="<?php echo $datum; ?>"/>
		<input type="hidden" name="id_banke" value="<?php echo $idbank; ?>"/>
		<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda; ?>"/>
		<button type='submit' class='dugme_zeleno print_hide'>Promeni datum</button>
	</form>
	<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
	<form action="../index.php" method="post">
	<button type='submit' class='dugme_plavo print_hide'>Zavrsi</button>
	</form>
	<div class="cf"></div>
</div>
</body>
</html>