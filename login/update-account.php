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
    font-family: Arial, sans-serif; 
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
    background-color: #111827;
}

.container {
    background-color: #FFFFFF;
    border-radius: 20px;
    width: 100%;
    max-width: 500px;
    padding: 40px 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 10px;
}

h4 {
    text-align: center;
    font-size: 1.5rem;
    padding-bottom: 10px;
}

input,
select {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #CBD5E1;
    font-size: 1rem;
    outline: none;
}

.btn {
    background-color: #2563EB;  
    color: white;
    border: none;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1.1rem;
    margin-top: 15px;    
}

.btn:hover {
    background-color: #1D4ED8; 
}

form a { 
    text-decoration: none;
    color: #2563EB;
    margin-top: 5px;
    text-align: center;
}

.passwordField {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 0 12px;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    background-color: #FFFFFF;
}

.passwordField input {
    flex: 1;
    border: none;
    outline: none;
    padding: 10px 0;
    font-size: 1rem;
}

.passwordField .icon {
    color: #64748B; 
    cursor: pointer;
    font-size: 1.1rem;
}

.passwordField .icon:hover {
    color: #2563EB; 
}
.head-con {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    text-align: center;
}
img {
    width: 100px;
    height: 100px;
    border: 1px solid black;
    border-radius: 100%;
    margin: auto;
}
label {
    font-size: 1.1rem;
}

@media (max-width: 480px) {
    .container {
        padding: 30px 20px;
        border-radius: 15px;
    }

    h4 {
        font-size: 1.3rem;
    }

    .btn {
        font-size: 1rem;
    }
}

</style>
<body>
    <div class="container">
        <div class="head-con">
            <img src="aulogo.png" alt="">
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