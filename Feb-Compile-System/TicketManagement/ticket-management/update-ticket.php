<?php
require_once("../config/config.php");
include("../auth/session-checker.php");


$ticket_number = $_GET['ticket_number'];

$sqlCurrent = "SELECT * FROM tbltickets WHERE ticket_number = ?";
$stmtCurrent = mysqli_prepare($link, $sqlCurrent);
mysqli_stmt_bind_param($stmtCurrent, "s", $ticket_number);

if (mysqli_execute($stmtCurrent)) {
    $result = mysqli_stmt_get_result($stmtCurrent);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Error fetch ticket data!'
    ];
}

if (isset($_POST['btnupdate'])) {
    $problem = $_POST['problem'];
    $details = $_POST['details'];

    $status = "WORKING";
    $createdBy = $_SESSION['username'];
    $date_created = date("d/m/y");
    
    $sql = "UPDATE tbltickets SET problem = ?, details = ? WHERE ticket_number = ?";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sss",  $problem, $details, $ticket_number);

    if (mysqli_execute($stmt)) {
        $logs = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?);";
        $logsStmt = mysqli_prepare($link, $logs);

        $date = date("d/m/Y");
        $time = date("h:i:sa");
        $action = "Update ticket";
        $module = "Ticketing Management System";
        
        mysqli_stmt_bind_param($logsStmt, "ssssss", $date, $time, $action, $module, $_SESSION["username"], $ticket_number);
        if (mysqli_stmt_execute($logsStmt)) {
                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'Successful update ticket #'.$ticket_number.'!'
                ];
                header("location: ./ticket-management.php");
                exit();
        }
    } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error updating ticket #'.$ticket_number.'!'
            ];
    }
        

}
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
    <link rel="stylesheet" href="../assets/css/ticket-style/create.css">
</head>
<body>
    
    <?php include("../includes/nav-sidebar.php");?>
    <div class="container">
            <h4>Update Ticket</h4>
            <div class="wrapper">
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI']));?>" method="POST">
                    <div class="col-div">
                        <div class="col1-input">
                            <div class="input-div">
                                <label class="labelInput">
                                    Ticket Number 
                                </label>
                                <input type="text" value="<?php echo $ticket_number?>" disabled>
                            </div>
                            <div class="input-div">
                                <label class="labelInput">
                                    Problem              
                                </label>
                                <select name="problem" required>
                                    <option value="Hardware" <?php if($data['problem'] == "Hardware") echo "selected"?>>Hardware</option>
                                    <option value="Software" <?php if($data['problem'] == "Software") echo "selected"?>>Software</option>
                                    <option value="Connection" <?php if($data['problem'] == "Connection") echo "selected"?>>Connection</option>
                                </select>
                            </div>
                      
                        </div>

                        <!-- Col 2 -->
                        <div class="col2-input">
                            <div class="input-div">
                                <label>Problem Details</label>
                                <textarea name="details" required><?php echo htmlspecialchars($data['details']);?></textarea>
                            </div>
                            
              
                        </div>
                    </div>
                    <div class="btn-div">
                        <button type="submit" name="btnupdate" class="add-btn">
                            Save
                        </button>
                        <a href="./ticket-management.php" class="cancel-btn">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
                
        </div>
    </main>
</body>

</html>