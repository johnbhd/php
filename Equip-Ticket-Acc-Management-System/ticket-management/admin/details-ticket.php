<?php

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
            <p><strong>Problem:</strong> <span id="ticketProblem">Monitor not working</span></p>
            <p><strong>Description:</strong> <span id="ticketDescription">The monitor does not power on.</span></p>
            <p><strong>Status:</strong> <span id="ticketStatus">NEW</span></p>
            <p><strong>Date:</strong> <span id="ticketDate">2026-03-08</span></p>
            <p><strong>Time:</strong> <span id="ticketTime">10:30 AM</span></p>

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
    document.getElementById('showId').textContent = `#${id}`;
}

function detailsClose() {
    document.getElementById('popup-details').style.display = 'none';
}

</script>