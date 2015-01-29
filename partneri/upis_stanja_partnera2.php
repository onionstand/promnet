<?php
require("../include/DbConnectionPDO.php");
$id=$_POST["id"];
$stanje=$_POST["stanje"];
$kupci=$_POST["kupci"];
$dobavljaci=$_POST["dobavljaci"];

$upit = 'UPDATE dob_kup SET stanje = ?, kupci = ?, dobavljaci = ?
	  WHERE sif_kup = ?';
$stmt = $baza_pdo->prepare($upit);
$stmt->execute(array($stanje, $kupci, $dobavljaci, $id));
$uradjeno = $stmt->rowCount();
?>