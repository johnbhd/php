<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page - Equipment Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="../assets/img/aulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/notification.css">
</head>
<style>
* {
    font-family: Arial, Helvetica, sans-serif;
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body {
    background-color: #e6e9eb;  

}
.container {
    padding: 30px 30px;
    width: 100%;
    height: 100%;
}
.container h4 {
    font-size: 2rem;
}
.wrapper {
    background-color: #fafafa;
    width: 100%; 
    height: auto;
    padding: 20px;
    border-radius: 5px;
    margin-top: 20px;
}
form {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 10px;
}

.col-div {
    display: flex;
    gap: 30px;
}
.col1-input, .col2-input {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}
.input-div {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.input-div input, select, textarea {
    padding: 10px 10px;
    outline: none;
    border: 1px solid #CBD5E1;
    border-radius: 5px;
    font-size: 1rem;
}
.input-div input:focus, select:focus, textarea:focus {
    border-color: #111827;
    box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.25);
}
.input-div label {
    font-size: 1.3rem;
}
textarea {
    resize: vertical;
}

.btn-div {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 15px;
}

.add-btn {
    background-color: #354d74;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.1s ease;
}

.add-btn:hover {
    background-color: #1f2937;
    transform: translateY(-1px);
}

.cancel-btn {
    background-color: transparent;
    color: #111827;
    border: 1px solid #111827;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 1.1rem;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease;
}

.cancel-btn:hover {
    background-color: #111827;
    color: white;
}
@media screen and (max-width:  768px) {
    .col-div {
        display: flex;
        flex-direction: column;
    }   
}

</style>
<body>
    
    <?php include("../includes/nav-sidebar.php");?>
    <div class="container">
            <h4>Update Equipment > 
                <span>#
                    <?php 
                        echo $_GET["asset_number"];
                    ?>
                </span>
            </h4>
            <div class="wrapper">
                <form action="">
                    <div class="col-div">
                        <div class="col1-input">
                            <div class="input-div">
                                <label>Asset Number <span>*</span></label>
                                <input type="text" name="" id="">
                            </div>
                            <div class="input-div">
                                <label>Select Type <span>*</span></label>
                                <select id="">
                                    <option disabled selected hidden>Choose equipment type</option>
                                    <option value="Monitor">Monitor</option>
                                    <option value="CPU">CPU</option>
                                    <option value="Keyboard">Keyboard</option>
                                    <option value="Mouse">Mouse</option>
                                    <option value="AVR">AVR</option>
                                    <option value="MAC">MAC</option>
                                    <option value="Printer">Printer</option>
                                    <option value="Projector">Projector</option>

                                </select>
                            </div>
                            <div class="input-div">
                                <label>Year Model <span>*</span></label>
                                <input type="text" name="" id="">
                            </div>
                            <div class="input-div">
                                <label>Description <span>*</span></label>
                                <textarea type="" name="" id=""></textarea>
                            </div>
                        </div>
                        <div class="col2-input">
                            <div class="input-div">
                                <label>Serial Number <span>*</span></label>
                                <input type="text" name="" id="">
                            </div>
                            <div class="input-div">
                                <label>Manufacturer <span>*</span></label>
                                <input type="text" name="" id="">
                            </div>
                            <div class="input-div">
                                <label>Asset Number <span>*</span></label>
                                <input type="text" name="" id="">
                            </div>
                            <div class="input-div">
                                <label>Branch <span>*</span></label>
                                <select id="">
                                    <option disabled selected hidden>-- Choose AU Branch --</option>
                                    <option value="Juan Sumulong Campus (AU Legarda/Main)">Juan Sumulong Campus (AU Legarda/Main)</option>
                                    <option value="Jose Abad Santos Campus (AU Pasay)">Jose Abad Santos Campus (AU Pasay)</option>
                                    <option value="Arellano School of Law">Arellano School of Law</option>
                                    <option value="Andres Bonifacio Campus (AU Pasig)">Andres Bonifacio Campus (AU Pasig)</option>
                                    <option value="Jose Rizal Campus (AU Malabon)">Jose Rizal Campus (AU Malabon)</option>
                                    <option value="Plaridel Campus (AU Mandaluyong)">Plaridel Campus (AU Mandaluyong)</option>
                                    <option value="Apolinario Mabini Campus (AU Pasay)">Apolinario Mabini Campus (AU Pasay)</option>
                                    <option value="Elisa Esguerra Campus (AU Malabon)">Elisa Esguerra Campus (AU Malabon)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="btn-div">
                        <button type="submit" name="btnadd" class="add-btn">
                            Save
                        </button>
                        <a href="./equipment-management.php" class="cancel-btn">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
                
        </div>
    </main>
</body>
</html>