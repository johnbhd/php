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
                        $_SESSION['message'] = [
                            'type' => 'success',
                            'text' => 'User account added successfully!'
                        ];
                        
                        header("location: accounts-management.php");
                        exit();
                    } else {
                          $_SESSION['message'] = [
                                'type' => 'error',
                                'text' => 'Username already in use!'
                            ];
                            header("location: create-account.php");
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
    gap: 15px;
}

h4 {
    text-align: center;
    font-size: 1.5rem;
    padding-bottom: 10px;
}

input,
select {
    padding: 10px;
    outline: none;
    border-radius: 8px;
    border: 1px solid #CBD5E1;
    font-size: 1rem;
}

.btn {
    background-color: #2563EB;  
    color: white;
    border: none;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1.1rem;
}

.btn:hover {
    background-color: #1D4ED8; 
}

form a { 
    text-decoration: none;
    color: #2563EB;
    margin-top: 10px;
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
.notification {
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #FFFFFF; 
    color: white;
    padding: 12px 18px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    z-index: 1000;
    font-family: Arial, sans-serif;
    width: 100%;
    max-width: 300px;
        animation: slideIn 0.5s ease, fadeOut 0.5s ease 3s forwards;
}

.notification-body {
    display: flex;
    flex-direction: column;
}

.noti-head {
    display: flex;
    align-items: center;

}

.icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background-color: #e2e6e3; 
    color: white;
    border-radius: 50%;
    color: #f87171;
    margin-right: 10px;
    font-size: 14px;
}

.noti-body {
    padding-left: 34px; 
}
.notification.success {
    background-color: #16a34a; 
}

.notification.error {
    background-color: #f87171;
}
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
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
            <img src="aulogo.png" alt="">

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

