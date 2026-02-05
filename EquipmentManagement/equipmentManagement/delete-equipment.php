<?php

if (isset($_POST['btndelete'])) {
    $id = $_POST['delete_id'];

    $sql = "DELETE FROM tblequipments WHERE asset_number = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);

        $logs = "INSERT INTO tbllogs (datelog, timelog, action, module, performedby, performedto) VALUES (?,?,?,?,?,?);";
        $logsStmt = mysqli_prepare($link, $logs);

        $date = date("d/m/Y");
        $time = date("h:i:sa");
        $action = "Delete equipments";
        $module = "Equipments Management";
        
        mysqli_stmt_bind_param($logsStmt, "ssssss", $date, $time, $action, $module, $_SESSION["username"], $id);
        if (mysqli_execute($logsStmt)) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Successfully deleted equipment #'.$id.'.'
            ];
            echo "<script>
                window.location.href = '{$_SERVER['PHP_SELF']}';
            </script>";
            exit;
        }

    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error deleting equipment #'.$id.'.'
        ];
    }


    
}


?>

<style>
.popup-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;             
    align-items: center;        
    justify-content: center;    
    z-index: 999;
}

.popup-field {
    background-color: white;
    padding: 20px 30px;
    border-radius: 6px;
    width: 100%;
    max-width: 400px;          
}
.popup-field h4 {
    padding: 10px 0;
    font-size: 1.3rem;
}
form a {
    text-decoration: none;
    color: black;
}
.btnModal {
    display: flex;
    justify-content: center;
    gap: 4vh;
    padding-top: 20px;
}
#noBtn, #yesBtn {
    padding: 5px 10px;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}
#yesBtn {
    background-color: green;
    border: none;
}
#noBtn {
    background-color: red;
}
</style>

    <div class="popup-overlay" id="popup-overlay"> 
        <div class="popup-field">
            <div>
                <h4>Delete Equipment</h4>
            </div>
            <div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input type="hidden" name="delete_id" id="delete-id"">
                    <p>Are you sure you want to delete this equipment asset no. of <strong id="showId"></strong>?</p>
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