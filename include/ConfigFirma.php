
<?php
//Ukucati podesavanja

//podaci
$inkfirma="
<img alt='LOGO' class='memorandum_logo' src='../include/images/logo.png'>
<p class='memorandum_tekst'>
	<b>TEST <small>DOO</small></b> | Pere Perica 22 | 11000 Beograd<br />
	<small>Tel:</small> 011/111-111 | 011/111-111<br />
	test@test.com | www.test.com<br />
	<small>PIB:</small> 999999999 | <small>MB:</small> 99999999 | <small>TR:</small> <b>999-9999-99</b>
</p>";
//mesto izdavanja racuna
$inkfirma_mir="Beograd";

$inkfirmamin="<b>Test d.o.o.</b>";
$trengodina="2017";


if (isset($brojfak)){$inkfaktekst="Napomena o poreskom oslobođenju: NEMA.

<br>
";
				
	if (isset($rokpl)){
		if ($rokpl>0){
			$inkfaktekst.="Plaćanje u roku od ". $rokpl ." dana.<br>
			";
		}
		else{
			$inkfaktekst.="Plaćanje avansno.<br>";
		}
	}

$inkfaktekst.="Uplatu izvršiti na račun 99-9999999-99 sa pozivom na broj <b>".$racun_rucni."/".$trengodina."</b><br><br><br>";
		
	//$inkfaktekst.="Reklamacije prihvatamo u roku od 8 dana.";
}
?>