<?php

if (isset($_POST['btndelete'])) {
    $id = $_POST['delete_id'];

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

    <div class="popup-overlay" id="popup-delete"> 
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
                        <button type="button" onclick="deleteClose()" id="noBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
function deleteModal(id) {
    document.getElementById('popup-delete').style.display = 'flex';
    document.getElementById('delete-id').value = id;
    document.getElementById('showId').textContent = `#${id}`;
}

function deleteClose() {
    document.getElementById('popup-delete').style.display = 'none';
}

</script>