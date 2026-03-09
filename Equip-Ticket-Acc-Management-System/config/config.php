<?php

define('DB_SERVER', "192.168.1.146");
define('DB_USERNAME', "admin");
define('DB_PASSWORD', "admin123");
define('DB_NAME', "itc127_it2a_2026");

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($link === false) {
    die("ERROR: could not connect, " . mysqli_connect_error());
}
date_default_timezone_set('Asia/Manila');

?>