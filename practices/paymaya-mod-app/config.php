<?php

  $host = "192.168.1.146";
  $user = "admin";
  $password = "admin123";
  $paymaya = "paymaya_mod_app";
  
  $conn = mysqli_connect($host, $user, $password, $db);
  
  if (!$conn) {
    echo "db not connected";
  } else {
    echo "db connected";
  }
?>