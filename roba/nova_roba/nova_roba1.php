<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html" />
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../../include/css/stil2.css">
    <title>Unos Robe</title>
    <script type="text/javascript" src="../../include/jquery/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="../../include/form/jquery.validity.js"></script>
    <link rel="stylesheet" type="text/css" href="../../include/form/jquery.validity.css">
    <script type="text/javascript">
      jQuery(document).ready(function() {

      	$("#obaveznaf").validity(function() {
      						$("#porez-pdv,#cena")
      							.require("Neophodno polje.")
      							.match("number","Mora biti broj.");
      						$("#ime_robe,#jedinica_mere,#porez-pdv")
      							.require("Neophodno polje.");
      					});

      	$("#ime_robe:visible:first").focus();
      	$("#daljebuton:visible:first").focus();
      });
    </script>
  </head>
  <body>
    <div class="nosac_glavni_400">
      <?php require("../../include/DbConnectionPDO.php");
      if (isset($_POST['ime_rob']) && ($_POST['cena_robe']) && ($_POST['jed_mere']) && ($_POST['porez_pdv']))
      {

        $ime_rob=$_POST['ime_rob'];
        $cena=$_POST['cena_robe'];
        $jed_mere=$_POST['jed_mere'];
        $porez_pdv=$_POST['porez_pdv'];

        $OK = false;
        $sql = 'INSERT INTO roba (naziv_robe, cena_robe, porez, stanje, jed_mere, ruc, kolicina, poc_stanje, usluga_opis)
      		  VALUES(:naziv_robe, :cena_robe, :porez, 0, :jed_mere, 0, 0, 0,:naziv_robe)';

        $stmt = $baza_pdo->prepare($sql);

        $stmt->bindParam(':naziv_robe', $ime_rob);
        $stmt->bindParam(':cena_robe', $cena);
        $stmt->bindParam(':porez', $porez_pdv);
        $stmt->bindParam(':jed_mere', $jed_mere);

        $stmt->execute();
        $OK = $stmt->rowCount();

        if ($OK) {?>
        	<p>Roba "<?php echo $ime_rob;?>" je uneta</p><br>
        <?php
        }
        else {
        	$error = $stmt->errorInfo();
        	if (isset($error[2])) {
        	  $error = $error[2];
            }
        }
        ?>
        <a href="../../index.php" class="dugme_zeleno_92plus4 print_hide" id="daljebuton">Početna</a>
        <?php
      }
      else
      {
        ?>
        <h2>Unos Robe</h2>
        <form action="" method="post" id="obaveznaf">
          <label>Ime robe:</label>
          <input type="text" name="ime_rob" class="polje_100_92plus4" id="ime_robe"/>
          <label>Jed. mere:</label>
          <input type="text" name="jed_mere" class="polje_100_92plus4" id="jedinica_mere"/>
          <label>Cena:</label>
          <input type="text" name="cena_robe" class="polje_100_92plus4" id="cena"/>

          <label>Porez:</label>
  				<select name='porez_pdv' class='polje_100' id="porez-pdv">
  					<option value=''>Odaberi</option>
  					<?php
  					$sql = 'SELECT id_poreske_stope, porez_procenat, porez_datum, tarifa_stope
  								FROM poreske_stope S
  								WHERE porez_datum=(
    								SELECT MAX(porez_datum)
    								FROM poreske_stope
    								WHERE tarifa_stope = S.tarifa_stope
    								AND porez_datum <= CURDATE()
  								)';
  				  $result = $baza_pdo->query($sql);
  					$error = $baza_pdo->errorInfo();
  					if (isset($error[2])) die($error[2]);
  					foreach ($result as $row) {

  					  ?>
  					  <option value='<?php echo $row['tarifa_stope'];?>'><?php echo $row['porez_procenat'];?></option>
  					  <?php
  					}
  					?>
  				</select>

          <button type="submit" class="dugme_zeleno">Unesi</button>
          <a href="nova_usluga1.php" class="dugme_crveno_92plus4 print_hide" id="daljebuton">Dodaj uslugu</a>
          <a href="../../index.php" class="dugme_zeleno_92plus4 print_hide" id="daljebuton">Početna</a>
        </form>
      <?php } ?>
      <div class="cf"></div>
    </div>
  </body>
</html>
