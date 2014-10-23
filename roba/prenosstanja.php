<?php
require('../../include/DbConnection.php');
mysql_query('DROP TABLE prenos_stan');
IF (mysql_query('
		CREATE TABLE prenos_stan
		(
		naziv_robe varchar(64),
		cena_robe decimal(12,2),
		porez decimal(10,0),
		jed_mere tinytext,
		ruc decimal(10,0),
		kolicina int(11)
		)ENGINE=MyISAM DEFAULT CHARSET=utf8'))
  {
  echo '<!DOCTYPE html>
		<head>
		<link rel=stylesheet type=text/css href=../../include/css/stil.css>
		<title>Prenos Robnih Razlika</title>
		</head>
		<body>
		<div id=formpoz2>
		<p>Kreirano</p>
		<a class=button_kuci href=../index.php>Pocetna strana</a>
		</div>
		</body>';
  }
ELSE
  {
	echo 'Greska pri kreiranju tabele: ' . mysql_error();
  }
  mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('FAIRY ZA SUDOVE 1L', '171.00', '18', 'KOM', '28', '10')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('GLADE MIKROSPREJ REFIL', '249.00', '18', 'KOM', '22', '24')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('KAFA 200G GRAND AROMA', '175.00', '18', 'KOM', '20', '10')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('MLEKO 1L', '93.50', '8', 'KOM', '25', '120')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('NESCAFE CLASSIC 250G', '480.00', '18', 'KOM', '30', '12')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('PROLOM VODA 1.5L', '45.00', '18', 'KOM', '28', '120')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('SOK BRESKVA 1L 100% NEKTAR', '110.00', '18', 'KOM', '24', '116')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('SOK NARANDZA 1L 100% NEXT', '121.00', '18', 'KOM', '20', '144')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('VINO CHANTI 0.75', '434.33', '18', 'KOM', '1', '1')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('VODA K.MILOS 0.5L', '30.00', '18', 'KOM', '25', '24')");
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina)
		VALUES ('VODA K.MILOS 1.5L', '39.00', '18', 'KOM', '25', '120')");
		?>