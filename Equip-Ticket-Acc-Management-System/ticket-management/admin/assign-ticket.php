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

        <form action="#" method="POST">

            <input type="hidden" name="assign_id" id="assign-id">

            <p>Assign ticket <strong id="showAssignId"></strong> to technical:</p>

            <select name="technical" required>
                <option value="">Select Technical</option>
                <option value="tech1">tech1</option>
                <option value="tech2">tech2</option>
                <option value="tech3">tech3</option>
            </select>

            <div class="btnModal">
                <input type="submit" value="Assign" id="assignBtn">
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