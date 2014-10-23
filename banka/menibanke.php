
<?php 
require("include/DbConnection.php");
$result = mysql_query("SELECT * FROM banke");
while($row = mysql_fetch_array($result))
	{
	$bank=$row['ime_banke'];
	$bank_id=$row['id_banke'];
	echo "<li><form action='banka/izvod1.php' method='post'><button type='submit' class='menibank' name='id_banke' value='" . $bank_id . "'>NOVI IZVOD " . $bank . "</button></form></li>";
	echo "<li><form action='banka/stari_iz.php' method='post'><button type='submit' class='menibank' name='id_banke' value='" . $bank_id . "'>IZVODI " . $bank . "</button></form></li>";
	}
?>