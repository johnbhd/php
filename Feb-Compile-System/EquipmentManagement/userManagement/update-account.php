<?php
require_once "../config/config.php";
include("../auth/session-checker.php");
if (isset($_POST['btnsubmit'])) {
    $sql = "UPDATE tblaccounts SET password = ?, usertype = ?, status = ? WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);
            if (mysqli_stmt_execute($stmt)) {
                $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?)";
                if($stmt = mysqli_prepare($link, $sql)) {
                    $date = date("d/m/Y");
                    $time = date("h:i:sa");
                    $action = "Update account";
                    $module = "Accounts Management";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_SESSION['username'], $_GET['username']);
                    if (mysqli_stmt_execute($stmt)) {
                          $_SESSION['message'] = [
                                'type' => 'success',
                                'text' => 'User account updated successfully!'
                            ];
                        header("location: ../userManagement/accounts-management.php");
                        exit();
                    } else {
                        echo "<font color = 'red'>Error on inserting log.</font>";
                    }
                }
            } else {
                echo "<font color = 'red'>Error on updating account.</font>";
            }
        }

} else {
    if (isset($_GET['username']) && !empty(trim($_GET['username']))) {
        $sql = "SELECT * FROM tblaccounts WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $accounts = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                echo "<font color = 'red'> Error on loading accounts details. </font>";
            }
        }
    } else {
        header("location: accounts-management.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Page - Equipment Management System</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="shortcut icon" href="../assets/img/aulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/update-account.css">
    <link rel="stylesheet" href="../assets/css/notification.css">
</head>
<body>
    <div class="container">
        <div class="head-con">
            <img src="../assets/img/aulogo.png" alt="">
            <h4>Update User Accounts</h4>
            <p>Change the value on this form and submit to update the account...</p>
        </div>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI']));?>" method="post">
            <label>Username: </label>
            <input type="text" value="<?php echo $accounts['username']; ?> " disabled>

            <label>Password: </label>
            <div class="passwordField">
                <input type="password" name="txtpassword" id="passwordInput" value="<?php echo $accounts['password'];?>" required>
                <i class="fa-solid fa-eye icon" id="passwordToggle"></i>
            </div>
            
            <label>Current Account type:</label> 
            <input type="text" disabled value="<?php echo $accounts['usertype'];?>">
         
            <label>Change account type to: </label>
            <select name="cmbtype" id="cmbtype" required>
                <option value ="">--Select Account type-</option>
                <option value ="ADMINISTRATOR">ADMINISTRATOR</option>
                <option value ="USER">USER</option>
                <option value ="TECHNICAL">TECHNICAL</option>
            </select>

            <div class="status">
                <label>Status: </label>
                <?php 
                    $status = $accounts['status'];
                    if ($status == "ACTIVE") {
                    ?> 
                    <input type="radio" name="rbstatus" value="ACTIVE" checked>Active 
                    <input type="radio" name="rbstatus" value="INACTIVE">Inactive <?php
                    } else {
                        ?> 
                    <input type="radio" name="rbstatus" value="ACTIVE">Active <br>
                    <input type="radio" name="rbstatus" value="INACTIVE" checked>Inactive <br> <?php
                    }
                ?>
            </div>
            <input type="submit" class="btn" value="Submit" name="btnsubmit">
            <a href="../userManagement/accounts-management.php">Cancel</a>
        </form>
    </div>
    
</body>
<script src="../assets/js/showPassword.js"></script>
</html>