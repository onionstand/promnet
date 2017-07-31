
<?php
require("include/DbConnectionPDO.php");

function MesecnaRazlikaUCeni(){
	global $baza_pdo;
	$upit = "SELECT SUM(bruc) AS ukupruc FROM dosta GROUP BY YEAR(datum_d), MONTH(datum_d)";
	 foreach ($baza_pdo->query($upit) as $red) { ?>
		<td><?php echo $red["ukupruc"];?></td>
		<?php
	}
}


function KupacMeseca($mesec){
	global $baza_pdo;
	$najkup = "SELECT dosta.sifra_fir, SUM(dosta.bruc) AS maxbruc, dob_kup.naziv_kup
				FROM dosta RIGHT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
				WHERE month(datum_d) ='".$mesec."' GROUP BY sifra_fir ORDER BY maxbruc DESC";
	
	$result_najkup = $baza_pdo->query($najkup);
	$kupmax = $result_najkup->fetch();
	echo $kupmax['naziv_kup'];
}



function MesecnaNabavka(){
	global $baza_pdo;
	$upit_mes_nab = "SELECT SUM(nabav_vre) AS sum_nabav_vre FROM kalk GROUP BY YEAR(datum), MONTH(datum)";
	 foreach ($baza_pdo->query($upit_mes_nab) as $iznos_mes_nab) { ?>
		<td><?php echo $iznos_mes_nab["sum_nabav_vre"];?></td>
		<?php
	}
}

function MesecnaProdaja(){
	global $baza_pdo;
	$upit_mes_pro = "SELECT SUM(izzad) AS sumizzad FROM dosta GROUP BY YEAR(datum_d), MONTH(datum_d)";
	 foreach ($baza_pdo->query($upit_mes_pro) as $iznos_mes_pro) { ?>
		<td><?php echo $iznos_mes_pro["sumizzad"];?></td>
		<?php
	}
}


function MesecnaRazlika(){
	global $baza_pdo;
	$upit_mes_pro = "SELECT SUM(izzad) AS sumizzad FROM dosta GROUP BY YEAR(datum_d), MONTH(datum_d) ";
	$prodaja=array();
	foreach ($baza_pdo->query($upit_mes_pro) as $iznos_mes_pro) { 
		$prodaja[] = $iznos_mes_pro["sumizzad"];
	}

	$upit_mes_nab = "SELECT SUM(nabav_vre) AS sum_nabav_vre FROM kalk GROUP BY YEAR(datum), MONTH(datum) ";
	$nabavka=array();
	foreach ($baza_pdo->query($upit_mes_nab) as $iznos_mes_nab) { 
		$nabavka[] = $iznos_mes_nab["sum_nabav_vre"];
	}


	foreach( $prodaja as $id => $prodaj ) {
		if (isset($nabavka[$id])) {
			$razlika=$prodaj-$nabavka[$id];
			echo "<td>".$razlika."</td>";
		}
		else{
			echo "<td>".$prodaj."</td>";
		}
	}
}
?>
