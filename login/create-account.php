<?php
require_once 'config.php';
include('session-checker.php');
if (isset($_POST['btnsubmit'])) {
    $sql = "SELECT * FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO tblaccounts (username, password, usertype, status, createdby, datecreated) VALUE (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $status = "ACTIVE";
                    $date = date("d/m/y");
                    mysqli_stmt_bind_param($stmt,  "ssssss", $_POST['txtusername'], $_POST['txtpassword'], $_POST['cmbtype'], $status, $_SESSION['username'], $date);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "User account added.";
                        header("location: accounts-management.php");
                        exit();
                    } else {
                        echo "<font color = 'red'>Username already in use</font>";
                    }
                } 
            } else {
                echo "<font color = 'red'>Username already in use</font>";
            }
        } else {
                echo "<font color = 'red'>Error username validate</font>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page - Technical Management System</title>
</head>
<style>
    * {
        font-family: 'arial'; 
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-image: url('aubg.jpg');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;

    }
    .container {
        padding: 50px 30px;
        background-color: white;
        border-radius: 20px;
        width: 50vh;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    h4 {
        text-align: center;
        font-size: 1.5rem;
        padding-bottom: 10px;
    }
    input, select {
        padding: 5px 0px;
    }
    .btn {
        background-color: #2563EB;  
        color: white;
        border: none;
        padding: 5px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 1.2rem;
    }

    .btn:hover {
        background-color: #1D4ED8; 
    }
</style>
<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
            <h4>Register Page</h4>
            Username: <input type="text" name="txtusername" required> <br>
            Password: <input type="text" name="txtpassword" required> <br>
            Account type: <select type="text" name="cmbtype" id="cmbtype" required> 
                <option value ="">--Select Account type-</option>
                <option value ="ADMINISTRATOR">ADMINISTRATOR</option>
                <option value ="USER">USER</option>
                <option value ="TECHNICAL">TECHNICAL</option>
            </select>
            <br>
            <input type="submit" name="btnsubmit" value="Login" class="btn">
            <a href="accounts-management.php">Cancel</a>
        </form>
    </div>
</body>
</html>

