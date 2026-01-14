<?php
if (isset($_POST['btnsubmit'])) {
    require_once("config.php");
    $sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $accounts = mysqli_fetch_array($result, MYSQLI_ASSOC);
                session_start();

                $_SESSION['username'] = $accounts['username'];
                $_SESSION['usertype'] = $accounts['usertype'];
                

                header('location: accounts-management.php');
            } else {
                echo "<p style = 'background-color: red;  margin-bottom: 10px; padding: 5px 20px; border-radius: 20px; color: white; text-align: center'>Invalid username or password</p>";
            }
        } else {
            echo "<p style='background-color: red; margin-bottom: 10px;  padding: 5px 20px; border-radius: 20px; color: white; text-align: center'>Execute Error: " . mysqli_stmt_error($stmt) . "</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Technical Management System</title>
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
    input {
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
            <h4>Login Page</h4>
            Username: <input type="text" name="txtusername"> <br>
            Password: <input type="text" name="txtpassword"> <br>
            <input type="submit" name="btnsubmit" value="Login" class="btn">
        </form>
    </div>
</body>
</html>