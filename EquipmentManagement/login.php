<?php
require_once("./config/config.php");
session_start();

if (isset($_POST['btnsubmit'])) {
    $username = $_POST['txtusername'];
    $password = $_POST['txtpassword'];
    
    $sql = "SELECT username, usertype FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";
    
    $stmt = mysqli_prepare($link, $sql);
    
    if (!$stmt) {
        die("Prepare failed: ". mysqli_error($link));
    }
    
    mysqli_stmt_bind_param($stmt,   "ss", $username, $password);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $accounts = mysqli_fetch_assoc($result);
        
        $_SESSION["username"] = $accounts["username"];
        $_SESSION["usertype"] = $accounts["usertype"];
        $_SESSION["message"] = [
            'type'  => 'success',
            'text' => 'Login succesfully!'
        ];
    
        if ($accounts['usertype'] == 'ADMINISTRATOR' ) {
            header("Location: ./equipmentManagement/equipment-management.php");
            exit;
        } else if ($accounts['usertype'] == 'TECHNICAL') {
            header("Location: ./userManagement/accounts-management.php");
            exit;
        } else {
            $_SESSION["message"] = [
                'type' => 'error',
                'text' => 'Your usertype is USER cant login...'
            ];
            header("Location: ./login.php");
            exit;
        }
        
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Wrong username or password!'
        ];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Equipment Management System</title>
    <link rel="shortcut icon" href="./assets/img/aulogo.png" type="image/x-icon">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="./assets/css/notification.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['message']) && $_SESSION['message']['type'] == 'error'): 
            $type = $_SESSION['message']['type']; 
            $text = $_SESSION['message']['text'];
        ?>
        <div class="notification <?php echo $type; ?>">
            <div class="notification-body">
                <div class="noti-head">
                    <i class="fa-solid fa-xmark icon errors"></i>
                    <h4><?php echo ucfirst($type); ?></h4>
                </div>
                <div class="noti-body">
                    <p><?php echo $text; ?></p>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['message']); endif; ?>


        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
            <img src="./assets/img/aulogo.png" alt="">
            <h4>Login Page</h4>
            <label>Username: </label>
            <input type="text" name="txtusername"> <br>
            <label>Password: </label>
            <div class="passwordField">
                <input type="password" name="txtpassword" id="passwordInput"  required>
                <i class="fa-solid fa-eye icon" id="passwordToggle"></i>
            </div>
            <input type="submit" name="btnsubmit" value="Login" class="btn">
        </form>
    </div>
</body>
<script src="./assets/js/showPassword.js"></script>
</html>