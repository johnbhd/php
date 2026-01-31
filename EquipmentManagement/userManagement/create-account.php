<?php
require_once '../config/config.php';
include('../auth/session-checker.php');
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
                        $_SESSION['message'] = [
                            'type' => 'success',
                            'text' => 'User account added successfully!'
                        ];
                        
                        header("location: ./accounts-management.php");
                        exit();
                    } else {
                          $_SESSION['message'] = [
                                'type' => 'error',
                                'text' => 'Username already in use!'
                            ];
                            header("location: ./create-account.php");
                            exit();                    }
                } 
            } else {
                    $_SESSION['message'] = [
                        'type' => 'error',
                        'text' => 'Error checking username: ' . mysqli_stmt_error($stmt)
                    ];
                    header("location: create-account.php");
                    exit();            
            }
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error creating account: ' . mysqli_stmt_error($stmt)
            ];        
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
    <link rel="shortcut icon" href="aulogo.png" type="image/x-icon">
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
        <?php if (isset($_SESSION['message']) && $_SESSION['message']['type'] == 'error'): 
            $type = $_SESSION['message']['type']; 
            $text = $_SESSION['message']['text'];
        ?>
        <div class="notification <?php echo $type; ?>">
            <div class="notification-body">
                <div class="noti-head">
                    <i class="fa-solid fa-xmark icon"></i>
                    <h4><?php echo ucfirst($type); ?></h4>
                </div>
                <div class="noti-body">
                    <p><?php echo $text; ?></p>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['message']); endif; ?>


        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
            <img src="../assets/img/aulogo.png" alt="">

            <h4>Register Page</h4>

            <label>Username: </label>
            <input type="text" name="txtusername" required> 
            
            <label>Password: </label>
            <div class="passwordField">
                <input type="password" name="txtpassword" id="passwordInput"  required>
                <i class="fa-solid fa-eye icon" id="passwordToggle"></i>
            </div>

            <label>Account type: </label> 
            <select type="text" name="cmbtype" id="cmbtype" required> 
                <option value ="">--Select Account type-</option>
                <option value ="ADMINISTRATOR">ADMINISTRATOR</option>
                <option value ="USER">USER</option>
                <option value ="TECHNICAL">TECHNICAL</option>
            </select>
            <br>
            <input type="submit" name="btnsubmit" value="Submit" class="btn">
            <a href="accounts-management.php">Cancel</a>
        </form>
    </div>
</body>
<script src="../assets/js/showPassword.js"></script>
</html>

