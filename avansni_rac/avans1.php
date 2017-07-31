<?php
require("../include/DbConnectionPDO.php");

//id 	id_firme 	opis   osnovica 	porez 	zbir 	datum 

if(isset($_GET['novo_pismo'])) {
	$sql = 'INSERT INTO avans_rac (datum)
		  VALUES(:datum)';
	$stmt = $baza_pdo->prepare($sql);
	$d=date("Y-m-d");
	$stmt->bindParam(':datum', $d, PDO::PARAM_STR);
	$stmt->execute();
	$id_pisma = $baza_pdo->lastInsertId();
}

if(isset($_GET['satro_pismo'])) {
	//$id_pisma = $_GET['satro_pismo'];
	$id_pisma = filter_input(INPUT_GET, 'satro_pismo', FILTER_SANITIZE_STRING);
	$upit_staro_pis = "SELECT * FROM avans_rac WHERE id = $id_pisma";
	foreach ($baza_pdo->query($upit_staro_pis) as $red_staro_pis) {
		$id_pisma=$red_staro_pis['id'];
		$partner_id=$red_staro_pis['id_firme'];
		$opis=$red_staro_pis['opis'];
		$osnovica=$red_staro_pis['osnovica'];
		$pdv=$red_staro_pis['porez'];
		$zbir=$red_staro_pis['zbir'];
		$datum=date("d-m-Y",(strtotime($red_staro_pis['datum'])));
	}
}


