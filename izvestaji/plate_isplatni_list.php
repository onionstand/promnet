<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Analiticka kartica</title>
</head>
<body>
<div class="nosac_sa_tabelom">
<?php include("../include/ConfigFirma.php");require("../include/DbConnectionPDO.php");
if (isset($_GET['id_plate'])) {
	$id_plate = (int) $_GET['id_plate'];
	$sql = "SELECT * FROM plate WHERE id_plate = $id_plate";
	$result = $baza_pdo->query($sql);
	$red = $result->fetch();
			
	$error = $baza_pdo->errorInfo();
	if (isset($error[2])) die($error[2]);
	$datum=$red['datum_plate'];
	$danstr=strtotime( $datum );
	$datum_za_bazu=date("d.m.Y",$danstr);
	$godina=date("Y",$danstr);
	$mesec=date("m",$danstr);
	?>
	<div class='memorandum'><?php echo $inkfirma;?></div>
	<div class="cf"></div>
	<h2 style="text-align:center;">Isplatni list</h2>
	<div class="nosac_zaglavlja_fakture">
		<div class="zaglavlje_fakture_desni">
			DATUM OBRAČUNA. <b><?php echo $datum_za_bazu;?></b>
		</div>
		<div class="zaglavlje_fakture_levi">
			<b><?php echo $red['ime'] . " " . $red['prezime'];?></b>
			<br>
			<b>Radno mesto:</b>
			<select>
			 	<option value="Direktor">Direktor</option>
			 </select> 
			<br>
			<b>Radni staž: </b>
			<select><?php
			for ($i=0; $i < 30; $i++) { 
				?>
				<option value="<?php echo $i;?>"><?php echo $i;?></option>
				<?php
			}?>
			</select> god.
			<select>
				<option>0</option>
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>7</option>
				<option>8</option>
				<option>9</option>
				<option>10</option>
				<option>11</option>
			</select> meseci
		</div>
	</div>
	<h2 style="text-align:center;">Obračun za godinu <?php echo $godina;?>, mesec <?php echo $mesec;?> </h2>
	<table>
		<tr>
			<th style="font-size:9px;">Vrednost rada</th>
			<th style="font-size:9px;">Bruto</th>
		</tr>
		<tr>
			<td><?php echo number_format($red['bruto_zarada'], 0, '.', '');?></td>
			<td><?php echo number_format($red['bruto_zarada'], 0, '.', '');?></td>
		</tr>
	</table>
	<div class="cf"></div>
	<table style="margin-top:20px;">
		<tr>
			<td><b>Bruto zarada:</b></td>
			<td><b><?php echo number_format($red['bruto_zarada'], 0, '.', '');?></b></td>
		</tr>
		<tr>
			<td>Doprinosi:</td>
			<td><?php echo number_format($red['ukupni_doprinosi']-$red['porez_na_licna_prim'], 0, '.', '');?></td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #000000;">Porez:</td>
			<td style="border-bottom:1px solid #000000;"><?php echo number_format($red['porez_na_licna_prim'], 0, '.', '');?></td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #000000;"><b>Neto zarada:</b></td>
			<td style="border-bottom:1px solid #000000;"><b><?php echo number_format($red['neto_zarada'], 0, '.', '');?></b></td>
		</tr>
		<tr>
			<td><b>Za isplatu:</b></td>
			<td><b><?php echo number_format($red['neto_zarada'], 0, '.', '');?></b></td>
		</tr>
	</table>
	<div class="cf"></div>
	<div id="potpis0">
		<div class="potpis2">
			<p>Radnik</p>
		</div>
	</div>
	<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
	<a href="plate_pregled.php" class="dugme_zeleno_92plus4 print_hide">Pregledaj plate</a>
	<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna</a>
	<?php } ?>
</div>
</body>