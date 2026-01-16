<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management Page - Technical Management System</title>
    <link rel="shortcut icon" href="aulogo.png" type="image/x-icon">
</head>
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
    form {
        display: flex;
        flex-direction: column;
        gap: 5px;
        width: 50vh;
    }
    form h4 {
        font-size: 2rem;
    }
    .search {
        margin-top: 20px;
        padding-bottom: 20px;
    }
    .searchdiv {
        display: flex;
        gap: 10px;
        width: 73vh;
        margin-top: 5px;
    }
    .search label {
        font-size: 1.3rem;
    }
    .searchdiv input[type="text"] {
        flex: 1;
    }
    .searchdiv input[type="submit"] {
        background-color: green;
        padding: 5px 15px;
        color: white;
        font-size: 15px;
        border-radius: 15px;
        cursor: pointer;
    }
    table {
        border: 1px solid black;
        border-collapse: collapse;
    }
    tr, td {
        padding: 20px 10px;
    }
    tr, th {
        padding: 5px 30px;
    }
    tr, th, td {
        border: 1px solid black;
        text-align: center;
    }
    .actions a {
        text-decoration: none;
        color: white;

    }
    .actions #update, #delete {
        padding: 8px;
        margin: 5px;
        border-radius: 15px;
        cursor: pointer;
    }
    .actions #update {
        background-color: blue;
    }
    .actions #delete {
        background-color: red;
    }
</style>
<body>
    <nav>
        <img src="aulogo.png" alt="">
        <div class="usernav">
            <?php
                session_start();
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
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                <h4>Accounts Management</h4>
                <div class="search">
                    <label>Search: </label>
                    <div class="searchdiv">
                        <input type="text" name="txtsearch">
                        <input type="submit" name="btnsearch" value="Search">
                    </div>
                </div>
        <?php
        function buildtable($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr>";
                echo "<th>Username</th><th>User Type</th><th>Status</th><th>Created by</th><th>Date created</th><th>Actions</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['usertype'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['createdby'] . "</td>";
                    echo "<td>" . $row['datecreated'] . "</td>";
                    echo "<td class = 'actions'>";
                    echo "<a href = 'update-account.php?username=". $row['username']. "' id='update'>Update</a>";
                    echo "<a href = 'delete-account.php?username=". $row['username']. "' id='delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No record/s found.";
            }
        }

        require_once "config.php";

        if (isset($_POST['btnsearch'])) {
            $sql = "SELECT * FROM tblaccounts WHERE username LIKE ? OR usertype LIKE ? ORDER BY username";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $searchvalue = '%' . $_POST['txtsearch'] . '%';
                mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);
                }
            }

        } else {

            $sql = "SELECT * FROM tblaccounts ORDER BY username";
            if ($stmt = mysqli_prepare($link, $sql)) {
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);
                }
            }
        }
        ?>
            </form>
        </div>

    </main>
</body>
</html>
