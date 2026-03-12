<?php

if (isset($_POST['btndelete'])) {
    $id = $_POST['delete_id'];

    $statusCheck = "SELECT status FROM tbltickets WHERE ticket_number = ?";
        $stmtCheck = mysqli_prepare($link, $statusCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $id);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $rowCheck = mysqli_fetch_assoc($resultCheck);

        if ($rowCheck['status'] !== "PENDING") {

            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'You cannot delete this ticket because its status is ' . $rowCheck['status']
            ];

            echo "<script>window.location.href = '{$_SERVER['PHP_SELF']}';</script>";
            exit;
        }

    $sql = "DELETE FROM tbltickets WHERE ticket_number = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);

        $logs = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?)";
        $logsStmt = mysqli_prepare($link, $logs);

        $date = date("d/m/Y");
        $time = date("h:i:sa");
        $action = "Delete ticket";
        $module = "Ticketing Management System";
        
        mysqli_stmt_bind_param($logsStmt, "ssssss", $date, $time, $action, $module, $_SESSION["username"], $id);
        if (mysqli_stmt_execute($logsStmt)) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Successfully deleted ticket #'.$id.'.'
            ];
            echo "<script>
                window.location.href = '{$_SERVER['PHP_SELF']}';
            </script>";
            exit;
        }

    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error deleting ticket #'.$id.'.'
        ];
    }


    
}


?>

    <link rel="stylesheet" href="../assets/css/ticket-style/delete.css">

    <div class="popup-overlay" id="popup-overlay"> 
        <div class="popup-field">
            <div>
                <h4>Delete Ticket</h4>
            </div>
            <div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input type="hidden" name="delete_id" id="delete-id">
                    <p>Are you sure you want to delete this ticket no. of <strong id="showId"></strong>?</p>
                    <div class="btnModal">
                        <input type="submit" name="btndelete" value="YES" id="yesBtn">
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="noBtn">NO</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
function deleteModal(id) {
    document.getElementById('popup-overlay').style.display = 'flex';
    document.getElementById('delete-id').value = id;
    document.getElementById('showId').textContent = `#${id}`;
}

function closeModal() {
    document.getElementById('popup-overlay').style.display = 'none';
}

</script>