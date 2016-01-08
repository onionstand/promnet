<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html" />
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">
		<style>
			.editbox{display:none; width: 90%;}
			.edit_tr:hover{background-color:#5F90B0;cursor:pointer;}
		</style>
		<script src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script src="../include/jquery/jquery.ui.core.js"></script>
		<script src="../include/jquery/jquery.ui.widget.min.js"></script>
		<title>Knjizno pismo robno</title>

		<script type="text/javascript">
			$(document).ready(function()
			{
				$(".edit_tr").click(function(){
					var ID=$(this).attr('id');
					$("#stanje_"+ID).hide();
					$("#kupci_"+ID).hide();
					$("#dobavljaci_"+ID).hide();
					$("#stanje_input_"+ID).show();
					$("#kupci_input_"+ID).show();
					$("#dobavljaci_input_"+ID).show();
				}).change(function(){
					var ID=$(this).attr('id');
					var stanje=$("#stanje_input_"+ID).val();
					var kupci=$("#kupci_input_"+ID).val();
					var dobavljaci=$("#dobavljaci_input_"+ID).val();
					var dataString = 'id='+ ID +'&stanje='+stanje +'&kupci='+kupci+'&dobavljaci='+dobavljaci;
					$("#stanje_"+ID).html('<img src="../include/images/olovka.png" />'); // Loading slika

					if(stanje.length>0 && kupci.length>0 && dobavljaci.length>0){
						$.ajax({
							type: "POST",
							url: "upis_stanja_partnera2.php",
							data: dataString,
							cache: false,
							success: function(html){
								$("#stanje_"+ID).html(stanje);
								$("#kupci_"+ID).html(kupci);
								$("#dobavljaci_"+ID).html(dobavljaci);
							}
						});
					}
					else
					{
						alert('Greska.');
					}

				});

				// Edit input box click action
				$(".editbox").mouseup(function(){
					return false
				});

				// Outside click action
				$(document).mouseup(function(){
					$(".editbox").hide();
					$(".text").show();
				});
			});
		</script>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<table class='tabele'>
				<tr>
					<th>Sifra</th>
					<th>Ime partnera</th>
					<th>PIB</th>
					<th>Kupci</th>
					<th>Dobavljaci</th>
					<th>Stanje</th>
				</tr>
				<?php 
				require("../include/DbConnectionPDO.php");
				$upit="SELECT * FROM dob_kup";

				foreach ($baza_pdo->query($upit) as $niz){
				$sifra=$niz['sif_kup'];
				$naziv_kup=$niz['naziv_kup'];
				$pib=$niz['pib'];
				$stanje=$niz['stanje'];
				$kupci=$niz['kupci'];
				$dobavljaci=$niz['dobavljaci'];
				?>	
				<tr class="edit_tr" id="<?php echo $sifra; ?>">
					<td><?php echo $sifra; ?></td>
					<td><?php echo $naziv_kup; ?></td>
					<td><?php echo $pib; ?></td>
					<td class="edit_td">
						<span id="kupci_<?php echo $sifra; ?>" class="text"><?php echo $kupci; ?></span>
						<input type="text" value="<?php echo $kupci; ?>" class="editbox" id="kupci_input_<?php echo $sifra; ?>" />
					</td>
					<td class="edit_td">
						<span id="dobavljaci_<?php echo $sifra; ?>" class="text"><?php echo $dobavljaci; ?></span>
						<input type="text" value="<?php echo $dobavljaci; ?>" class="editbox" id="dobavljaci_input_<?php echo $sifra; ?>" />
					</td>
					<td class="edit_td">
						<span id="stanje_<?php echo $sifra; ?>" class="text"><?php echo $stanje; ?></span>
						<input type="text" value="<?php echo $stanje; ?>" class="editbox" id="stanje_input_<?php echo $sifra; ?>" />
					</td>
				</tr>
				<?php } ?>	
			</table>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
			<button class="dugme_plavo" onClick='window.print()' type='button'>Stampa</button>
			<div class="cf"></div>
		</div>
	</body>
</html>