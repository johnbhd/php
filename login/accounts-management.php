

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management Page - Technical Management System</title>
</head>
<body>
    <?php
        session_start();
        if (isset($_SESSION['username'])) {
            echo "<h1>Welcome, ". $_SESSION['username'] . "</h1>";
            echo "<h1>Account Type: ". $_SESSION['usertype'] . "</h1>";
        } else {
            header('location: login.php');
        }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
        
        <a href="create-account.php">Create Account</a>
        <a href="logout.php">Logout</a>
        <h4>Login Page</h4>
        <hr>Search: <input type="text" name="txtsearch">
        <input type="submit" name="btnsearch" value="Search">
    </form>
</body>
</html>
<?php
function buildtable($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Username</th><th>User Type</th>Created by<th>Status</th><th>Created by</th><th>Date created</th><th>Actions</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_array($result)) {
             echo "<tr>";
             echo "<td>" . $row['username'] . "</td>";
             echo "<td>" . $row['usertype'] . "</td>";
             echo "<td>" . $row['status'] . "</td>";
             echo "<td>" . $row['createdby'] . "</td>";
             echo "<td>" . $row['datecreated'] . "</td>";
             echo "<td>";
             echo "<a href = 'update-account.php?username=". $row['username']. "'>Update</a>";
             echo "<a href = 'delete-account.php?username=". $row['username']. "'>Delete</a>";
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
