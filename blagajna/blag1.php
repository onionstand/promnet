<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<link rel="stylesheet" type="text/css" href="../include/css/tab.css">
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">

	<title>Blagajna</title>

	<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">
	<script src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>
	<script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<script>
		$(function() {
			$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {

			//When page loads...
			$(".tab_content").hide(); //Hide all content
			$("label,input").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content

			//On Click Event
			$("ul.tabs li").click(function() {

				$("ul.tabs li").removeClass("active"); //Remove any "active" class
				$(this).addClass("active"); //Add "active" class to selected tab
				$(".tab_content").hide(); //Hide all tab content

				var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
				$(activeTab).fadeIn(); //Fade in the active ID content
				return false;
			});
			
			
			 $("#obaveznaf").validity(function() {
							$("#biracdatuma")
								.require("Polje je neophodno...");
								
							$("#opis")
								.require("Polje je neophodno...");
								
							$(".iznosa")
								.require("Polje je neophodno...")
								.match("number","Mora biti broj.")
								.sumMin(1);
						});

			 $("#konto").AddIncSearch({
				maxListSize: 4,
				maxMultiMatch: 80,
				selectBoxHeight: 400,
				warnMultiMatch: 'prvih {0} poklapanja ...',
				warnNoMatch: 'nema poklapanja...'
			});

		});
	</script>
</head>
<body>
<div class="nosac_glavni_400">
	<?php
	require("../include/DbConnection.php");

	if (isset($_POST['datum'])&&($_POST['opis']))
	{
	$dan=$_POST['datum'];
	$dan2=strtotime( $dan );
	$datum=date("Y-m-d",$dan2);
	$opis=$_POST['opis'];
	$izlaz=$_POST['izlaz'];
		
	//niza stopa
	$result_porez_niza_stopa = mysql_query("SELECT porez_procenat FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = 10)
								AND tarifa_stope = 10
								AND porez_datum <= '$datum'");
	$row_porez_niza_stopa = (mysql_fetch_array($result_porez_niza_stopa));
	$procenat_nize_stope=$row_porez_niza_stopa['porez_procenat'];
	//visa stopa
	$result_porez_visa_stopa = mysql_query("SELECT porez_procenat FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = 20)
								AND tarifa_stope = 20
								AND porez_datum <= '$datum'");
	$row_porez_visa_stopa = (mysql_fetch_array($result_porez_visa_stopa));
	$procenat_vise_stope=$row_porez_visa_stopa['porez_procenat'];
	//	
	if (isset($_POST['oduzmipdv']))
			{	
				$pdv_izn=$_POST['oduzmipdv'];
			}
		else{$oduzmipdv=0;
			$pdv_izn=0;}
		
		
		
	$ulaz=$_POST['ulaz'];
	$br_uplate=$_POST['br_uplate'];
	$konto=$_POST['konto'];
	$broj_isecka=$_POST['broj_isecka'];
	mysql_query("INSERT INTO blagajna (br_konta,opis_troska,blagulaz,blagizn,pdv_izn,datum,brupl,napomena)
	VALUES ('$konto','$opis','$ulaz','$izlaz','$pdv_izn','$datum','$br_uplate','$broj_isecka')");
	$br_blagajne=mysql_insert_id();

	$upit4 = mysql_query("SELECT * FROM blagajna WHERE br_blag='$br_blagajne' ");
	while($niz4 = mysql_fetch_array($upit4))
	{
		$datumf=$niz4['datum'];
		$br_konta2=$niz4['br_konta'];
		$opis_troska2=$niz4['opis_troska'];
		$blagulaz2=$niz4['blagulaz'];
		$blagizn2=$niz4['blagizn'];
		$pdv_izn2=$niz4['pdv_izn'];
		$brupl2=$niz4['brupl'];
		$napomena2=$niz4['napomena'];
	}?>
		<p style="text-align:center">
			Broj blagajne: <?php echo $br_blagajne;?><br>
			Datum: <?php echo $datumf;?><br>
			Konto: <?php echo $br_konta2;?><br>
			Opis: <?php echo $opis_troska2;?><br>
			Ulaz:<?php echo $blagulaz2;?><br>
			Izlaz: <?php echo $blagizn2;?><br>
			PDV: <?php echo $pdv_izn2;?><br>
			Broj uplate: <?php echo $brupl2;?><br>
			Napomena: <?php echo $napomena2;?>
		</p>
	<div class="dugme_roba">
		<a href="../index.php" class="dugme_zeleno_92plus4">
			Pocetna strana
		</a>
	</div>
	<?php }
	else {
	?>
	<h2>Blagajna</h2>
	<form method="post" id="obaveznaf" >
		<label>Datum: </label>
		<input id="biracdatuma" type="text" name="datum" value="" class="polje_100_92plus4" />
		<label>Opis:</label>
		<input type="text" name="opis" id="opis" class="polje_100_92plus4"/>
		<label>Ulaz-izlaz: </label>
		<ul class="tabs">
			<li><a href="#tab1">Izlaz</a></li>
			<li><a href="#tab2">Ulaz</a></li>
		</ul>
		<div class="cf"></div>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<label>Iznos: </label>
				<input type="text" name="izlaz" value="0" class="iznosa polje_100_92plus4"/>
				<label>PDV za oduzimanje: </label>
				<input id="oduzmipdv" type="text" name="oduzmipdv" class="polje_100_92plus4" value="0"/>
				<label>Broj racuna: </label>
				<input type="text" name="broj_isecka" value="0" class="polje_100_92plus4"/>
			</div>
			<div id="tab2" class="tab_content">
				<label>Iznos: </label>
				<input type="text" name="ulaz" value="0" class="iznosa polje_100_92plus4"/>
				<label>Broj uplate: </label>
				<input type="text" value="0" name="br_uplate"  class="polje_100_92plus4"/>
			</div>
		</div>
		<div class="cf"></div>
		<label>Konto: </label>
		<select id='konto' name='konto' size='1' class='polje_100'>
			<option value=''>Odaberi ... </option>
			<?php 
			$upit = mysql_query("SELECT * FROM konto");
			while($red = mysql_fetch_array($upit))
			{
				$konto=$red['naziv_kont'];
				$broj_kont=$red['broj_kont'];
				?>
				<option value='<?php echo $broj_kont;?>'><?php echo $konto . " - " . $broj_kont;?></option>
				<?php 
			} ?>
		</select>
		<button type="submit" class="dugme_zeleno">Unesi</button>
	</form>
	<form action="../index.php" method="post">
		<button type="submit" class="dugme_crveno">Ponisti</button>
	</form>
	<?php } ?>
	<div class="cf"></div>
</div>
</body>
</html>