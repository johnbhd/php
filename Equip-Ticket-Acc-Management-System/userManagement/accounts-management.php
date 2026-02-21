<?php

include("../auth/session-checker.php");
checkRoles(['ADMINISTRATOR']);

$_SESSION['title'] = "Account Management System";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management Page - Technical Management System</title>
    <link rel="shortcut icon" href="../assets/img/aulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/accounts-managements.css">
    <link rel="stylesheet" href="../assets/css/notification.css">
</head>
<body>
    <?php include("../includes/nav-sidebar.php")?>
        <div class="container">
            <div class="wrapper">
                    <?php 
                    if (isset($_SESSION['message']) && $_SESSION['message']['type'] == 'success'): 
                        $type = $_SESSION['message']['type']; 
                        $text = $_SESSION['message']['text'];
                    ?>
                    <div class="notification <?php echo $type; ?>">
                        <div class="notification-body">
                            <div class="noti-head">
                                <i class="fa-solid fa-check icon successful"></i>
                                <h4><?php echo ucfirst($type); ?></h4>
                            </div>
                            <div class="noti-body">
                                <p><?php echo $text; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php unset($_SESSION['message']); endif; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                            <h4>Accounts Management</h4>
                            <div class="head-con">
                                    <div class="searchdiv">
                                        <input type="text" name="txtsearch" placeholder="Search accounts...">
                                        <button type="submit" name="btnsearch" class="search-btn">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </div>
                                    <div class="create-div">
                                        <a href="./create-account.php" name="btncreate" class="create-btn">
                                            <span style="margin-right: 5px; font-size: 1.4rem;">+</span><span class="mobile-block">Add User</span> 
                                        </a>
                                    </div>
                                </div>
                    <div class="table-div">
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

                        require_once "../config/config.php";

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
                    </div>
                </form>
            </div>
            <?php include("../includes/footer.php")?>
        </div>
    </main>
</body>
</html>
