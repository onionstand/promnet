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
      						$("#kolicina,#kalk_cena,#polje_porez,#pro_cena")
      							.require("Neophodno polje.")
      							.match("number","Mora biti broj.");
      						$("#ime_robe,#jedinica_mere")
      							.require("Neophodno polje.");
                  $("#polje_rabat")
                    .require("Neophodno polje.")
                    .match("number","Mora biti broj.")
                    .range(0, 99, "Mora biti izmedju 0 i 100.");
      					});

      	$("#ime_robe:visible:first").focus();
      	$("#daljebuton:visible:first").focus();
      });
    </script>
  </head> 
  <body>
    <div class="nosac_glavni_400">
      <?php require("../include/DbConnection.php");
      /*zvanje sifre kalk*/ 
      $sifra_kalk=$_POST['broj_kalkulaci'];

      if (isset($_POST['ime_rob']) && ($_POST['kolicina']) && ($_POST['jed_mere']) && ($_POST['porez_pdv']))
      //provera podataka
      {
        $brojkalku=$_POST['broj_kalkulaci'];
        $pdv=$_POST['porez_pdv'];

        $kalkcena=$_POST['kalkcena'];
        $rabat2=$_POST['rabat'];
        $kolicina=$_POST['kolicina'];
        $kalkcena_min_rab=($kalkcena/100)*(100-$rabat2);
        $prod_cena=$_POST['prodajna_cena'];
        
        /*ruc*/
        $iznos_razlika_u_ceni=($prod_cena*$kolicina)-($kalkcena_min_rab*$kolicina);
        //$ruc=($iznos_razlika_u_ceni*100)/($prod_cena*$kolicina);
        $ruc=$iznos_razlika_u_ceni/(($prod_cena*$kolicina)/100);


        /*ubacivanje u roba*/
        $ubacir="INSERT INTO roba (naziv_robe, cena_robe, porez, stanje, jed_mere, ruc, poc_stanje, usluga_opis)
        VALUES
        ('$_POST[ime_rob]','$prod_cena', '$_POST[porez_pdv]', '$kolicina', '$_POST[jed_mere]','$ruc', '0', '$_POST[ime_rob]')";
        mysql_query($ubacir);
        $sifrarobe3 = mysql_insert_id();

        /*ubacivanje u ulaz*/
        $ubaciv="INSERT INTO ulaz (br_kal, srob_kal, kol_kalk, cena_k, rab_kalk)
        VALUES
        ('$brojkalku', '$sifrarobe3', '$kolicina','$kalkcena','$rabat2')";
        mysql_query($ubaciv);
        $id_ulaz = mysql_insert_id();

        ?>
        Roba je uneta<br />
        <form action="kalk_nov6.php" method="post">
          <input type="hidden" name="broj_kalkulaci" value="<?php echo $sifra_kalk;?>"/>
          <button type="submit" class="dugme_zeleno" id="daljebuton">Dalje</button>
        </form>
        <?php
      }

      else
      { 
        ?>
        <p>
          Broj kalkulacije: <?php echo $sifra_kalk;?> <br>
          Proracunata cena: <?php echo $_POST['prodajna_cena'];?>
        </p>
        <form action="" method="post" id="obaveznaf">
          <label>Ime robe:</label>
          <input type="text" name="ime_rob"  size="35" class="polje_100_92plus4" id="ime_robe"/>
          <label>Kolicina:</label>
          <input type="text" name="kolicina" size="6" class="polje_100_92plus4" id="kolicina"/>
          <label>Jed. mere:</label>
          <input type="text" name="jed_mere" size="4" class="polje_100_92plus4" id="jedinica_mere"/>
          <label>Kalk. cena:</label>
          <input type="text" name="kalkcena" value="<?php echo $_POST['kalkcena'];?>" class="polje_100_92plus4" id="kalk_cena"/>
          <label>Porez:</label>
          <input type="text" name="porez_pdv" size="4" value="<?php echo $_POST['porez_pdv'];?>" class="polje_100_92plus4" id="polje_porez"/>
          <label>Rabat:</label>
          <input type="text" name="rabat" size="4" value="<?php echo $_POST['rabat']; ?>" class="polje_100_92plus4" id="polje_rabat"/>
          <label>Prodajna cena:</label>
          <input type="text" name="prodajna_cena" value="<?php echo $_POST['prodajna_cena'];?>" class="polje_100_92plus4" id="pro_cena"/>
          <input type="hidden" name="broj_kalkulaci" value="<?php echo $_POST['broj_kalkulaci']; ?>" />
          <button type="submit" class="dugme_zeleno">Unesi</button>
        </form>
      <?php } ?>
      <div class="cf"></div>
    </div>
  </body>
</html>