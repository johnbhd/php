<?php
require_once("../../config/config.php");
$ticket_number = $_GET["ticket_number"];

$sql = "SELECT * from tbltickets WHERE ticket_number = ?";
$stmt = mysqli_prepare($link, $sql);

mysqli_stmt_bind_param($stmt, "s", $ticket_number);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");
echo json_encode($row);
?>