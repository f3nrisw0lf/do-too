<?php

$pdo = new PDO('mysql:host=localhost;dbname=do_too', 'root');

if (mysqli_connect_errno()) {
  echo "MYSQL CONNECTION ERROR: " . mysqli_connect_errno();
}