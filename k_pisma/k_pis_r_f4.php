<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Knjizno pismo robno</title>
	<script type="text/javascript">
		function showHint(str,str2){
			if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		 			xmlhttp=new XMLHttpRequest();
			}
			else {// code for IE6, IE5
		  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.open("GET","k_pis_r_k4a.php?q="+str+"&ido="+str2,true);
			xmlhttp.send();
		}
		
		function pisi(tekst){
			document.write(tekst);
		}
	</script>
</head>
<body>
	<div class="nosac_sa_tabelom">
		<?php 
		require("../include/DbConnection.php");
		$br_k_pis=$_POST['br_k_pis'];
		$upit1 = mysql_query("SELECT dos_kal, date_format(dat_k, '%d. %m. %Y.') as formatted_date FROM k_pism_r
				WHERE broj_k='$br_k_pis'");
		$red1 = mysql_fetch_array($upit1);
		$dat_k=$red1['formatted_date'];
		$broj_fak=$red1['dos_kal'];

		$upit2 = mysql_query("SELECT sifra_fir FROM dosta
				WHERE broj_dost='$broj_fak'");
		$red2 = mysql_fetch_array($upit2);
		$sif_firme=$red2['sifra_fir'];

		$result4 = mysql_query("SELECT * FROM dob_kup
				WHERE sif_kup='$sif_firme'");
		while($row4 = mysql_fetch_array($result4))
		  {
			  $kupac=$row4['naziv_kup'];
			  $ulica_kup=$row4['ulica_kup'];
			  $post_br=$row4['postbr'];
			  $mesto_kup=$row4['mesto_kup'];
			  $pib=$row4['pib'];
			  $mat_br=$row4['mat_br'];
		  }
		/*Memorandum*/
		include("../include/ConfigFirma.php");
		?>
		<div class='memorandum screen_hide'>
			<?php echo $inkfirma;?>
		</div>
		<div class="cf"></div>
		<div class="nosac_zaglavlja_fakture screen_hide">
			<div class="zaglavlje_fakture_desni">
				Knjizno pismo br. <b><?php echo $br_k_pis;?></b><br>
				Datum: <b><?php echo $dat_k;?></b>
			</div>
			<div class="zaglavlje_fakture_levi">
				Dobavljac: <br>
				<b><?php echo $kupac;?></b><br>
				<b><?php echo $ulica_kup;?></b><br>
				<b><?php echo $post_br." ".$mesto_kup;?></b><br>
				PIB <b><?php echo $pib;?></b><br>
				MAT.BR. <b><?php echo $mat_br;?></b>
			</div>
		</div>
		<div class="cf"></div>
		<table>
			<tr>
				<th>Opis</th>
				<th>Na teret</th>
				<th>U korist</th>
			</tr>
			<tr>
				<td>
					<?php $upit3 = mysql_query("SELECT tekst_k FROM k_pism_r
											WHERE broj_k='$br_k_pis'");
					$red5 = mysql_fetch_array($upit3);
					$tekst_k=$red5['tekst_k'];
					?>
					<form action="">
						<textarea class="knjpistekst" id="txt1" onblur="showHint(this.value,'<?php echo $br_k_pis;?>')" style="width:100%; border:none;"><?php echo $tekst_k;?></textarea>
					</form>
				</td>
				<td></td>
				<td>
					<?php 
					$upit= mysql_query("SELECT 
										SUM(((((izlaz.cena_d/100)*(100-k_pism_tr.rabat_p))/100)*(100+roba.porez))*k_pism_tr.kolic_p)AS teret,
										SUM(k_pism_tr.kolic_p*izlaz.cena_d) AS kol_i_cena_s,
										SUM(((izlaz.cena_d/100)*(100-k_pism_tr.rabat_p))*k_pism_tr.kolic_p) AS bez_pdv_teret, 
										SUM((izlaz.cena_d-((izlaz.cena_d/100)*(100-k_pism_tr.rabat_p)))*k_pism_tr.kolic_p) AS k_rab
										FROM k_pism_tr 
										RIGHT JOIN izlaz ON k_pism_tr.sif_rob_p=izlaz.srob_dos AND k_pism_tr.id_u_i=izlaz.id
										LEFT JOIN roba ON k_pism_tr.sif_rob_p=roba.sifra
										WHERE broj_p='$br_k_pis'");
					while ($niz=mysql_fetch_array($upit))
					{
						$kol_i_cena_s=$niz['kol_i_cena_s'];
						$teret= $niz['teret'];
						$bez_pdv_teret=$niz['bez_pdv_teret'];
						$pdv=$teret-$bez_pdv_teret;
						$k_rab= $niz['k_rab'];
						echo number_format($teret, 2,".",",");
					}
					?>
				</td>
			</tr>
		</table>
		<p>OBRACUN: </p>
		<table>
			<tr>
				<th>Roba</th>
				<th>Kolicina</th>
				<th>Cena</th>
				<th>Zbir</th>
				<th>Rabat (%)</th>
			</tr>
			<?php 
			$upit4=mysql_query("SELECT k_pism_tr.id_k, k_pism_tr.sif_rob_p, k_pism_tr.kolic_p, k_pism_tr.rabat_p, izlaz.cena_d, roba.naziv_robe, 
								(k_pism_tr.kolic_p*izlaz.cena_d) AS kol_i_cena 
								FROM k_pism_tr 
								RIGHT JOIN izlaz ON k_pism_tr.sif_rob_p=izlaz.srob_dos AND k_pism_tr.id_u_i=izlaz.id 
								LEFT JOIN roba ON k_pism_tr.sif_rob_p=roba.sifra 
								WHERE broj_p='$br_k_pis'");

			while ($niz2=mysql_fetch_array($upit4))
				{ ?>
					<tr>
						<td><?php echo $niz2['naziv_robe'];?></td>
						<td><?php echo $niz2['kolic_p'];?></td>
						<td><?php echo $niz2['cena_d'];?></td>
						<td><?php echo $niz2['kol_i_cena'];?></td>
						<td><?php echo $niz2['rabat_p'];?></td>
						<td class="print_hide">
							<form action="k_pis_r_f4bris.php" method="post">
								<input type="hidden" name="broj_k_pis_tr" value="<?php echo $br_k_pis;?>"/>
								<input type="hidden" name="id_k_pis_tr" value="<?php echo $niz2['id_k'];?>"/>
								<input type="hidden" name="broj_fak" value="<?php echo $broj_fak;?>"/>
								<input type="image" id="btnPrint" src="../include/images/iks.png" title="Ispravi"/>
							</form>
						</td>
					</tr>
				<?php } ?>
			<tr>
				<td colspan="3">Ukupno:</td>
				<td><?php echo $kol_i_cena_s;?></td>
				<td rowspan="4" style="border-right:none; border-bottom:none;"></td>
			</tr>
			<tr>
				<td colspan="3">Rabat:</td>
				<td><?php echo "- ".number_format($k_rab, 2,".",",");?></td>
			</tr>
			<tr>
				<td colspan="3">PDV:</td>
				<td><?php echo "+ ".number_format($pdv, 2,".",",");?></td>
			</tr>
			<tr>
				<td colspan="3">Zbir:</td>
				<td><?php echo number_format($teret, 2,".",",");?></td>
			</tr>
		</table>

		<form action="k_pis_r_f2.php" method="post">
			<input type="hidden" name="broj_dost" value="<?php echo $broj_fak;?>"/>
			<input type="hidden" name="br_k_pis" value="<?php echo $br_k_pis;?>"/>
			<button type="submit" class="dugme_zeleno print_hide">Dodaj robu</button>
		</form>

		<form action="k_pis_r_f5.php" method="post">
			<input type="hidden" name="br_k_pis" value="<?php echo $br_k_pis;?>"/>
			<input type="hidden" name="iznos" value="<?php echo $teret;?>"/>
			<input type="hidden" name="ispor" value="<?php echo $pdv;?>"/>
			<input type="hidden" name="odo_rab" value="<?php echo $k_rab;?>"/>
			<input type="hidden" name="partner" value="<?php echo $kupac;?>"/>
			<input type="hidden" name="sif_firme" value="<?php echo $sif_firme;?>"/>
			<button type="submit" class="dugme_zeleno print_hide">Zavrsi</button>
			<a href="#" onClick="window.print();return false" class="dugme_plavo_92plus4 print_hide">Stampa</a>
		</form>
		<div id="potpis0">
			<div class="potpis1">
				<p>Direktor</p>
			</div>
		</div>
	</div>
</body>
</html>
