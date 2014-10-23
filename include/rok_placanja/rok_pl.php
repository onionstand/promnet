<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="../include/css/stil.css">
<title>Analiticka kartica</title>
</head>
<body>
<div id="formpoz2">
<?php require("../include/DbConnection.php");require("../include/ConfigFirma.php");$sif_kup=$_POST['partnersif'];

$upit3 = mysql_query("SELECT * FROM dob_kup WHERE sif_kup='$sif_kup' ");
$niz3= mysql_fetch_array($upit3);
echo "<p class='karticanaslov'><b>Analiticka kartica</b></p>
<div class='karticazag1'>
<div class='karticazaglev'>".$inkfirma."</div>
<div class='karticazagdes'>Partner: " . $niz3['naziv_kup'] . "<br/>Sifra: " . $sif_kup . "<br/>";
echo $niz3['mesto_kup'] . "<br/>";
echo $niz3['ulica_kup'] . "<br/></div>
</div>";
?>
<div class="karticatabela"><table id='tabele'>
<tr>
<th>Broj dokumenta</th>
<th>Vrsta</th>
<th>Datum</th>
<th>Duguje</th>
<th>Potrazuje</th>
<th>Saldo</th>
</tr>
<?php 

$upit=mysql_query(
"SELECT bankaupis.br_izvoda AS a1, banke.ime_banke AS a2, bankaupis.datum_izv AS a3, bankaupis.izlaz_novca AS a4, bankaupis.ulaz_novca AS a5 
FROM bankaupis
RIGHT JOIN banke ON bankaupis.banka=banke.id_banke 
WHERE sifra_par ='$sif_kup'
UNION ALL
SELECT broj_dost AS a1,'DOS' AS a2,datum_d AS a3,izzad AS a4, 0 AS a5 
FROM dosta 
WHERE sifra_fir='$sif_kup'
UNION ALL
SELECT broj_kalk AS a1,'KAL' AS a2,datum AS a3,0 AS a4, nabav_vre AS a5 
FROM kalk 
WHERE sif_firme='$sif_kup'
UNION ALL
SELECT br_usluge AS a1,'USL' AS a2,datum AS a3,0 AS a4, iznosus AS a5 
FROM usluge 
WHERE sifusluge='$sif_kup'
UNION ALL
SELECT broj_k AS a1,'PIS K' AS a2,dat_k AS a3,iznos_k AS a4, 0 AS a5 
FROM k_pism_r 
WHERE sif_firme='$sif_kup' AND kod_p=1
UNION ALL
SELECT broj_k AS a1,'PIS F' AS a2,dat_k AS a3,0 AS a4, iznos_k AS a5 
FROM k_pism_r 
WHERE sif_firme='$sif_kup' AND kod_p=2
ORDER BY a3 
");

while($niz = mysql_fetch_array($upit))
{
$dat=$niz['a3'];

$upit2 = mysql_query("
SELECT SUM(izlaz_novca) AS b1,SUM(ulaz_novca) AS c1 
FROM bankaupis 
WHERE sifra_par ='$sif_kup' AND datum_izv <= '$dat'");
$upit3 = mysql_query("
SELECT SUM(izzad) AS b2
FROM dosta
WHERE sifra_fir ='$sif_kup' AND datum_d <= '$dat'");
$upit4 = mysql_query("
SELECT SUM(iznos_f) AS b3, SUM(iznos_k) AS c3
FROM k_pism_r
WHERE sif_firme='$sif_kup' AND dat_k <= '$dat'");
$upit5 = mysql_query("
SELECT SUM(nabav_vre) AS c2
FROM kalk
WHERE sif_firme='$sif_kup' AND datum <= '$dat'");
$upit6 = mysql_query("
SELECT SUM(iznosus) AS c4
FROM usluge
WHERE sifusluge='$sif_kup' AND datum <= '$dat'

");
$niz2 = mysql_fetch_array($upit2);
$niz3 = mysql_fetch_array($upit3);
$niz4 = mysql_fetch_array($upit4);
$niz5 = mysql_fetch_array($upit5);
$niz6 = mysql_fetch_array($upit6);
$b1=$niz2['b1'];
$b2=$niz3['b2'];
$b3=$niz4['b3'];
$c1=$niz2['c1'];
$c2=$niz5['c2'];
$c3=$niz4['c3'];
$c4=$niz6['c4'];
$saldo=($b1+$b2+$b3)-($c1+$c2+$c3+$c4);



	echo "<tr>";
	echo "<td>" . $niz['a1'] . "</td>";
	echo "<td>" . $niz['a2'] . "</td>";
	echo "<td>" . $niz['a3'] . "</td>";
	echo "<td>" . $niz['a4'] . "</td>";
	echo "<td>" . $niz['a5'] . "</td>";
	echo "<td>" . $saldo . "</td>";
	echo "</tr>";
	}
echo "</table></div>"; 
?>


<div class="cf"></div>
<a href="kartica0.php" class="button_nazad">Nazad</a>
<a href="../index.php" class="button_kuci">Pocetna strana</a>
<button class="button_print" onClick='window.print()' type='button'>Stampa</button>
<div class="cf"></div>
</div>
</body>