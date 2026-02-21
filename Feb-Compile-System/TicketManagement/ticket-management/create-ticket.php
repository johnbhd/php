<?php
require_once("../config/config.php");
include("../auth/session-checker.php");

if (!isset($_SESSION['ticket_number'])) {
    $_SESSION['ticket_number'] = date("YmdHis");
}
$ticket_number = $_SESSION['ticket_number'];

if (isset($_POST['btnadd'])) {
    $problem = $_POST['problem'];
    $details = $_POST['details'];

    $status = "PENDING";
    $createdBy = $_SESSION['username'];
    $date_created = date("m-d-y H:i:s");
    
    $sql = "INSERT INTO tbltickets (ticket_number, problem, details, status, created_by, date_created) VALUES (?, ?, ?, ?, ?, ?); ";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $ticket_number, $problem, $details, $status, $createdBy, $date_created);

    if (mysqli_stmt_execute($stmt)) {
            unset($_SESSION['ticket_number']);
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Successful created new ticket!'
            ];
            header("location: ./ticket-management.php");
            exit();
    } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error creating new ticket!'
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
            <h4>Add Ticket</h4>
            <div class="wrapper">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
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
                                    <option value="" disabled selected>Select problem</option>
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                    <option value="Connection">Connection</option>
                                </select>
                            </div>
                      
                        </div>

                        <!-- Col 2 -->
                        <div class="col2-input">
                            <div class="input-div">
                                <label>Problem Details</label>
                                <textarea name="details" required></textarea>
                            </div>
                            
              
                        </div>
                    </div>
                    <div class="btn-div">
                        <button type="submit" name="btnadd" class="add-btn">
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