<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
    <title>Kalkulacija</title>
  </head>
  <body>
    <div class="nosac_sa_tabelom">
      <?php require("../include/DbConnection.php"); 
      $izdost = mysql_query("SELECT profak.broj_prof, profak.sifra_fir, date_format(profak.datum_prof, '%d. %m. %Y.') AS datumf, profak.izzad, dob_kup.sif_kup, dob_kup.naziv_kup FROM profak 
      LEFT JOIN dob_kup ON profak.sifra_fir=dob_kup.sif_kup");
      ?>
      <h2>Stare profakture</h2>
      <table>
        <tr>
          <th>Broj fak.dos.</th>
          <th>Kupac</th>
          <th>Datum</th>
          <th>Iznos</th>
          <th></th>
        </tr>
        <?php
        while($niz = mysql_fetch_array($izdost)){
        ?>
          <tr>
            <td><?php echo $niz['broj_prof'];?></td>
            <td><?php echo $niz['naziv_kup'];?></td>
            <td><?php echo $niz['datumf'];?></td>
            <td><?php echo $niz['izzad'];?></td>
            <td>
              <form action="profak5.php" method="post">
                <input type="hidden" name="broj_profak" value="<?php echo $niz['broj_prof'];?>"/>
                <input type="image" src="../include/images/olovka.png" title="Ispravi" />
              </form>
            </td>
          </tr>
          <?php } ?>
      </table>
      <a href="../index.php" class="dugme_zeleno_92plus4">Pocetna strana</a>
      <div class="cf"></div>
    </div>
  </body>
</html>