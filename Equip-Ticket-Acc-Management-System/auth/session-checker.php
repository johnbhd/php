<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("location: ../index.php");
    }

    function checkRoles($allowedRole) {
        if (!in_array($_SESSION['usertype'], $allowedRole)) {
            header("location: ../equipmentManagement/equipment-management.php");
        }
    }
?>