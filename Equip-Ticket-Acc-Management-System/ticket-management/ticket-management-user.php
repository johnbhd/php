<?php
require_once("../config/config.php");
include("../auth/session-checker.php");
checkRoles(['USER']);

$_SESSION['title'] = "Ticketing Management System";



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page - Ticket Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="../assets/img/aulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/notification.css">
    <link rel="stylesheet" href="../assets/css/ticket-style/mains.css">
</head>
<style>
    .actions {
        justify-content: center;
    }

</style>
<body>
    <?php include("../includes/nav-sidebar.php");?>
        <div class="container">
            <div class="wrapper"> 
                <?php 
                if (isset($_SESSION['message'])):
                    $type = $_SESSION['message']['type']; 
                    $text = $_SESSION['message']['text'];
                ?>
                <div class="notification <?php echo $type; ?>">
                    <div class="notification-body">
                        <div class="noti-head">
                            <i class="fa-solid <?php echo $type == 'success' ? 'fa-check icon successful' : 'fa-xmark icon errors'; ?>"></i>
                            <h4><?php echo ucfirst($type); ?></h4>
                        </div>
                        <div class="noti-body">
                            <p><?php echo $text; ?></p>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['message']); endif; ?>

                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                        <h4>Tickets</h4>
                        <div class="head-con">
                            <div class="searchdiv">
                                <input type="text" name="txtsearch" placeholder="Search ticket...">
                                <button type="submit" name="btnsearch" class="search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            <div class="create-div">
                                <a href="./create-ticket.php" name="btncreate" class="create-btn">
                                    <span style="margin-right: 5px; font-size: 1.4rem;">+</span><span class="mobile-block">Add Ticket</span> 
                                </a>
                            </div>
                        </div>
                        <div class="table-div">
                            <?php
                            function buildtable($result) {
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<table>";
                                    echo "<tr>";
                                    echo "<th>Ticket No.</th><th>Problem</th><th>Date Created</th><th>Status</th><th>Actions</th>";
                                    echo "</tr>";
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['ticket_number'] . "</td>";
                                        echo "<td>" . $row['problem'] . "</td>";
                                        echo "<td>" . $row['date_created'] . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td class = 'actions'>";
                                        
                                        echo "<button type='button'
                                            name='thisUpdate'
                                            onclick=\"updateModal('".$row['ticket_number']."')\"
                                            class='details'>
                                            Update
                                        </button>";
                                            echo "<button type='button'
                                            onclick=\"deleteModal('".$row['ticket_number']."')\"
                                            class='delete'>
                                            Delete
                                        </button>";
                                        echo "</td>";
                                    
                                        echo "</tr>";
                                    }
                                    echo "<tr class='countTotal'>";
                                    echo "<td>Total of Ticket</td>";
                                    echo "<td colspan='3'></td>";
                                    echo "<td>". mysqli_num_rows($result)."</td>";
                                    echo "</tr>";
                                    echo "</table>";
                                } else {
                                    echo "No ticket found.";
                                }
                        }

                        if (isset($_POST['btnsearch'])) {

                            $sql = "SELECT * FROM tbltickets WHERE created_by = ? AND (ticket_number LIKE ? OR problem LIKE ? OR status LIKE ?) ORDER BY date_created DESC";
                            if ($stmt = mysqli_prepare($link, $sql)) {
                                $searchvalue = '%' . $_POST['txtsearch'] . '%';
                                mysqli_stmt_bind_param($stmt, "ssss", $_SESSION['username'], $searchvalue, $searchvalue, $searchvalue);
                                if (mysqli_stmt_execute($stmt)) {
                                    $result = mysqli_stmt_get_result($stmt);
                                    buildtable($result);
                                }
                            }

                        } else {

                            $sql = "SELECT * FROM tbltickets WHERE created_by = ?  ORDER BY date_created DESC";
                            if ($stmt = mysqli_prepare($link, $sql)) {
                                mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
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
          <?php include("./user/delete-ticket.php");?>
          <?php include("./user/update-ticket.php");?>
</body>
<script>
    
</script>
</html>