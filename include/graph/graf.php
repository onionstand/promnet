
<?php 
require("include/DbConnection.php");

function MesecnaRazlikaUCeni($mesec){
	$upit = mysql_query("SELECT SUM(bruc) AS ukupruc FROM dosta WHERE month(datum_d) ='".$mesec."'");
	$iznoskauk=(mysql_fetch_array($upit));
	echo $iznoskauk['ukupruc']+0;
}

function KupacMeseca($mesec){
	$najkup = mysql_query("SELECT dosta.sifra_fir, SUM(dosta.bruc) AS maxbruc, dob_kup.naziv_kup
							FROM dosta RIGHT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
							WHERE month(datum_d) ='".$mesec."' GROUP BY sifra_fir ORDER BY maxbruc DESC");
	$kupmax = mysql_fetch_array($najkup);
	echo $kupmax['naziv_kup'];
}



function MesecnaProdaja($mesec){
	$upit_mes_pro = mysql_query("SELECT SUM(izzad) AS sumizzad FROM dosta WHERE month(datum_d) ='".$mesec."'");
	$iznos_mes_pro=(mysql_fetch_array($upit_mes_pro));
	echo $iznos_mes_pro['sumizzad']+0;

}

function MesecnaNabavka($mesec){
	$upit_mes_nab = mysql_query("SELECT SUM(nabav_vre) AS sum_nabav_vre FROM kalk WHERE month(datum) ='".$mesec."'");
	$iznos_mes_nab=(mysql_fetch_array($upit_mes_nab));
	echo $iznos_mes_nab['sum_nabav_vre']+0;
	//echo number_format(($iznos_mes_nab['sum_nabav_vre']+0), 2,"."," ");

}
?>