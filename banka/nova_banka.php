<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<script src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
	<title>Nova banka</title>

	<script type="text/javascript">
	            $(function() { 
	                $("#validity_form").validity(function() {
	                    $("#ime_banke")
	                        .require("Polje je neophodno...")
	                     
						$("#pocetno_stanje")
	                        .require("Polje je neophodno...")
							.match("number","Mora biti broj.")
	                });
	            });
	</script>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php 
			require("../include/DbConnection.php");
			IF (isset($_POST['ime_banke']) && ($_POST['poc_sta'])){
				$ime_banke=$_POST['ime_banke'];
				$poc_sta=$_POST['poc_sta'];

				mysql_query("INSERT INTO banke (ime_banke,poc_stanje)
				VALUES
				('$ime_banke', '$poc_sta')");
			}

			IF (isset($_POST['ime_banke_za_ispravak']) && ($_POST['poc_stanje_za_ispravak'])){

				$ime_banke_ispravi=$_POST['ime_banke_za_ispravak'];
				$poc_stanje_ispravi=$_POST['poc_stanje_za_ispravak'];
				$id_banke_ispravi=$_POST['id_banke_za_ispravak'];

				mysql_query("UPDATE banke SET ime_banke = '$ime_banke_ispravi' ,poc_stanje = '$poc_stanje_ispravi'
					WHERE id_banke = '$id_banke_ispravi'");
			}
			
			IF (isset($_GET['sifrabanke'])){
				$sifrabanke=$_GET['sifrabanke'];
				$upit = mysql_query("SELECT * FROM banke
					WHERE id_banke='$sifrabanke'");
					$niz = mysql_fetch_array($upit);
					$ime_banke_za_ispravak=$niz['ime_banke'];
					$poc_stanje_za_ispravak=$niz['poc_stanje'];
					$id_banke_za_ispravak=$niz['id_banke'];
					?>
				<form action="nova_banka.php" method="post" id="validity_form">
					<label>Ime banke:</label>
					<input type="text" name="ime_banke_za_ispravak" class="polje_100_92plus4" id="ime_banke" value="<?php echo $ime_banke_za_ispravak;?>"/>
					<label>Pocetno stanje:</label>
					<input type="text" name="poc_stanje_za_ispravak" class="polje_100_92plus4" id="pocetno_stanje" value="<?php echo $poc_stanje_za_ispravak;?>"/>
					<input type='hidden' name='id_banke_za_ispravak' value='<?php echo $id_banke_za_ispravak;?>'/>
					<button type="submit" class="dugme_zeleno">Unesi</button>
				</form>
			<?php 
			}	
			ELSE {
			?>
				<table id='tabele'>
					<tr>
						<th>Sifra</th>
						<th>Naziv</th>
						<th>Pocetno stanje</th>
						<th></th>
					</tr>

				<?php 
				$upit = mysql_query("SELECT * FROM banke ORDER BY ime_banke ");
					while($niz = mysql_fetch_array($upit))
						{
						echo "<tr>";
						echo "<td>" . $niz['id_banke'] . "</td>";
						echo "<td>" . $niz['ime_banke'] . "</td>";
						echo "<td>" . $niz['poc_stanje'] . "</td>";
						echo "<td><a href='nova_banka.php?sifrabanke=".$niz['id_banke']."'><img src='../include/images/olovka.png' alt='Ispravi' title='Ispravi' class='slicica_uredi_tabela'></a></td>";
						echo "</tr>";
						}

				?>
				</table>
				<h2>Unos Nove banke</h2>
				<form action="" method="post" id="validity_form">
					<label>Ime banke:</label>
					<input type="text" name="ime_banke" class="polje_100_92plus4" id="ime_banke"/>
					<label>Pocetno stanje:</label>
					<input type="text" name="poc_sta" class="polje_100_92plus4" id="pocetno_stanje"/>
					<button type="submit" class="dugme_zeleno">Unesi</button>
				</form>

			<?php
			}
			?>

			<form action="../index.php" method="post">
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>