<?php
require_once "config.php";
include('session-checker.php');

if (isset($_POST['btnsubmit'])) {
    $sql = "DELETE FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", trim($_POST["txtusername"]));

        if (mysqli_stmt_execute($stmt)) {
            $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?)";
                if($stmt = mysqli_prepare($link, $sql)) {
                    $date = date("d/m/Y");
                    $time = date("h:i:sa");
                    $action = "Delete account";
                    $module = "Accounts Management";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_SESSION['username'], $_POST['txtusername']);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "User account deleted";
                        header("location: accounts-management.php");
                        exit();
                    } else {
                        echo "<font color = 'red'>Error on inserting log.</font>";
                    }
                }
        }  else {
            echo "<font color = 'red'>Error on deleting accounts.</font>";
        }
    }

    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Accounts Page - Technical Management System</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
        <input type="hidden" name="txtusername" value="<?php echo trim($_GET['username'])?>">
        <p>Are you sure want to delete this account</p>
        <input type="submit" name="btnsubmit" value="YES">
        <a href="accounts-management.php">NO</a>
    </form>
</body>
</html>