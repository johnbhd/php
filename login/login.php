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
                
                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'Login successful!'
                ];
                header('location: accounts-management.php');
                exit;
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => 'Wrong username or password!'
                ];
                $error = "Wrong username or password.";
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
    <link rel="shortcut icon" href="aulogo.png" type="image/x-icon">
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
        background-color: #111827;

}
.container {
    background-color: white;
    border-radius: 20px;
    width: 100%;
    max-width: 400px;
    padding: 40px 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

form {
    display: flex;
    flex-direction: column;
    gap: 5px;

}

h4 {
    text-align: center;
    font-size: 1.5rem;
    margin-bottom: 15px;
    padding-top: 10px;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

.btn {
    margin-top: 15px;
    background-color: #2563EB;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1.1rem;
}
.btn:hover {
    background-color: #1D4ED8;
}
.passwordField {
    width: 100%;
    display: flex;
    align-items: center;
    border: 1px solid #CBD5E1; 
    border-radius: 8px;
    padding: 0 10px;
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

/* notification for all*/
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


@keyframes fadeOut {
    to { opacity: 0; }
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