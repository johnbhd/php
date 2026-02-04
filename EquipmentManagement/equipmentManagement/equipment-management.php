<?php
require_once("../config/config.php");
include("../auth/session-checker.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page - Equipment Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="../assets/img/aulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/notification.css">
</head>
<style>
* {
    font-family: Arial, Helvetica, sans-serif;
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body {
    background-color: #e6e9eb;  

}
.container {
    padding: 30px 30px;
    width: 100%;
    height: 100%;
}

form {
    display: flex;
    flex-direction: column;
    gap: 5px;
    width: 100%; 
    max-width: 100%; 
}
form h4 {
    font-size: 2rem;
}
.head-con {
    width: 100%;
    display: flex;
    padding: 20px 0px;
    gap: 20px;
}

.searchdiv {
    display: flex;
    gap: 10px;
    flex: 1;

}

.searchdiv input[type="text"] {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #CBD5E1;
    outline: none;
    font-size: 1rem;
}

.search-btn i {
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

.create-div {
    
}

.create-btn {
    background-color: #15803d;

    border: none;
    text-decoration: none;
    border-radius: 6px;
    padding: 0 15px;
    height: 100%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}


.search-btn:hover, .create-btn:hover {
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
.table-div {
    overflow-x: auto;
    width: 100%;

}
table {
    border: 1px solid #CBD5E1;
    border-collapse: collapse;
    background-color: white;
    width: 100%;
     -webkit-overflow-scrolling: touch;
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
    white-space: nowrap;
}

.actions {
    display: flex;
    justify-content: center;
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

@media screen and (max-width:  768px) {
    .mobile-block {
        display: none;
    }
    .container {
        padding-right: 50px;
    }
}

</style>
<body>
    <?php include("../includes/nav-sidebar.php");?>
        <div class="container">


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
                <h4>Equipments</h4>
                <div class="head-con">
                    <div class="searchdiv">
                        <input type="text" name="txtsearch" placeholder="Search accounts...">
                        <button type="submit" name="btnsearch" class="search-btn">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <div class="create-div">
                        <a href="./create-equipment.php" name="btncreate" class="create-btn">
                            <span style="margin-right: 5px; font-size: 1.4rem;">+</span><span class="mobile-block">Add Equipment</span> 
                        </a>
                    </div>
                </div>
            <div class="table-div">
                <?php
                function buildtable($result) {
                    if (mysqli_num_rows($result) > 0) {
                        echo "<table>";
                        echo "<tr>";
                        echo "<th>Asset No.</th><th>Serial No.</th><th>Type</th><th>Branch</th><th>Status</th><th>Created By</th><th>Actions</th>";
                        echo "</tr>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['asset_number'] . "</td>";
                            echo "<td>" . $row['serial_number'] . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['branch'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td>" . $row['created_by'] . "</td>";
                            echo "<td class = 'actions'>";
                            echo "<a href = 'update-equipment.php?asset_number=". $row['asset_number']. "' class='update'>Update</a>";
                            echo "<a href = 'delete-account.php?asset_number=". $row['asset_number']. "' class='delete'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No record/s found.";
                    }
                }

                if (isset($_POST['btnsearch'])) {
                    $sql = "SELECT * FROM tblequipments WHERE asset_number LIKE ? OR serial_number LIKE ? OR type LIKE ? OR branch LIKE ? OR status LIKE ? OR department LIKE ? ORDER BY date_created";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        $searchvalue = '%' . $_POST['txtsearch'] . '%';
                        mysqli_stmt_bind_param($stmt, "ssssss", $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue);
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            buildtable($result);
                        }
                    }

                } else {

                    $sql = "SELECT * FROM tblequipments ORDER BY type";
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
    </main>
</body>
</html>