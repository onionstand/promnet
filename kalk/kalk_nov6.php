<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
		 jQuery(document).ready(function() {
			$(".dugme_zeleno:visible:first").focus();
			});
		</script>
	</head> 
	<body>
		<div class="nosac_sa_tabelom">
			<div class="memorandum screen_hide">
				<?php include("../include/ConfigFirma.php");
				echo $inkfirma;?>
			</div>
			<div class="nosac_zaglavlja_fakture screen_hide">
				<?php require("../include/DbConnection.php");
				$brojkalku=$_POST['broj_kalkulaci'];

				$result3 = mysql_query("SELECT * FROM kalk
				WHERE broj_kalk='$brojkalku'");

				while($row3 = mysql_fetch_array($result3))
				  {
				  $siffirme=$row3['sif_firme'];
				  }

				$datkal = mysql_query ("SELECT datum,faktura, date_format(datum, '%d. %m. %Y.') as formatted_date FROM kalk WHERE broj_kalk=$brojkalku ");
				$datumm= mysql_fetch_array ($datkal);
				  
				$datumzaporez= $datumm['datum'];
				$result4 = mysql_query("SELECT * FROM dob_kup
				WHERE sif_kup='$siffirme'");

				while($row4 = mysql_fetch_array($result4))
				  {
				  $dobavljac=$row4['naziv_kup'];
				  }

				
				?>
				<div class="zaglavlje_fakture_levi">
					<p>
						Broj kalkulacije: <b><?php echo $brojkalku;?></b><br>
						Datum: <b><?php echo $datumm['formatted_date'];?></b><br>
						Dobavljac: <b><?php echo $dobavljac;?></b><br>
						Broj dokumenta: <b><?php echo $datumm['faktura'];?></b>
					</p>
				</div>
			</div>
			<p class="print_hide">
				Broj kalkulacije: <b><?php echo $brojkalku;?></b><br>
				Datum: <b><?php echo $datumm['formatted_date'];?></b><br>
				Dobavljac: <b><?php echo $dobavljac;?></b><br>
				Broj dokumenta: <b><?php echo $datumm['faktura'];?></b>
			</p>
			<div class="cf"></div>
			<table id="tabele">
				<tr>
					<th>Sifra</th>
					<th>Naziv robe</th>
					<th>Kol.</th>
					<th>Fak.cena</th>
					<th>Iznos f.c.</th>
					<th>Pro.cena</th>
					<th>Iznos p.c.</th>
					<th>Rabat</th>
					<th>PDV</th>
				</tr>
				<?php
				$resulta97 = mysql_query("SELECT ulaz.id, ulaz.br_kal, ulaz.srob_kal, ulaz.kol_kalk, ulaz.cena_k, ulaz.rab_kalk,
				ulaz.cena_k*ulaz.kol_kalk AS ukupkal, (ulaz.kol_kalk*roba.cena_robe) AS ukupfak, roba.sifra, roba.naziv_robe, roba.cena_robe, roba.porez 
				FROM ulaz RIGHT JOIN roba ON ulaz.srob_kal=roba.sifra 
				WHERE br_kal='$brojkalku'");
				while($rowa97 = mysql_fetch_array($resulta97)) { ?>
				<tr>
					<td><?php echo $rowa97['srob_kal'];?></td>
					<td><?php echo $rowa97['naziv_robe'];?></td>
					<td><?php echo $rowa97['kol_kalk'];?></td>
					<td><?php echo $rowa97['cena_k'];?></td>
					<td><?php echo $rowa97['ukupkal'];?></td>
					<td><?php echo $rowa97['cena_robe'];?></td>
					<td><?php echo $rowa97['ukupfak'];?></td>
					<td><?php echo $rowa97['rab_kalk'];?></td>
					<td><?php 
						$poreskastopasifra= $rowa97['porez'];
						$porstoprow0= mysql_query("SELECT *	FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = '$poreskastopasifra')
								AND tarifa_stope = '$poreskastopasifra'
								AND porez_datum <= '$datumzaporez'");
						$porstoprow= mysql_fetch_array($porstoprow0);
						$porezprocenat= $porstoprow['porez_procenat'];
						echo $porezprocenat;?>
					</td>
					<td class="print_hide">
						<form action='kalk_nov_del.php' method='post'>
							<input type='hidden' name='broj_kalkulaci' value='<?php echo $brojkalku;?>' />
							<input type='hidden' name='id_kalk' value='<?php echo $rowa97['id'];?>'/>
							<input type='image' id='btnPrint' src='../include/images/iks.png' title='Ispravi'/>
						</form>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td rowspan="7" style="border-left:none;border-bottom:none;"></td>
					<td colspan="3">Zbir:</td>
					<td><?php $resulta10 = mysql_query("SELECT SUM(cena_k*kol_kalk) AS ukupiznul FROM ulaz WHERE br_kal='$brojkalku'");
						$iznoskauk=(mysql_fetch_array($resulta10));echo $iznoskauk['ukupiznul'];?>
					</td>
					<td></td>
					<td><?php $resulta11 = mysql_query("SELECT ulaz.br_kal, ulaz.srob_kal, SUM(ulaz.kol_kalk*roba.cena_robe) AS ukupfakz, roba.sifra 
						FROM ulaz RIGHT JOIN roba ON ulaz.srob_kal=roba.sifra WHERE br_kal='$brojkalku'");
						$iznosfauk=(mysql_fetch_array($resulta11));
						echo $iznosfauk['ukupfakz'];?>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3">Odobren rabat:</td>
					<td><?php $resulta12 = mysql_query("SELECT SUM(kol_kalk*(cena_k-(cena_k/100)*(100-rab_kalk))) AS ukuprab FROM ulaz WHERE br_kal='$brojkalku'");
						$rowa12 = (mysql_fetch_array($resulta12));
						echo number_format($rowa12['ukuprab'], 2,".","");?>
					</td>
					<td colspan="4" rowspan="6" style="border-right:none; border-bottom:none;"></td>
				</tr>
				<tr>
					<td colspan="3">Nabavna vrednost robe:</td>
					<td><?php echo number_format((($iznoskauk['ukupiznul'])-($rowa12['ukuprab'])), 2,".","");?></td>
				</tr>
				<tr>
					<td colspan="3">Uracunat PDV od dobavljaca:</td>
					<td>
						<?php $resulta13 = 
						mysql_query("SELECT ulaz.br_kal, ulaz.srob_kal, 
						SUM(((ulaz.kol_kalk*((ulaz.cena_k/100)*(100-ulaz.rab_kalk))/100)*(100+
						
						(SELECT porez_procenat FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
								AND tarifa_stope = roba.porez
								AND porez_datum <= '$datumzaporez')
						
						))-(ulaz.kol_kalk*((ulaz.cena_k/100)*(100-ulaz.rab_kalk)))) AS ukupporez, roba.sifra 
						FROM ulaz 
						RIGHT JOIN roba ON ulaz.srob_kal=roba.sifra 
						WHERE br_kal='$brojkalku'");
						$rowa13 = (mysql_fetch_array($resulta13));
						echo number_format($rowa13['ukupporez'], 2,".","");?>
					</td>
				</tr>
				<tr>
					<td colspan="3">Nabavna vrednost sa PDV-om:</td>
					<td><?php $nabvrsapdv=number_format((($iznoskauk['ukupiznul'])-($rowa12['ukuprab']))+($rowa13['ukupporez']), 2,".",""); echo $nabvrsapdv;?></td>
				</tr>
				<tr>
					<td colspan="3">Prodajna vrednost bez PDV-a:</td>
					<td><?php echo $iznosfauk['ukupfakz'];?></td>
				</tr>
				<tr>
					<td colspan="3">Razlika u ceni:</td>
					<td><?php echo number_format(($iznosfauk['ukupfakz'])-(($iznoskauk['ukupiznul'])-($rowa12['ukuprab'])), 2,".","");?></td>
				</tr>
			</table> 
			<div class="cf"></div>
			<form action="kalk_nov3.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $brojkalku; ?>"/>
				<button type='submit' class='dugme_zeleno print_hide'>Nova roba</button>
			</form>
			<form action="kalk_nov7.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $brojkalku; ?>"/>
				<input type="hidden" name="nab_vr" value="<?php echo $nabvrsapdv; ?>"/>
				<input type="hidden" name="pro_vr" value="<?php echo $iznosfauk['ukupfakz']; ?>"/>
				<input type="hidden" name="por_vr" value="<?php echo $rowa13['ukupporez']; ?>"/>
				<input type="hidden" name="rab_vr" value="<?php echo $rowa12['ukuprab']; ?>"/>
				<button type='submit' class='dugme_plavo print_hide'>Zavrsi</button>
			</form>
			<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
			<div class="cf"></div>
			<div id="potpis0">
				<div class="potpis1">
					<p>Obradio</p>
				</div>
				<div class="potpis2">
					<p>Odobrio</p>
				</div>
			</div>
		</div>
	</body>
</html>