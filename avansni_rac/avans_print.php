<?php
require("../include/DbConnectionPDO.php");


//id 	id_firme 	opis   osnovica 	porez 	zbir 	datum 

if(isset($_POST['id_pisma'])) {
	//$id_pisma = $_GET['satro_pismo'];
	$id_pisma = filter_input(INPUT_POST, 'id_pisma', FILTER_SANITIZE_STRING);
	//$upit_staro_pis = "SELECT * FROM avans_rac WHERE id = $id_pisma";
	$upit_staro_pis = "SELECT * FROM avans_rac WHERE id = $id_pisma";
	$avans_upit = $baza_pdo->query($upit_staro_pis);
	$avans_red = $avans_upit->fetch();
	$id_pisma=$avans_red['id'];
	$partner_id=$avans_red['id_firme'];
	$opis=$avans_red['opis'];
	$osnovica=$avans_red['osnovica'];
	$pdv=$avans_red['porez'];
	$zbir=$avans_red['zbir'];
	$datum=date("d.m.Y.",(strtotime($avans_red['datum'])));
	
	
	$upit_partner = "SELECT * FROM dob_kup WHERE sif_kup = $partner_id";
	$upit_partner_q = $baza_pdo->query($upit_partner);
	$red_partner = $upit_partner_q->fetch();
	
	$naziv_kup=$red_partner['naziv_kup'];
	$ulica_kup=$red_partner['ulica_kup'];
	$postbr=$red_partner['postbr'];
	$mesto_kup=$red_partner['mesto_kup'];
	$pib=$red_partner['pib'];
	
}
include("../include/ConfigFirma.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<title>Avansni racun</title>
	</head> 
	<body>
		<div class="nosac_sa_tabelom">
			<div class="memorandum screen_hide">
			<?php echo $inkfirma;?>
			</div>
			
			<div class="nosac_zaglavlja_fakture screen_hide">
				<div class="zaglavlje_fakture_levi"><span style="font-size: 15px;"><b>AVANSNI RAČUN BR. <?php echo $id_pisma."/".$trengodina;?></b></span>
					<br><br>Mesto i datum izdavanja: <b><?php echo $inkfirma_mir . ", " . $datum;?></b>
				</div>
				<div class="zaglavlje_fakture_desni">
					<span style="font-size: 12px;display: inline-block; margin-bottom:6px;">Kupac:</span><br>
					<b><span style="font-size: 16px;"><?php echo $naziv_kup;?></span></b>
					<br><?php echo $ulica_kup;?>
					<br><?php echo $postbr ." ". $mesto_kup;?>
					<br>
					<?php if($pib){echo "PIB: ". $pib;}?>
				</div>
			</div>
			
			
			<div class="cf"></div>
			<table class="sirina100">
				<tr>
					<th>Opis</th>
					<th>Ukupno</th>
				</tr>
				<tr>
					<td style="text-align:left;"><?php echo $opis;?></td>
					<td><?php echo number_format($osnovica, 2,".",",");?></td>
				</tr>
				<tr>
					<td style="border:none;">Osnovica:</td>
					<td><?php echo number_format($osnovica, 2,".",",");?></td>	
				</tr>
				<tr>
					<td style="border:none;">Ukupan PDV:</td>
					<td><?php echo number_format($pdv, 2,".",",");?></td>
				</tr>
				<tr>
					<td style="border:none;">Ukupna vrednost sa PDV-om:</td>
					<td>
						<b><?php echo number_format($zbir, 2,".",",");?></b>
					</td>
				</tr>
			</table>
			<div class="cf"></div>
			<div id="potpis0">
				<div class="potpis1">
					<p>Izdao</p>
				</div>
			</div>
			<div class="cf"></div>
			<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_plavo_92plus4 print_hide">Pocetna strana</a>
		</div>
	</body>
</html>