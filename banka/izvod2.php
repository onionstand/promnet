<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
    <link rel="stylesheet" type="text/css" href="../include/css/tab.css">
    <link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
    <script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
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
    	
    	//When page loads...
    	$(".tab_content").hide(); //Hide all content
    	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
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

    	
      });
    </script>
    <script type="text/javascript">
        $(function() { 
          $("#izvod_forma").validity(function() {
            $("#firma")
              .require("Polje je neophodno...");
            $("#broj_dokumenta")
              .require("Polje je neophodno...");
      			$("#iznosa")
             .require("Polje je neophodno...")
    		  		.match("number","Mora biti broj.")
    	    });
        });
    </script>
    <script>
      $(document).ready(function() {
        $( "#uplata_dobavljacu" ).click(function() {
          $( "#polja_svrha option" ).replaceWith( $( '<option value="DOBAVLJAC">DOBAVLJAC</option><option value="USLUGE">USLUGE</option><option value="GOTOVINA">GOTOVINA</option>' ) );
        });
        $( "#uplata_od_kupca" ).click(function() {
          $( "#polja_svrha option" ).replaceWith( $( '<option value="KUPAC">KUPAC</option>' ) );
        });
      });
    </script>
    <title>Izvod</title>
  </head>
  <body>
    <div class="nosac_glavni_400">
      <?php
      require("../include/DbConnection.php");
      $idbank=$_POST['id_banke'];
      $datum=$_POST['datum'];

      $broj_izvoda=$_POST['broj_izvoda'];
      ?> 

      <form action="izvod3.php" method='post' id="izvod_forma">
        <label>Partner:</label>
        <select id="firma" name="partnersif" size="1" class="polje_100">
          <option value=''>Odaberi ... </option>
          <?php
            $upit = mysql_query("SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup");
            while($red = mysql_fetch_array($upit)) {
              $naziv_kup=$red['naziv_kup'];
              $sif_kup=$red['sif_kup'];?>
              <option value='<?php echo $sif_kup;?>'><?php echo $naziv_kup;?></option>
          <?php
          } ?>
        </select>
        <label>Broj dok:</label>
        <input type="text" name="broj_dok" class='polje_100_92plus4' id="broj_dokumenta"/>
        
        <p>Uplata-isplata:</p>
        
        <label>Uplata dobavljacu: </label>
        <input type="radio" name="uplata" value="uplata_dobavljacu" id="uplata_dobavljacu" checked>
        
        <label>Uplata od kupca: </label>
        <input type="radio" name="uplata" value="uplata_od_kupca" id="uplata_od_kupca">
        
        <label>Iznos: </label>
        <input type="text" value="" name="iznos_novca" class='polje_100_92plus4' id='iznosa'/>

        <label>Svrha: </label>
        <select name="svrha" class="polje_100" id="polja_svrha">
          <option value="DOBAVLJAC">DOBAVLJAC</option>
          <option value="KUPAC">KUPAC</option>
          <option value="USLUGE">USLUGE</option>
          <option value="GOTOVINA">GOTOVINA</option>
        </select> 

        <input type="hidden" name="id_banke" value="<?php echo $idbank; ?>"/>
        <input type="hidden" name="datum" value="<?php echo $datum; ?>"/>
        <input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda; ?>"/>
        <button type="submit" class="dugme_zeleno">Unesi</button>
      </form>
      <form action="izvod5.php" method="post">
        <input type="hidden" name="datum" value="<?php echo $datum;?>"/>
        <input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda;?>"/>
        <input type="hidden" name="id_banke" value="<?php echo $idbank;?>"/>
        <button type="submit" class="dugme_crveno">Ponisti</button>
      </form>
      <div class="cf"></div>
    </div>
  </body>
</html>