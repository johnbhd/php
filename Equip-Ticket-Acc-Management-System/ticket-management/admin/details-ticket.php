
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
    width:90%;
    max-width:420px;
    border-radius:8px;
    padding:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.popup-field h4{
    margin-bottom:15px;
    font-size:20px;
    border-bottom:1px solid #eee;
    padding-bottom:8px;
}

.details-content p{
    margin:8px 0;
    font-size:14px;
}

.details-content strong{
    color:#333;
}

.btnModal{
    margin-top:15px;
    text-align:right;
}

.btnModal button{
    padding:8px 16px;
    border:none;
    background:#333;
    color:white;
    border-radius:4px;
    cursor:pointer;
}

.btnModal button:hover{
    background:#555;
}
</style>
<div class="popup-overlay" id="popup-details"> 
    <div class="popup-field">
        <div>
            <h4>Ticket Details</h4>
        </div>

        <div class="details-content">
            <p><strong>Ticket Number:</strong> <span id="show-id"></span></p>
            <p><strong>Problem:</strong> <span id="ticketProblem"></span></p>
            <p><strong>Description:</strong> <span id="ticketDescription"></span></p>
            <p><strong>Status:</strong> <span id="ticketStatus"></span></p>
            
            <p><strong>Time:</strong> <span id="ticketTime"></span></p>
            
            <p><strong>Assigned To:</strong> <span id="ticketAssigned"></span></p>
            <p><strong>Date Assigned:</strong> <span id="ticketDateAssigned"></span></p>
            
            <p><strong>Date Completed:</strong> <span id="ticketDateCompleted"></span></p>
            
            <p><strong>Date Approved:</strong> <span id="ticketDateApproved"></span></p>
            <p><strong>Approved By:</strong> <span id="ticketApprovedBy"></span></p>
            
            <div class="btnModal">
                <button onclick="detailsClose()" id="closeBtn">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
function detailsModal(id) {

    document.getElementById('popup-details').style.display = 'flex';
    document.getElementById('show-id').textContent = id;

    fetch("admin/get-ticket.php?ticket_number=" + id)
    .then(res => res.json())
    .then(data => {

        document.getElementById("ticketProblem").textContent = data.problem || "-";
        document.getElementById("ticketDescription").textContent = data.details || "-";
        document.getElementById("ticketStatus").textContent = data.status|| "-";
        document.getElementById("ticketTime").textContent = data.date_created || "-";
        
        document.getElementById("ticketAssigned").textContent = data.assigned_to || "-";
        document.getElementById("ticketDateAssigned").textContent = data.date_assigned || "-";
        document.getElementById("ticketDateCompleted").textContent = data.date_completed || "-";
        document.getElementById("ticketDateApproved").textContent = data.date_approved || "-";
        document.getElementById("ticketApprovedBy").textContent = data.approved_by || "-";
    });

}

function detailsClose() {
    document.getElementById('popup-details').style.display = 'none';
}

</script>