<?php
session_start();

$pdo = new PDO ("mysql:host=localhost;dbname= trabalho_php",
"root", "",[
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);


  

?>