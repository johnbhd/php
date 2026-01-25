<?php
require_once "config.php";
include("session-checker.php");
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
                        echo "User account updated";
                        header("location: accounts-management.php");
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
    <title>Update Accounts Page - Technical Management System</title>
      <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        />
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
        width: 60vh;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
    }
    h4 {
        text-align: center;
        font-size: 1.5rem;
        padding-bottom: 10px;
    }
    input, select {
        padding: 5px 5px;
    }
    .btn {
        background-color: #2563EB;  
        color: white;
        border: none;
        padding: 5px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 1.2rem;
        margin-top: 15px;    
    }

    .btn:hover {
        background-color: #1D4ED8; 
    }
    form a { 
        text-decoration: none;
        color: black;
        margin-top: 5px;
    }

    .passwordField {
        width: 100%;
        border: 1px solid black;
    }
    .passwordField input {
        border: none;
        outline: none;
    }
    .passwordField .icon {
        position: relative;
        float: right;
        padding: 5px 10px;
        cursor: pointer;
    }
</style>
<body>
    <div class="container">
        <div>
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
            <a href="accounts-management.php">Cancel</a>
        </form>
    </div>
    
</body>
<script>
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordInput = document.getElementById('passwordInput');

    passwordToggle.addEventListener('click', () => {
        const isPassword = passwordInput.type === "password"
        
        passwordInput.type = isPassword ? "text" : "password";

        
        passwordToggle.classList.toggle("fa-eye", !isPassword);
        passwordToggle.classList.toggle("fa-eye-slash", isPassword);


    })
</script>
</html>