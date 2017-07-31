<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
  <script type="text/javascript" src="../include/form/jquery.validity.js"></script>
  <script src="../include/jquery/jquery-1.6.2.min.js"></script>
  <script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
  <script type="text/javascript" src="../include/form/jquery.validity.js"></script>

  <script type="text/javascript">
   jQuery(document).ready(function() {
      jQuery("#firma").AddIncSearch({
          maxListSize: 4,
          maxMultiMatch: 50,
          selectBoxHeight: 400,
          warnMultiMatch: 'prvih {0} poklapanja ...',
          warnNoMatch: 'nema poklapanja...'
      });
  	});
  </script>
  <title>Kartica</title>
</head>
<body>
<div class="nosac_glavni_400">
  <?php
  require("../include/DbConnection.php");?>
  <h2>Kartica partnera</h2>
  <form action="kartica.php" method='post'>
    <label>Partner:</label>
    <select id='firma' name='partnersif' size='1' class='polje_100'>
      <option value=''>Odaberi ... </option>
      <?php
      $upit = mysql_query("SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup");
      while($red = mysql_fetch_array($upit)){
        $naziv_kup=$red['naziv_kup'];
        $sif_kup=$red['sif_kup'];
        echo "<option value='".$sif_kup."'>".$naziv_kup."</option>";
      }
      ?>
    </select>
    <div class="cf"></div>
    <button type="submit" class="dugme_zeleno">Unesi</button>
    <a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
  </form>
  <div class="cf"></div>
</div>
</body>