if(isset($_POST['id_pisma_u'])) {
	$sql = 'UPDATE avans_rac SET id_firme=:partner_id, opis=:opis, osnovica=:osnovica, porez=:pdv, zbir=:zbir, datum=:datum_baza
		  WHERE id = :id_pisma_u';
	$stmt = $baza_pdo->prepare($sql);

	$partner_id = filter_input(INPUT_POST, 'partnersif', FILTER_SANITIZE_STRING);
	$opis = filter_input(INPUT_POST, 'opis', FILTER_SANITIZE_STRING);
	$osnovica = filter_input(INPUT_POST, 'osnovica', FILTER_SANITIZE_STRING);
	$pdv = filter_input(INPUT_POST, 'pdv', FILTER_SANITIZE_STRING);
	$zbir = filter_input(INPUT_POST, 'zbir', FILTER_SANITIZE_STRING);
	$id_pisma_u = filter_input(INPUT_POST, 'id_pisma_u', FILTER_SANITIZE_STRING);
	$datum_baza=date("Y-m-d",(strtotime($_POST['datum'])));

	$stmt->bindParam(':partner_id', $partner_id, PDO::PARAM_STR);
	$stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
	$stmt->bindParam(':osnovica', $osnovica, PDO::PARAM_STR);
	$stmt->bindParam(':pdv', $pdv, PDO::PARAM_STR);
	$stmt->bindParam(':zbir', $zbir, PDO::PARAM_STR);
	$stmt->bindParam(':id_pisma_u', $id_pisma_u, PDO::PARAM_STR);
	$stmt->bindParam(':datum_baza', $datum_baza, PDO::PARAM_STR);
	$stmt->execute();
	$OK = $stmt->rowCount();
	if ($OK) {
		$info="Pismo je uredjeno.";
	}

	$upit_staro_pis = "SELECT * FROM avans_rac WHERE id = $id_pisma_u";
	foreach ($baza_pdo->query($upit_staro_pis) as $red_staro_pis) {
		$id_pisma=$red_staro_pis['id'];
		$partner_id=$red_staro_pis['id_firme'];
		$opis=$red_staro_pis['opis'];
		$osnovica=$red_staro_pis['osnovica'];
		$pdv=$red_staro_pis['porez'];
		$zbir=$red_staro_pis['zbir'];
		$datum=date("d-m-Y",(strtotime($red_staro_pis['datum'])));
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Avansni račun</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
		<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
		<script src="../include/jquery/jquery.ui.core.js"></script>
		<script src="../include/jquery/jquery.ui.widget.min.js"></script>
		<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
		<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>
		<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
		<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">
		<script type="text/javascript">
			function roundNumber(number,decimals) {
			  var newString;// The new rounded number
			  decimals = Number(decimals);
			  if (decimals < 1) {
			    newString = (Math.round(number)).toString();
			  } else {
			    var numString = number.toString();
			    if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
			      numString += ".";// give it one at the end
			    }
			    var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
			    var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
			    var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
			    if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
			      if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
			        while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
			          if (d1 != ".") {
			            cutoff -= 1;
			            d1 = Number(numString.substring(cutoff,cutoff+1));
			          } else {
			            cutoff -= 1;
			          }
			        }
			      }
			      d1 += 1;
			    } 
			    if (d1 == 10) {
			      numString = numString.substring(0, numString.lastIndexOf("."));
			      var roundedNum = Number(numString) + 1;
			      newString = roundedNum.toString() + '.';
			    } else {
			      newString = numString.substring(0,cutoff) + d1.toString();
			    }
			  }
			  if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
			    newString += ".";
			  }
			  var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
			  for(var i=0;i<decimals-decs;i++) newString += "0";
			  //var newNumber = Number(newString);// make it a number if you like
			  return newString; // Output the result to the form field (change for your purposes)
			}

			function update_iznos() {
				var zbir = Number($("#osnovica").val()) + Number($("#pdv").val()) ;
				zbir = roundNumber(zbir,2);
				$('#zbir').val(zbir);
			}

			$(document).ready(function() {
				$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);

			    $("#firma").AddIncSearch({
			        maxListSize: 4,
			        maxMultiMatch: 50,
			        selectBoxHeight: 400,
			        warnMultiMatch: 'top {0} matches ...',
			        warnNoMatch: 'nema poklapanja...'
			    });
				 $("#obaveznaf").validity(function() {
			                    $(".polje_100_92plus4",".polje_100")
			                        .require("Polje je neophodno...");
			                    $("#rok_placanja")
			                    	.match("number","Mora biti broj.");
			                });

				$("#firma").focus();

				$(".izracunaj").blur(update_iznos);	
			});

		</script>
	</head>
	<body>
		
		<div class="nosac_glavni_400">
			<p>Avansni račun br. <?php if(isset($id_pisma)) {echo $id_pisma; }?></p>
			<?php if(isset($info)) {echo "<p>".$info."</p>"; }?>
			<form id="obaveznaf" method="post" action="avans1.php">
				<label>Partner:</label>
				<select id='firma' name='partnersif' size='1' class='polje_100'>
					<option value=''>Odaberi</option>
					<?php
					$upit = 'SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup';
					foreach ($baza_pdo->query($upit) as $red) {
						$naziv_kup=$red['naziv_kup'];
						$sif_kup=$red['sif_kup'];
						?>
						<option value='<?php echo $sif_kup;?>' <?php if(isset($partner_id) && $partner_id == $sif_kup) {echo "selected"; }?>><?php echo $naziv_kup;?></option>
					<?php } ?>
				</select>
				<label>Datum:</label>
				<input id="biracdatuma" type="text" name="datum" <?php if(isset($datum)) {echo "value='".$datum."'"; }?> class="date" />
				<label>Osnovica:</label>
				<input type="text" name="osnovica" id="osnovica" class="polje_100_92plus4 izracunaj" <?php if(isset($osnovica)) {echo "value='".$osnovica."'"; }?>/>
				<label>PDV:</label>
				<input type="text" name="pdv" id="pdv" class="polje_100_92plus4 izracunaj" <?php if(isset($pdv)) {echo "value='".$pdv."'"; }?>/>
				<label>Zbir:</label>
				<input type="text" name="zbir" id="zbir" class="polje_100_92plus4 izracunaj" <?php if(isset($zbir)) {echo "value='".$zbir."'"; }?>/>
				<label>Opis:</label>
				<textarea name="opis" class="polje_100_92plus4" rows="12"><?php if(isset($opis)) {echo $opis; }?></textarea>
				<input type='hidden' name='id_pisma_u' <?php if(isset($id_pisma)) {echo "value='".$id_pisma."'"; }?> <?php if(isset($id_pisma_u)) {echo "value='".$id_pisma_u."'"; }?>/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form action="avans_print.php" method="post">
				<input type='hidden' name='id_pisma' <?php if(isset($id_pisma)) {echo "value='".$id_pisma."'"; }?> <?php if(isset($id_pisma_u)) {echo "value='".$id_pisma_u."'"; }?>/>
				<button type="submit" class="dugme_crveno">Stampaj</button>
			</form>
			<form action="avans_stari.php" method="post">
				<button type="submit" class="dugme_crveno">Nazad</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>