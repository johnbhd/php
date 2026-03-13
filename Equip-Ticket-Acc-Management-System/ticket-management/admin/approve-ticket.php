<?php

if (isset($_POST['approveBtn'])) {
    $ticket_number = $_POST['approve_id'];
    $approve_by = $_SESSION['username'];
    $date_approved = date("Y-m-d H:i:s");
    $status = "COMPLETED";
    
    $statusCheck = "SELECT status FROM tbltickets WHERE ticket_number = ?";
        $stmtCheck = mysqli_prepare($link, $statusCheck);
        mysqli_stmt_bind_param($stmtCheck, "s",  $ticket_number);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $rowCheck = mysqli_fetch_assoc($resultCheck);

        if ($rowCheck['status'] !== "WAITING FOR APPROVAL" ) {

            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'You cannot approve this ticket #'.$ticket_number.' because its status is ' . $rowCheck['status']
            ];

            echo "<script>window.location.href = '{$_SERVER['PHP_SELF']}';</script>";
            exit;
        }
  
    $sql = "UPDATE tbltickets SET approved_by = ?, date_approved = ?, status = ? WHERE ticket_number = ?";
    $stmt = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt, "ssss", $approve_by, $date_approved, $status, $ticket_number);
    
    $logs = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?)";
    $logsStmt = mysqli_prepare($link, $logs);

    $date = date("d/m/Y");
    $time = date("h:i:sa");
    $action = "Approved ticket";
    $module = "Ticket Management";
        
    mysqli_stmt_bind_param($logsStmt, "ssssss", $date, $time, $action, $module, $_SESSION["username"], $ticket_number);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_execute($logsStmt);
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Successful approving this ticket #' . $ticket_number . "!"
        ];
        echo "<script>
                window.location.href = '{$_SERVER['PHP_SELF']}';
        </script>";
        exit;
        
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error approving this ticket #' . $ticket_number . "!"
        ];
    }
}



?>

<style>
.popup-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.4);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:999;
    font-family:Arial, sans-serif;
}

.popup-field{
    background:white;
    padding:20px;
    border-radius:8px;
    width:90%;
    max-width:400px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.popup-field h4{
    margin-bottom:10px;
}

.btnModal{
    margin-top:15px;
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

#approveBtn{
    padding:8px 14px;
    border:none;
    background:#2e7d32;
    color:white;
    border-radius:4px;
    cursor:pointer;
}

#cancelBtn{
    padding:8px 14px;
    border:none;
    background:#777;
    color:white;
    border-radius:4px;
    cursor:pointer;
}
</style>


<div class="popup-overlay" id="popup-approve"> 
    <div class="popup-field">

        <h4>Approve Ticket</h4>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">

            <input type="hidden" name="approve_id" id="approve-id">

            <p>
                Are you sure you want to approve ticket 
                <strong id="showApproveId"></strong> ?
            </p>

            <div class="btnModal">
                <button type="submit" name="approveBtn" id="assignBtn">Yes</button>
                <button type="button" onclick="approveClose()" id="cancelBtn">No</button>
            </div>

        </form>

    </div>
</div>


<script>
function approveModal(id){
    document.getElementById('popup-approve').style.display='flex';
    document.getElementById('approve-id').value=id;
    document.getElementById('showApproveId').textContent="#"+id;
}

function approveClose(){
    document.getElementById('popup-approve').style.display='none';
}
</script>