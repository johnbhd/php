<?php
require_once "../config/config.php";
include('../auth/session-checker.php');

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
                          $_SESSION['message'] = [
                            'type' => 'success',
                            'text' => 'User account successfully deleted!'
                        ];
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
    <link rel="stylesheet" href="../assets/css/delete-account.css">
    <link rel="stylesheet" href="../assets/css/notification.css">
    <link rel="stylesheet" href="../assets/css/nav-sidebar.css">
    <link rel="shortcut icon" href="../assets/img/aulogo.png" type="image/x-icon">
</head>
<body>

    <?php include("../includes/nav-sidebar.php");?>
        <div class="container"></div>
        <div class="popup-overlay" > 
            <div class="popup-field">
        <div>
            <h4>Delete Account</h4>
        </div>
        <div>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="txtusername" value="<?php echo htmlspecialchars($_GET['username']); ?>">
                <p>Are you sure you want to delete this account name <strong><?php echo htmlspecialchars($_GET['username']); ?></strong>?</p>
                <div class="btnModal">
                    <input type="submit" name="btnsubmit" value="YES" id="yesBtn">
                    <a href="./accounts-management.php" id="noBtn">NO</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>