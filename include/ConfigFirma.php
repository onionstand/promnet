
<?php
//Unesi svoje podatke

//podaci
$inkfirma="
<img alt='Test' class='memorandum_logo' src='../include/images/logo.png'>
<p class='memorandum_tekst'>
	<b>TEST <small>d.o.o.</small></b> | 11000 Beograd | Pere Perića 22 | <small>Telefon:</small> 011/316-03-91<br />
	<small>E-mail:</small> test@test.com | <small>Web adress:</small> www.test.com<br />
	<small>PIB:</small> 999999999 | <small>MB:</small> 99999999 | <small>Žiro račun:</small> <b>999-9999-99</b>
</p>";
//mesto izdavanja racuna
$inkfirma_mir="Beograd";

$inkfirmamin="<b>TEST d.o.o.</b>";


if (isset($rokpl))
	{
		if ($rokpl>0){
			$inkfaktekst="Napomena o poreskom oslobađanju: #.<br>
			Plaćanje u roku od ". $rokpl ." dana.<br>
			Kamatna stopa predviđena zakonom. U slučaju spora nadležan sud u Beogradu.<br>
			Reklamacije prihvatamo u roku od 8 dana.";
		}
		else{

			$inkfaktekst="Napomena o poreskom oslobađanju: #.<br>
			Plaćanje avansno.<br>
			Kamatna stopa predviđena zakonom. U slučaju spora nadležan sud u Beogradu.<br>
			Reklamacije prihvatamo u roku od 8 dana.";
		}
	}
?>