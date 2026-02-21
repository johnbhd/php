<?php

  $host = "192.168.1.146";
  $user = "admin";
  $password = "admin123";
  $db = "aya_app";
  
  $conn = mysqli_connect($host, $user, $password, $db);
  
  if (!$conn) {
    die("db not connected");
  }
?>