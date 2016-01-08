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
			$id_rob=$_POST['id_rob'];

			echo "<p>Roba je izbrisana.</p>";

			mysql_query("DELETE FROM profakrob WHERE id_rob='$id_rob' AND br_profak='$brojfak'");
			?>
			<form action="profak5.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak; ?>"/>
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>