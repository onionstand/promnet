<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Faktura</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php"); 
			$brojfak=$_POST['broj_fak'];

			mysql_query("DELETE FROM profakrob WHERE br_profak='$brojfak'");
			mysql_query("DELETE FROM profak WHERE broj_prof='$brojfak'");
			echo "<h2>Izbrisano.</h2>";
			?>
			<form action="../index.php" method="post">
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>