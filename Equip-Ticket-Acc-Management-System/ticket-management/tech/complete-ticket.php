<?php

if (isset($_POST['completeBtn'])) {
    $ticket_number = $_POST['complete_id'];

    $sql = "UPDATE tbltickets SET date_completed = ?, status = ? WHERE ticket_number = ?";
    $stmt = mysqli_prepare($link, $sql);

    $dateComplete = date("Y-m-d H:i:s");
    $status = "FOR APPROVAL";

    mysqli_stmt_bind_param($stmt,"sss", $dateComplete, $status, $ticket_number);

    $logs = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?)";
    $logsStmt = mysqli_prepare($link, $logs);

    $date = date("d/m/Y");
    $time = date("h:i:sa");
    $action = "Completed ticket ";
    $module = "Ticket Management";
        
    mysqli_stmt_bind_param($logsStmt, "ssssss", $date, $time, $action, $module, $_SESSION["username"], $ticket_number);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_execute($logsStmt);
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Successful complete this ticket #' . $ticket_number . "!"
        ];
        echo "<script>
                window.location.href = '{$_SERVER['PHP_SELF']}';
        </script>";
        exit;
        
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error completing this ticket #' . $ticket_number . "!"
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
    background:#fff;
    padding:20px;
    border-radius:8px;
    width:90%;
    max-width:420px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.popup-field h4{
    margin-bottom:15px;
}

.popup-field select{
    width:100%;
    padding:8px;
    margin-top:10px;
}

.btnModal{
    margin-top:15px;
    display:flex;
    gap:10px;
    justify-content:flex-end;
}

#assignBtn{
    padding:8px 14px;
    background:#2e7d32;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
}

#cancelBtn{
    padding:8px 14px;
    background:#777;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
}
</style>


<div class="popup-overlay" id="popup-complete"> 
    <div class="popup-field">

        <h4>Complete Ticket</h4>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">

            <input type="hidden" name="complete_id" id="complete-id">

            <p>Are you sure you want to complete this ticket <span id="showcompleteId"></span>?</p>



            <div class="btnModal">
                <button type="submit" name="completeBtn" id="assignBtn">Confirm Complete</button>
                <button type="button" onclick="completeClose()" id="cancelBtn">Cancel</button>
            </div>

        </form>

    </div>
</div>


<script>
function completeModal(id){
    document.getElementById('popup-complete').style.display='flex';
    document.getElementById('complete-id').value=id;
    document.getElementById('showcompleteId').textContent="#"+id;
}

function completeClose(){
    document.getElementById('popup-complete').style.display='none';
}
</script>