<?php

define('DB_SERVER', '192.168.43.179:8080');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'itc127-it2a-2026');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($link === false) {
    die("ERROR: could not connect, " . mysqli_connect_error());
}
date_default_timezone_set('Asia/Manila');

?>