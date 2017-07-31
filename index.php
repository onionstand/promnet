<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="include/css/stil.css">
	<meta charset="utf-8" />
	<title>Promnet</title>
	<script src="include/jquery/jquery-1.6.2.min.js" type="text/javascript"></script>
	<script src="include/graph/grafjs.js" type="text/javascript"></script>
	<?php
	require("include/graph/graf.php");
	?>
	<link rel="stylesheet" type="text/css" href="include/graph/grafcss.css">
	<script>
			//podsetnik

			$(document).ready(function() {
				$('#loadpodsetnik').hide();
			});
			$(function() {
				$(".podsetnik_brisi").click(function() {
					$('#loadpodsetnik').fadeIn();
					var commentContainer = $(this).parent();
					var id = $(this).attr("id");
					var string = 'id='+ id ;

					$.ajax({
						   type: "POST",
						   url: "include/podsetnik_delete.php",
						   data: string,
						   cache: false,
						   success: function(){
								commentContainer.slideUp('slow', function() {$(this).remove();});
								$('#loadpodsetnik').fadeOut();
							}

					 });

					return false;
				});
			});
	</script>
</head>
<body>
<div class="sadrzaj_index">
	<div id="head">
		<div class="navi">
			<h4>Ulaz</h4>
			<ul>
			  <li><a href="kalk/kalk_nov1.php">Nova kalkulacija</a></li>
			  <li><a href="kalk/starakal.php">Stara kalkulacija</a></li>
			</ul>
		</div>

		<div class="navi">
			<h4>Izlaz</h4>
			<ul>
				<li><a href="fak/faktura.php">Nova Faktura</a></li>
				<li><a href="fak/faktura_stare.php">Stara Faktura</a></li>
				<li><a href="predracun/profak1.php">Novi Predracun</a></li>
				<li><a href="predracun/stara_profak.php">Stari Predracun</a></li>
				<li><a href="ponuda/ponuda1.php?ponuda_nova=1">Nova Ponuda</a></li>
				<li><a href="ponuda/ponuda_stara.php">Stara Ponuda</a></li>
				<li><a href="avansni_rac/avans_stari.php">Avansni racuni</a></li>
			</ul>
		</div>

		<div class="navi">
			<h4>Roba</h4>
			<ul>
				<li><a href="roba/pretra_rob.php">Pretraga i kartice</a></li>
				<li class="strelica_podmeni"><a href="#">Robne liste</a>
					<ul>
						<li><a href="roba/lager_lista.php">Lager lista</a></li>
						<li><a href="roba/upis_stanja.php">Upis stanja</a></li>
						<li><a href="roba/popisna_lista.php">Popisna lista</a></li>
						<li><a href="roba/lista_rob_razlika.php">Robne razlike</a></li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">Nivelacije</a>
					<ul>
						<li><a href="nivelacija/nivelacija1.php">Nova nivelacija</a></li>
						<li><a href="nivelacija/staraniv.php">Stara nivelacija</a></li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">Knjizna pisma rob.</a>
					<ul>
					<li><a href="#">Novo knjizno pis.</a>
						<ul>
							<li><a href="k_pisma/k_pis_r_k1.php">Kalkulacija</a></li>
							<li><a href="k_pisma/k_pis_r_f1.php">Dostavnica</a></li>
						</ul>
					</li>
					<li><a href="k_pisma/stara_k_p.php">Stara knjizna pis.</a></li>
					</ul>
				</li>
				<li><a href="k_pisma_fin/stara_k_pis_fin.php">Knjizna pisma fin.</a></li>
				<li class="strelica_podmeni"><a href="#">Unos roba i usluga</a>
					<ul>
						<li><a href="roba/nova_roba/nova_roba1.php">Nova Roba</a></li>
						<li><a href="roba/nova_roba/nova_usluga1.php">Nova Usluga</a></li>
						<li><a href="roba/nova_roba/pregled_usluga.php">Pregled usluga</a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="navi">
			<h4>Banka-blagajna</h4>
			<ul>
				<li class="strelica_podmeni"><a href="#">Izvod</a>
					<ul>
						<li><a href="banka/nova_banka.php">Banke</a></li>
						<li><a href="#">Izvodi</a>
							<ul><?php require("banka/menibanke.php");?></ul>
						</li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">Blagajna</a>
					<ul>
						<li><a href="blagajna/blag1.php">Blagajna nova</a></li>
						<li><a href="blagajna/blagizv.php">Blagajna stara</a></li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">Troskovi</a>
					<ul>
						<li><a href="blagajna/usluge1.php">Troskovi novi</a></li>
						<li><a href="blagajna/uslugeizv.php">Troskovi stari</a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="navi">
			<h4>Izvestaji</h4>
			<ul>
				<li class="strelica_podmeni"><a href="#">Nabavka</a>
					<ul>
						<li><a href="izvestaji/iz_nabavka.php">Po kalkulacijama</a></li>
						<li><a href="izvestaji/iz_nabavka_grupisano.php">Po firmama</a></li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">Prodaja</a>
					<ul>
						<li><a href="izvestaji/iz_prodaja.php">Po racunima</a></li>
						<li><a href="izvestaji/iz_prodaja_grupisano.php">Po firmama</a></li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">Kartica</a>
					<ul>
						<li><a href="izvestaji/kartica0.php">Kartica</a></li>
						<li><a href="izvestaji/kartica_dobavljac0.php">Kartica dobavljaci</a></li>
						<li><a href="izvestaji/kartica_kupac0.php">Kartica kupci</a></li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">PDV</a>
					<ul>
						<li><a href="izvestaji/iz_porez.php">PDV obracun</a></li>
						<li><a href="izvestaji/pdv_knizenje.php">PDV proknjizenja</a></li>
						<li><a href="izvestaji/pdv.php">PDV obrazac</a></li>
					</ul>
				</li>
				<li><a href="#">Troskovi</a></li>
				<li><a href="#">Kompenzacije</a></li>
			</ul>
		</div>

		<div class="navi">
			<h4>Okruzenje</h4>
			<ul>
				<li class="strelica_podmeni"><a href="#">Partneri</a>
					<ul>
						<li><a href="partneri/partner_novi1.html">Nov partner</a></li>
						<li><a href="partneri/partner_uredi1.php">Azuriraj partnere</a></li>
						<li><a href="partneri/upis_stanja_partnera.php">Pocetno stanje partnera</a></li>
					</ul>
				</li>
				<li><a href="izvestaji/potrazivanja.php">Potrazivanja</a></li>
				<li class="strelica_podmeni"><a href="#">Prenos stanja</a>
					<ul>
						<li><a href="roba/prenos_stan_auto.php">Automatski(uvoz)</a></li>
						<li><a href="roba/prenos_stan_rucno.php">Rucno</a></li>
						<li><a href="roba/sinh_prenos_st_roba.php">Sinhronizacija</a></li>
					</ul>
				</li>
				<li class="strelica_podmeni"><a href="#">Plate</a>
					<ul>
						<li><a href="izvestaji/obracun_plate.php">Nova plata</a></li>
						<li><a href="izvestaji/plate_pregled.php">Pregled plata</a></li>
					</ul>
				</li>
				<li><a href="include/poreske_stope.php">Poreske stope</a></li>
				<li class="strelica_podmeni"><a href="#">Zavrsni r.</a>
					<ul>
						<li><a href="zavrsni_r/knjiga1.php">Glavna knjiga</a></li>
						<li><a href="zavrsni_r/knjiga_po_kontu.php">Glavna knjiga po kontu</a></li>
						<li><a href="zavrsni_r/kontiranje_glk1.php">Kontiranje GLK</a></li>
						<li><a href="zavrsni_r/kontni_plan.php">Kontni plan</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="cf"></div>
	<div id="wrapindex">
		<div id="pp">
			<div class="portalkol">
				<div class="grafikoni" >
					 <div class="chart">
						<table id="data-table" border="1" cellpadding="10" cellspacing="0" summary="Prodaja robe od januara do decembra">
						   <caption>&nbsp;</caption>
						   <thead>
							  <tr>
								 <td>&nbsp;</td>
								 <th scope="col">1</th>
								 <th scope="col">2</th>
								 <th scope="col">3</th>
								 <th scope="col">4</th>
								 <th scope="col">5</th>
								 <th scope="col">6</th>
								 <th scope="col">7</th>
								 <th scope="col">8</th>
								 <th scope="col">9</th>
								 <th scope="col">10</th>
								 <th scope="col">11</th>
								 <th scope="col">12</th>
							  </tr>
						   </thead>
						   <tbody>
							  <tr>
								 <th scope="row">Prodaja</th>
								 <?php MesecnaProdaja();?>
							  </tr>
							  <tr>
								 <th scope="row">Nabavka</th>
								 <?php MesecnaNabavka();?>
							  </tr>
								<tr>
								 <th scope="row">Prod-Nab</th>
								 <?php MesecnaRazlika();?>
							  </tr>
							  <tr>
								 <th scope="row">Razlika RUC</th>
								 <?php MesecnaRazlikaUCeni();?>
							  </tr>
						   </tbody>
						</table>
					 </div>
					</div>
			</div>

			<div class="portalkol2">
				<div class="podsetnik_nosac" title="Podsetnik">
					<div id="loadpodsetnik"><img src="include/images/loading.gif"/></div>
					<p>Podsetnik:</p>
					<table id="tabele">
						<tr>
							<td>Partner</td>
							<td>Br. racuna</td>
							<td>Iznos</td>
							<td>Valuta</td>
						</tr>
						<?php

						$upit_podsetnik = "SELECT brojpod, partner, poziv_na_b, iznos, date_format(datum_za_plac, '%d. %m. %Y.')
							AS datum_za_placf FROM pods_kalk
							ORDER BY datum_za_plac ASC";
						foreach ($baza_pdo->query($upit_podsetnik) as $niz_podsetnik) { 
							$partner=$niz_podsetnik['partner'];
							$poziv_na_b=$niz_podsetnik['poziv_na_b'];
							$iznos=$niz_podsetnik['iznos'];
							$datum_za_plac=$niz_podsetnik['datum_za_placf'];
							$brojpod=$niz_podsetnik['brojpod'];
							?>
							<tr>
								<td><?php echo $partner;?></td>
								<td><?php echo $poziv_na_b;?></td>
								<td><?php echo $iznos;?></td>
								<td><?php echo $datum_za_plac;?></td>
								<td id="<?php echo $brojpod;?>" class="podsetnik_brisi">
									<a href="#" ><img src="include/images/mini8.png" alt="Brisi" /></a>
								</td>
							</tr>
							<?php
						}
						?>
				</table>
				</div>
			</div>
			<div class="cf"></div>
		</div>
	</div>
</div>
</body>
</html>
