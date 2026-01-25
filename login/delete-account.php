<?php
require_once "config.php";
include('session-checker.php');

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
                        echo "User account deleted";
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
</head>
<body>
<style>
        * {
        font-family: Arial, Helvetica, sans-serif;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    body {
        background-color: lightblue;
    }
    nav {
        display: flex;
        align-items: center;
        color: white;
        font-size: 1rem;
        gap: 20px;
        background-color: blue;
        padding: 20px;
    }
    nav img {
        width: 70px;
        height: 70px;
    }
    .usernav {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }
    aside {
        display: flex;
        flex-direction: column;
        padding: 20px 20px;
        gap: 20px;
        background-color: red;
    }
    aside a {
        text-decoration: none;
        color: white;
        cursor: pointer;
        font-size: 1.3rem;
    }
    aside a:hover {
        opacity: 80%;
    }
    main {
        display: flex;
        height: 100vh;
      
    }
    .container {
        padding: 20px 30px;
        background-color: white;
        width: 100%;
    }
    .popup-overlay {
        display: flex;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 999;
    }
    h4 {
        font-size: 20px;
        padding-bottom: 10px;
    }
    .popup-field {
        background-color: white;
        padding: 30px 20px;
        border-radius: 5px;
    }
    form a {
        text-decoration: none;
        color: black;
    }
    .btnModal {
        display: flex;
        justify-content: center;
        gap: 4vh;
        padding-top: 20px;
    }
    #noBtn, #yesBtn {
        padding: 5px 10px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }
    #yesBtn {
        background-color: green;
        border: none;
    }
    #noBtn {
        background-color: red;
    }
</style>
 <nav>
        <img src="aulogo.png" alt="">
        <div class="usernav">
            <?php
                if (isset($_SESSION['username'])) {
                    echo "<h1>Welcome, ". $_SESSION['username'] . "</h1>";
                    echo "<h1>Account Type: ". $_SESSION['usertype'] . "</h1>";
                } else {
                    header('location: login.php');
                }
            ?>
        </div>

    </nav>
    <main>
        <aside>
            <a href="create-account.php">Create Account</a>
            <a href="logout.php">Logout</a>

        </aside>
        <div class="container"></div>
<div class="popup-overlay" > <!-- Show modal immediately -->
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
                    <a href="accounts-management.php" id="noBtn">NO</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>