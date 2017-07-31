<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
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

				$sifra_firme_upit = mysql_query("SELECT sif_firme FROM kalk
				WHERE broj_kalk='$brojkalku'");

				while($sifra_firme_red = mysql_fetch_array($sifra_firme_upit))
				  {
				  $siffirme=$sifra_firme_red['sif_firme'];
				  }

				$datum_kalk_upit = mysql_query ("SELECT datum,faktura, rok_pl, date_format(datum, '%d. %m. %Y.') as formatted_date FROM kalk WHERE broj_kalk=$brojkalku ");
				$datum_kalk_red= mysql_fetch_array ($datum_kalk_upit);
				  
				$datumzaporez= $datum_kalk_red['datum'];
				$naziv_partnera_upit = mysql_query("SELECT naziv_kup FROM dob_kup
				WHERE sif_kup='$siffirme'");

				while($naziv_partnera_red = mysql_fetch_array($naziv_partnera_upit)){
				  	$dobavljac=$naziv_partnera_red['naziv_kup'];
				}
				$datum_za_pla=date("d.m.Y",strtotime ("$datum_kalk_red[datum]+$datum_kalk_red[rok_pl] day"));
				?>
				<div class="zaglavlje_fakture_levi">
					<p>
						Broj kalkulacije: <b><?php echo $brojkalku;?></b><br>
						Datum: <b><?php echo $datum_kalk_red['formatted_date'];?></b><br>
						Placanje: <b><?php echo $datum_za_pla;?></b><br>
						Dobavljac: <b><?php echo $dobavljac;?></b><br>
						Broj dokumenta: <b><?php echo $datum_kalk_red['faktura'];?></b>
					</p>
				</div>
			</div>
			<p class="print_hide">
				Broj kalkulacije: <b><?php echo $brojkalku;?></b><br>
				Datum: <b><?php echo $datum_kalk_red['formatted_date'];?></b><br>
				Placanje: <b><?php echo $datum_za_pla;?></b><br>
				Dobavljac: <b><?php echo $dobavljac;?></b><br>
				Broj dokumenta: <b><?php echo $datum_kalk_red['faktura'];?></b>
			</p>
			<div class="cf"></div>
			<table id="tabele">
				<tr>
					<th>Sifra</th>
					<th>Naziv robe</th>
					<th>Kol.</th>
					<th>J.M.</th>
					<th>Fak.cena</th>
					<th>Iznos f.c.</th>
					<th>Pro.cena</th>
					<th>Iznos p.c.</th>
					<th>Rabat</th>
					<th>PDV</th>
				</tr>
				<?php
				$stavke_kalk_upit = mysql_query("SELECT ulaz.id, ulaz.br_kal, ulaz.srob_kal, ulaz.kol_kalk, ulaz.cena_k, ulaz.rab_kalk,
				ulaz.cena_k*ulaz.kol_kalk AS ukupkal, (ulaz.kol_kalk*roba.cena_robe) AS ukupfak, roba.sifra, roba.naziv_robe, roba.cena_robe, roba.porez , roba.jed_mere
				FROM ulaz RIGHT JOIN roba ON ulaz.srob_kal=roba.sifra 
				WHERE br_kal='$brojkalku'");
				while($stavke_kalk_red = mysql_fetch_array($stavke_kalk_upit)) { ?>
				<tr>
					<td><?php echo $stavke_kalk_red['srob_kal'];?></td>
					<td><?php echo $stavke_kalk_red['naziv_robe'];?></td>
					<td><?php echo $stavke_kalk_red['kol_kalk'];?></td>
					<td><?php echo $stavke_kalk_red['jed_mere'];?></td>
					<td><?php echo $stavke_kalk_red['cena_k'];?></td>
					<td><?php echo number_format(($stavke_kalk_red['ukupkal']), 2,".","");?></td>
					<td><?php echo $stavke_kalk_red['cena_robe'];?></td>
					<td><?php echo number_format(($stavke_kalk_red['ukupfak']), 2,".","");?></td>
					<td><?php echo $stavke_kalk_red['rab_kalk'];?></td>
					<td><?php 
						$poreskastopasifra= $stavke_kalk_red['porez'];
						$poreska_stopa_upit= mysql_query("SELECT *	FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = '$poreskastopasifra')
								AND tarifa_stope = '$poreskastopasifra'
								AND porez_datum <= '$datumzaporez'");
						$poreska_stopa_red= mysql_fetch_array($poreska_stopa_upit);
						$porezprocenat= $poreska_stopa_red['porez_procenat'];
						echo $porezprocenat;?>
					</td>
					<td class="print_hide">
						<form action='kalk_nov_del.php' method='post'>
							<input type='hidden' name='broj_kalkulaci' value='<?php echo $brojkalku;?>' />
							<input type='hidden' name='id_kalk' value='<?php echo $stavke_kalk_red['id'];?>'/>
							<input type='image' id='btnPrint' src='../include/images/iks.png' title='Ispravi'/>
						</form>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td rowspan="7" style="border-left:none;border-bottom:none;"></td>
					<td colspan="4">Zbir:</td>
					<td><?php $zbir_upit = mysql_query("SELECT SUM(cena_k*kol_kalk) AS ukupiznul FROM ulaz WHERE br_kal='$brojkalku'");
						$zbir_red=(mysql_fetch_array($zbir_upit));
						echo number_format(($zbir_red['ukupiznul']), 2,".","");?>
					</td>
					<td></td>
					<td><?php $zbir_pc = mysql_query("SELECT ulaz.br_kal,
						ulaz.srob_kal,
						SUM(ulaz.kol_kalk*roba.cena_robe) AS ukupfakz,
						roba.sifra 
						FROM ulaz RIGHT JOIN roba ON ulaz.srob_kal=roba.sifra WHERE br_kal='$brojkalku'");
						$zbir_pc_red=(mysql_fetch_array($zbir_pc));
						echo $zbir_pc_red['ukupfakz'];?>
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="4">Odobren rabat:</td>
					<td><?php $odobren_rabat_upit = mysql_query("SELECT SUM(kol_kalk*(cena_k-(cena_k/100)*(100-rab_kalk))) AS ukuprab
							FROM ulaz WHERE br_kal='$brojkalku'");
						$odobren_rabat_red = (mysql_fetch_array($odobren_rabat_upit));
						echo number_format($odobren_rabat_red['ukuprab'], 2,".","");?>
					</td>
					<td colspan="5" rowspan="6" style="border-right:none; border-bottom:none;"></td>
				</tr>
				<tr>
					<td colspan="4">Nabavna vrednost robe:</td>
					<td><?php echo number_format((($zbir_red['ukupiznul'])-($odobren_rabat_red['ukuprab'])), 2,".","");?></td>
				</tr>
				<tr>
					<td colspan="4">Uracunat PDV od dobavljaca:</td>
					<td>
						<?php $pdv_od_obavljaca_upit = 
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
						$pdv_od_dobavljaca_red = (mysql_fetch_array($pdv_od_obavljaca_upit));
						echo number_format($pdv_od_dobavljaca_red['ukupporez'], 2,".","");?>
					</td>
				</tr>
				<tr>
					<td colspan="4">Nabavna vrednost sa PDV-om:</td>
					<td>
						<?php $nabavna_vrednost_sa_pdv=
							number_format((($zbir_red['ukupiznul'])-
							($odobren_rabat_red['ukuprab']))+
							($pdv_od_dobavljaca_red['ukupporez']), 2,".","");
						echo $nabavna_vrednost_sa_pdv;?>
					</td>
				</tr>
				<tr>
					<td colspan="4">Prodajna vrednost bez PDV-a:</td>
					<td><?php echo number_format($zbir_pc_red['ukupfakz'], 2,".","");?></td>
				</tr>
				<tr>
					<td colspan="4">Razlika u ceni:</td>
					<td>
						<?php echo number_format(($zbir_pc_red['ukupfakz'])-
							(($zbir_red['ukupiznul'])-
							($odobren_rabat_red['ukuprab'])), 2,".","");
						?>
					</td>
				</tr>
			</table> 
			<div class="cf"></div>
			<form action="kalk_nov3.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $brojkalku; ?>"/>
				<button type='submit' class='dugme_zeleno print_hide'>Nova roba</button>
			</form>
			<a href="promeni_partnera.php?brojkalku=<?php echo $brojkalku;?>" class="dugme_plavo_92plus4 print_hide">Promeni partnera ili datum</a>
			<form action="kalk_nov7.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $brojkalku; ?>"/>
				<input type="hidden" name="nab_vr" value="<?php echo $nabavna_vrednost_sa_pdv; ?>"/>
				<input type="hidden" name="pro_vr" value="<?php echo $zbir_pc_red['ukupfakz']; ?>"/>
				<input type="hidden" name="por_vr" value="<?php echo $pdv_od_dobavljaca_red['ukupporez']; ?>"/>
				<input type="hidden" name="rab_vr" value="<?php echo $odobren_rabat_red['ukuprab']; ?>"/>
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