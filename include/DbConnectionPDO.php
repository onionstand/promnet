<?php

$dsn = 'mysql:host=localhost;dbname=promnet-2017';
$user = 'root';
$password = '';

try {
    $baza_pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Povezivanje sa bazom je neuspesno: ' . $e->getMessage();
}
?>