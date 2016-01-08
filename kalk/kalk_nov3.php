<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
		<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
		<script type="text/javascript">
		 jQuery(document).ready(function() {

			$("#obaveznaf").validity(function() {
		                    $("#polje_kalkcena")
		                        .require("Popuni polje.")
								.match("number","Mora biti broj.");
							$("#porez-pdv")
								.require("Popuni polje.");
							$("#rabat_id")
		                        .require("Popuni polje!")
		                        .match("number","Mora biti broj.")
		                        .range(0, 99, "Mora biti izmedju 0 i 100.");
		                    $("#marza_id")
		                        .require("Popuni polje!")
		                        .match("number","Mora biti broj.")
		                        .range(0, 99, "Mora biti izmedju 0 i 100.");
								
		                });
			
			$("#polje_kalkcena:visible:first").focus();
			$("#trazifokus:visible:first").focus();
			});
		</script>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php"); 
			/*zvanje sifre kalk*/ 
			$sifra_kalk=$_POST['broj_kalkulaci'];
			$datkal = "SELECT datum FROM kalk WHERE broj_kalk=$sifra_kalk";
							$vis = mysql_query($datkal) or die(mysql_error());
							$row_vis = mysql_fetch_assoc($vis);
							$datumzaporez=$row_vis['datum'];


			if (isset ($_POST['kalk_cena']) && ($_POST['porez']))
			//provera podataka
			{
				$pdv=$_POST['porez'];
				
				$result_pdv_procenat = mysql_query("SELECT porez_procenat, tarifa_stope
					FROM poreske_stope
					WHERE tarifa_stope = '$pdv'
					AND porez_datum=(
					SELECT MAX(porez_datum)
					FROM poreske_stope
					WHERE porez_datum <= '$datumzaporez'
					AND tarifa_stope = '$pdv'
					);") or die(mysql_error());
				while ($r = mysql_fetch_array($result_pdv_procenat)) {
				$pdv_procenat= $r['porez_procenat'];}
				
				if ($_POST['radio_pdv'] == 'bezpdv' ){
					$kalkcena2=$_POST['kalk_cena'];
					}
				if ($_POST['radio_pdv'] == 'sapdv' ){
					
					$kalkcena2=(($_POST['kalk_cena'])/(100+$pdv_procenat))*100;
					}
				
				$rabat2=$_POST['rabat'];
				$marza2=$_POST['marza'];
				$brojkalku=$_POST['broj_kalkulaci'];
				/*marza*/
				$a=$marza2*100;
				$b=100-$marza2;
				$marza3=$a/$b;
				/*prodajna cena*/
				$kalkcenabezrabat=($kalkcena2/100)*(100-$rabat2);
				//$prod_cena=($kalkcenabezrabat/100)*(100+$marza3);
				$prod_cena=$kalkcenabezrabat*(1+($marza3/100));
				/*aaaa*/
				

				$kalkcena_min_rab=($kalkcena2/100)*(100-$rabat2);

				echo "Broj kalkulacije: ";
				echo $sifra_kalk;
				echo "<br \>";
				echo "Proracunata cena: ";
				echo number_format($prod_cena, 2,".","");
				echo "<br \>";
				?>
				<label>Trazi:</label>
				<form action="kalk_nov4.php" method="post">
					<input type='hidden' name='broj_kalkulaci' value='<?php echo $sifra_kalk;?>'/>
					<input type='hidden' name='kalkcena_min_rab' value='<?php echo $kalkcena_min_rab;?>'/>
					<input type='hidden' name='porez_pdv' value='<?php echo $pdv;?>'/>
					<input type='hidden' name='rabat' value='<?php echo $rabat2;?>'/>
					<input type='hidden' name='kalkcena' value='<?php echo $kalkcena2;?>'/>
					<input type='hidden' name='marza' value='<?php echo $marza2;?>'/>
					<input type='hidden' name='prodajna_cena' value='<?php echo $prod_cena;?>'/>
					<select name='metode' size='1' class='polje_100'>
						<option value='naziv_robe'>naziv robe</option>
						<option value='sifra'>sifra robe</option>
					</select>
					<input type='text' name='search'  class='polje_100_92plus4' id='trazifokus' style="margin-top:0.3em;">
					<button type='submit' class='dugme_plavo'>Trazi</button>
				</form>
				<form action="kalk_nov5b_nov_rob.php" method="post">
					<input type="hidden" name="broj_kalkulaci" value="<?php echo $sifra_kalk; ?>"/>
					<input type='hidden' name='kalkcena_min_rab' value='<?php echo $kalkcena_min_rab;?>'/>
					<input type='hidden' name='kalkcena' value='<?php echo $kalkcena2;?>'/>
					<input type='hidden' name='porez_pdv' value='<?php echo $pdv;?>'/>
					<input type='hidden' name='rabat' value='<?php echo $rabat2;?>'/>
					<input type='hidden' name='marza' value='<?php echo $marza2;?>'/>
					<input type='hidden' name='prodajna_cena' value='<?php echo $prod_cena;?>'/>
					<button type='submit' class='dugme_plavo'>Nova roba</button>
				</form>
				<div class="cf"></div>

				<?php

			}
			else
			{ 
			?>
			<form id='obaveznaf' action='' method='post'>
				<p>Broj kalkulacije: <?php echo $sifra_kalk;?></p>
				<label>Kalk. cena:</label>
				<input type="radio" name="radio_pdv" value="bezpdv" checked="checked" /> bez PDV 
				<input type="radio" name="radio_pdv" value="sapdv" /> sa PDV 
				<input type='text' name='kalk_cena' class='polje_100_92plus4' id='polje_kalkcena'/>
				<label>Porez:</label>
				<select name='porez' class='polje_100' id="porez-pdv">
					<option value=''>Odaberi</option>
					<?php 
					$result = mysql_query("SELECT id_poreske_stope, porez_procenat, porez_datum, tarifa_stope
								FROM poreske_stope S
								WHERE porez_datum=(
								SELECT MAX(porez_datum)
								FROM poreske_stope
								WHERE tarifa_stope = S.tarifa_stope
								AND porez_datum <= '$datumzaporez'
								);");
					while ($r = mysql_fetch_array($result)) { ?>
						<option value='<?php  echo $r['tarifa_stope'];?>'><?php  echo $r['porez_procenat'];?></option>
					<?php }	?>
				</select>
				<label>Rabat:</label>
				<input type='text' name='rabat' class='polje_100_92plus4' value='0' id='rabat_id'/>
				<label>Marza (%):</label>
				<input type='text' name='marza' size='3' class='polje_100_92plus4' value='0' id='marza_id'/>
				<div class="cf"></div>
				<input type='hidden' name='broj_kalkulaci' value='<?php echo $sifra_kalk;?>'/>
				<button type='submit' class='dugme_zeleno'>Unesi</button>
			</form>
			<?php } ?>
			<form action="kalk_nov6.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $sifra_kalk; ?>"/>
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>