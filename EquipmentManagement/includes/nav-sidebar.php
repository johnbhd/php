<nav>
    <img src="../assets/img/aulogo.png" alt="">
    <div class="usernav">
        <?php
            
            if (isset($_SESSION['username'])) {
                echo "<h1>Welcome, ". $_SESSION['username'] . "</h1>";
                echo "<h1>Account Type: ". $_SESSION['usertype'] . "</h1>";
            } else {
                header('location: ../login.php');
            }
        ?>
    </div>

</nav>
<main>
    <aside>
        <a href="../userManagement/create-account.php">Create Account</a>
        <a href="../auth/logout.php">Logout</a>

    </aside>