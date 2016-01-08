<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Prenos Robnih Razlika</title>
</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		IF (isset($_FILES["file"]["name"]))
		{	
			IF ($_FILES["file"]["size"] < 20000)
			{
				IF ($_FILES["file"]["error"] > 0)
				{
					echo "<p>Greska: " . $_FILES["file"]["error"] . "</p>";
				}
				ELSE
				{
					echo "<p>Upload zavrsen.</p>";

					move_uploaded_file($_FILES["file"]["tmp_name"],
					"prenos_stanja_sinh/" . $_FILES["file"]["name"]);
					echo "<p>Snimljen u: " . "prenos_stanja_sinh/" . $_FILES["file"]["name"] . "</p>
					<a class='button_unesi' href='prenos_stanja_sinh/prenosstanja.php'>Dalje</a>";
					
				}
			}
			ELSE
			{
				echo "<p>Neispravan fajl.</p>";
			}
		}
		ELSE
		{
		?> 

			<form action="" method="post" enctype="multipart/form-data">
				<p>Uploaduj bazu za prenos.</p>
				<div class="cf"></div>
				<input type="file" name="file" id="file" class="polje_100_92plus4" />
				<div class="cf"></div>
				<button type="submit" class="dugme_zeleno">Unesi</button>
				<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
			</form>
		<?php 
		} ?>

	</div>
</body>
<html>