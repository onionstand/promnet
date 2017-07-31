<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
    <title>Nivelacija</title>
  </head>
  <body>
    <?php require("../include/DbConnection.php"); ?>
    <div class="nosac_sa_tabelom">
      <div class="memorandum screen_hide">
        <?php include("../include/ConfigFirma.php");
        echo $inkfirma;?>
      </div>
      <?php
      $br_niv=$_POST['br_niv'];
      $upit = mysql_query("SELECT date_format(datum_niv, '%d. %m. %Y.') as formatted_date FROM nivel WHERE broj_niv='$br_niv'");
      while($niz = mysql_fetch_array($upit))
      	{
        	echo "<p>Datum: ";
        	echo $niz['formatted_date'];
      	}
        echo "<br>Broj nivelacije: ";
      	echo $br_niv;
        echo "</p>";
      ?>
      <table>
        <tr>
          <td colspan="5">Stara roba</td>
          <td colspan="3">Nova roba</td>
          <td></td>
        </tr>
        <tr>
          <td>Br.</td>
          <td>Sifra</td>
          <td>Naziv</td>
          <td>Cena</td>
          <td>Kolicina</td>
          <td>Sifra</td>
          <td>Novi naziv</td>
          <td>Cena</td>
          <td>Razlika</td>
        </tr>
        
        <?php
        $upit2 = mysql_query("
        SELECT niv_robe.id, niv_robe.srob, niv_robe.srob_niv, niv_robe.koli_niv, roba.naziv_robe, roba.cena_robe
        FROM niv_robe
        RIGHT JOIN roba ON niv_robe.srob=roba.sifra
        WHERE br_niv='$br_niv'
        ");
        $upit3 = mysql_query("
        SELECT niv_robe.srob, niv_robe.srob_niv, niv_robe.koli_niv, niv_robe.iznos_niv, roba.naziv_robe, roba.cena_robe
        FROM niv_robe
        RIGHT JOIN roba ON niv_robe.srob_niv=roba.sifra
        WHERE br_niv='$br_niv'
        ");
        $iznos_niv_zbir=0;
        $i=0;
        while($niz2 = mysql_fetch_array($upit2) and $niz3 = mysql_fetch_array($upit3))
      	{
          $iznos_niv_zbir+=$niz3['iznos_niv'];
          $i++;
          ?>

          <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $niz2['srob'];?></td>
            <td><?php echo $niz2['naziv_robe'];?></td>
            <td><?php echo $niz2['cena_robe'];?></td>
            <td><?php echo $niz2['koli_niv'];?></td>
            <td><?php echo $niz3['srob_niv'];?></td>
            <td><?php echo $niz3['naziv_robe'];?></td>
            <td><?php echo $niz3['cena_robe'];?></td>
            <td><?php echo $niz3['iznos_niv'];?></td>
            <td class="print_hide">
              <form action='niv_brisi_stavku.php' method='post'>
                <input type='hidden' name='br_niv' value='<?php echo $br_niv;?>'/>
                <input type='hidden' name='id_niv_robe' value="<?php echo $niz2['id'];?>"/>
                <input type='image' id='btnPrint' src='../include/images/iks.png' title='Ispravi'/>
              </form>
            </td>
          </tr>
        <?php
      	}
        ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Zbir:</td>
          <td><?php echo $iznos_niv_zbir;?></td>
        </tr>
      </table>
      <form action="nivelacija1.php" method="post">
        <input type="hidden" name="br_niv" value="<?php echo $br_niv; ?>"/>
        <button type='submit' class='dugme_zeleno print_hide'>Dodaj robu</button>
      </form>
      <button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
      <a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>
    </div>
  </body>
</html>