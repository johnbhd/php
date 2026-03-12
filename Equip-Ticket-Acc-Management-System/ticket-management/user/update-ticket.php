<?php

if (isset($_POST['updateBtn'])) {

    $ticket_number = $_POST['update_id'];
    $problem = $_POST['problem'];
    $details = $_POST['details'];

    $statusCheck = "SELECT status FROM tbltickets WHERE ticket_number = ?";
    $stmtCheck = mysqli_prepare($link, $statusCheck);
    mysqli_stmt_bind_param($stmtCheck, "s", $ticket_number);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);
    $rowCheck = mysqli_fetch_assoc($resultCheck);

    if ($rowCheck['status'] !== "PENDING") {

        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'You cannot update this ticket because its status is ' . $rowCheck['status']
        ];

        echo "<script>window.location.href = '{$_SERVER['PHP_SELF']}';</script>";
        exit;
    }

    $sql = "UPDATE tbltickets SET problem = ?, details = ? WHERE ticket_number = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $problem, $details, $ticket_number);


    $logs = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto)
             VALUES (?,?,?,?,?,?)";
    $logsStmt = mysqli_prepare($link, $logs);

    $date = date("d/m/Y");
    $time = date("h:i:sa");
    $action = "Update ticket";
    $module = "Ticket Management";

    mysqli_stmt_bind_param($logsStmt, "ssssss", $date, $time, $action, $module, $_SESSION["username"], $ticket_number);

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_execute($logsStmt);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Successfully updated ticket #' . $ticket_number . "!"
        ];

        echo "<script>window.location.href = '{$_SERVER['PHP_SELF']}';</script>";
        exit;

    } else {

        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error updating ticket #' . $ticket_number . "!"
        ];
    }
}
?>

<style>
.popup-overlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999;
    font-family: Arial, sans-serif;
}

.popup-field{
    background: #ffffff;
    padding: 25px;
    border-radius: 10px;
    width: 90%;
    max-width: 520px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    animation: popupFade .2s ease-in-out;
}

.popup-title{
    margin-bottom: 18px;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.ticket-form{
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.ticket-grid{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.ticket-group{
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.ticket-label{
    font-size: 13px;
    color: #555;
}

.ticket-input,
.ticket-select,
.ticket-textarea{
    width: 100%;
    padding: 8px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    outline: none;
}

.ticket-textarea{
    resize: vertical;
    min-height: 80px;
}

.ticket-actions{
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
}

.ticket-update-btn{
    padding: 8px 16px;
    background: #2e7d32;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.ticket-update-btn:hover{
    background: #256628;
}

.ticket-cancel-btn{
    padding: 8px 16px;
    background: #777;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.ticket-cancel-btn:hover{
    background: #5f5f5f;
}

@media (max-width:600px){
    .ticket-grid{
        grid-template-columns: 1fr;
    }
}

@keyframes popupFade{
    from{
        transform: scale(0.9);
        opacity: 0;
    }
    to{
        transform: scale(1);
        opacity: 1;
    }
}
</style>

   <div class="popup-overlay" id="popup-update"> 
    <div class="popup-field">

        <div class="popup-title">Update Ticket</div>

        <form class="ticket-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">

            <div class="ticket-grid">
    
                <div class="ticket-group">
                    <label class="ticket-label">Ticket Number</label>
                    <input class="ticket-input" type="text"  name="update_id" id="update-id" readonly>
                </div>

                <div class="ticket-group">
                    <label class="ticket-label">Problem</label>
                    <select class="ticket-select" name="problem" id="problem" required>
                        <option disabled selected>Select problem</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Connection">Connection</option>
                    </select>
                </div>

                <div class="ticket-group" style="grid-column: span 2;">
                    <label class="ticket-label" >Problem Details</label>
                    <textarea class="ticket-textarea" name="details" id="problemDetails" required></textarea>
                </div>

            </div>

            <div class="ticket-actions">
                <button type="submit" name="updateBtn" class="ticket-update-btn">Update</button>
                <button type="button" onclick="updateClose()" class="ticket-cancel-btn">Cancel</button>
            </div>

        </form>

    </div>
</div>
<script>
function updateModal(id) {
    document.getElementById('popup-update').style.display = 'flex';
    document.getElementById('update-id').value = id;

    fetch("./user/get-ticket.php?ticket_number=" + id)
    .then(res => res.json())
    .then(data => {
        document.getElementById("problemDetails").value = data.details || "-";
        document.getElementById("problem").value = data.problem || "-";

    });
}

function updateClose() {
    document.getElementById('popup-update').style.display = 'none';
}

</script>