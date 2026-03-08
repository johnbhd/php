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

        <form action="#" method="POST">

            <input type="hidden" name="approve_id" id="approve-id">

            <p>
                Are you sure you want to approve ticket 
                <strong id="showApproveId"></strong> ?
            </p>

            <div class="btnModal">
                <input type="submit" value="YES" id="approveBtn">
                <button type="button" onclick="approveClose()" id="cancelBtn">NO</button>
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