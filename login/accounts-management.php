<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management Page - Technical Management System</title>
    <link rel="shortcut icon" href="aulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<style>
   * {
    font-family: Arial, Helvetica, sans-serif;
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}

nav {
    display: flex;
    align-items: center;
    font-size: 1rem;
    gap: 20px;
    background-color: #1E293B;
    color: #E2E8F0;
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
    padding: 30px 40px;
    gap: 30px;
    background-color: #111827;
}

aside a {
    text-decoration: none;
    color: #CBD5E1;
    cursor: pointer;
    font-size: 1.3rem;
}

aside a:hover {
    color: #FFFFFF;
}

main {
    display: flex;
    height: 100vh;
}

.container {
    padding: 20px 30px;
    background-color: #F1F5F9;
    width: 100%;
}

form {
    display: flex;
    flex-direction: column;
    gap: 5px;
    width: 100%; /* allow full width */
    max-width: 100%; /* optional */
}
form h4 {
    font-size: 2rem;
}

.searchdiv {
    padding: 20px 0px;
    display: flex;
    gap: 10px;
    width: 100%;
    max-width: 400px;
}

.searchdiv input[type="text"] {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #CBD5E1;
    outline: none;
    font-size: 1rem;
}

.search-btn {
    background-color: #4F46E5;
    border: none;
    border-radius: 6px;
    padding: 0 15px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.search-btn i {
    font-size: 1rem;
}

.search-btn:hover {
    opacity: 0.8;
}

.searchdiv input[type="text"]:hover,
.update:hover,
.delete:hover {
    opacity: 0.8;
}

.searchdiv input[type="submit"] {
    background-color: #4F46E5;
    padding: 5px 15px;
    border: none;
    color: white;
    font-size: 15px;
    border-radius: 15px;
    cursor: pointer;
}

table {
    border: 1px solid #CBD5E1;
    border-collapse: collapse;
    background-color: white;
}
th {
    background-color: #303d52;
    color: white;
    padding: 15px 20px; 
    font-size: 1.3rem;
}
td {
    font-size: 1.1rem;
    padding: 15px 20px; 
}

th {
    background-color: #303d52;
    color: white;
    
}
tr, th, td {
    border: 1px solid #CBD5E1;
    text-align: center;
}

.actions {
    display: flex;
}

.actions a {
    text-decoration: none;
    color: white;
}

.actions .update,
.actions .delete {
    padding: 8px;
    margin: 5px;
    border-radius: 15px;
    cursor: pointer;
}

.actions .update {
    background-color: #3B82F6;
}

.actions .delete {
    background-color: #EF4444;
}

td.actions {
    border: none;
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
                <div class="searchdiv">
                    <input type="text" name="txtsearch" placeholder="Search accounts...">
                    <button type="submit" name="btnsearch" class="search-btn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
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
                    echo "<a href = 'update-account.php?username=". $row['username']. "' class='update'>Update</a>";
                    echo "<a href = 'delete-account.php?username=". $row['username']. "' class='delete'>Delete</a>";
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
