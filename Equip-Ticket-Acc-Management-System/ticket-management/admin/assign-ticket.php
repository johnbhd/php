<?php

$sql = "SELECT * FROM tblaccounts WHERE usertype = 'TECHNICAL' AND status = 'ACTIVE'";
$stmt = mysqli_prepare($link, $sql);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_num_rows($result);

if (isset($_POST['assignBtn'])) {
    $assigned_id = $_POST['assign_id'];
    $assigned_to = $_POST['assign_to'];
    $date_assigned = date('Y-m-d H:i:s');
    $status = "ON-GOING";

    $statusCheck = "SELECT status FROM tbltickets WHERE ticket_number = ?";
        $stmtCheck = mysqli_prepare($link, $statusCheck);
        mysqli_stmt_bind_param($stmtCheck, "s",  $assigned_id);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $rowCheck = mysqli_fetch_assoc($resultCheck);

        if ($rowCheck['status'] !== "PENDING") {

            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'You cannot assign this ticket because its status is ' . $rowCheck['status']
            ];

            echo "<script>window.location.href = '{$_SERVER['PHP_SELF']}';</script>";
            exit;
        }
  
  $updateSql = "UPDATE tbltickets SET assigned_to = ?, date_assigned = ?, status = ? WHERE ticket_number = ?";
  $updateStmt = mysqli_prepare($link, $updateSql);
  
  mysqli_stmt_bind_param($updateStmt, "ssss", $assigned_to, $date_assigned, $status, $assigned_id);
  mysqli_stmt_execute($updateStmt);

    $logs = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?)";
    $logsStmt = mysqli_prepare($link, $logs);

    $date = date("d/m/Y");
    $time = date("h:i:sa");
    $action = "Assign ticket";
    $module = "Ticket Management";
    
    mysqli_stmt_bind_param($logsStmt, "ssssss", $date, $time, $action, $module, $_SESSION["username"], $assigned_id);

  if(mysqli_stmt_execute($updateStmt)){
        mysqli_stmt_execute($logsStmt);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Successful assign this ticket #' . $assigned_id . "!"
        ];
        echo "<script>
                window.location.href = '{$_SERVER['PHP_SELF']}';
        </script>";
        exit;
        
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error assigning this ticket #' . $assigned_id . "!"
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


<div class="popup-overlay" id="popup-assign"> 
    <div class="popup-field">

        <h4>Assign Ticket</h4>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">

            <input type="hidden" name="assign_id" id="assign-id">

            <p>Assign ticket <strong id="showAssignId"></strong> to technical:</p>

            <select name="assign_to" required>
                <option value="">Select Technical</option>
                <?php 
                  if (mysqli_num_rows($result) > 0) {
                    
                    while ($data = mysqli_fetch_assoc($result)) {
                      echo "<option value='". $data['username'] ."'>". $data['username'] ."</option>";
                    }
                  } else {
                    echo "<option disabled>NO technical found.</option>";
                  }
                ?>
            </select>

            <div class="btnModal">
                <button type="submit" name="assignBtn" id="assignBtn">Assign</button>
                <button type="button" onclick="assignClose()" id="cancelBtn">Cancel</button>
            </div>

        </form>

    </div>
</div>


<script>
function assignModal(id){
    document.getElementById('popup-assign').style.display='flex';
    document.getElementById('assign-id').value=id;
    document.getElementById('showAssignId').textContent="#"+id;
}

function assignClose(){
    document.getElementById('popup-assign').style.display='none';
}
</script>