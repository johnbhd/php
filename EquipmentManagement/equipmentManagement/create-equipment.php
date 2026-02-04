<?php
require_once("../config/config.php");
include("../auth/session-checker.php");

if (isset($_POST['btnadd'])) {
   $assetNumber = $_POST['assetNumber'];
   $serialNumber = $_POST['serialNumber'];
   $equipmentType = $_POST['equipmentType'];
   $manufacturer = $_POST['manufacturer'];
   $yearModel = $_POST['yearModel'];
   $description = $_POST['description'];
   $AUBranch = $_POST['AUBranch'];
   $AUDepartment = $_POST['AUDepartment'];
   $status = "WORKING";
   $createdBy = $_SESSION['username'];

   $sql = "INSERT INTO tblequipments (asset_number, serial_number, type, manufacturer, year_model, description, branch, department, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";

   $stmt = mysqli_prepare($link, $sql);
   mysqli_stmt_bind_param($stmt, "ssssisssss", $assetNumber, $serialNumber, $equipmentType, $manufacturer, $yearModel, $description, $AUBranch, $AUDepartment, $status, $createdBy);

   if (mysqli_execute($stmt)) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Successful created new equipments!'
        ];
        header("location: ./equipment-management.php");
        exit();
   } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error creating new equipments!'
        ];
   }

}


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
.input-div textarea {
    resize: vertical;
    min-height: 50px;
    box-sizing: border-box;
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
            <h4>Add Equipment</h4>
            <div class="wrapper">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
                    <div class="col-div">
                        <div class="col1-input">
                            <div class="input-div">
                                <label>Asset Number <span>*</span></label>
                                <input type="text" name="assetNumber" required>
                            </div>
                            <div class="input-div">
                                <label>Equipment Type <span>*</span></label>
                                <select id="" name="equipmentType" required>
                                    <option value="" disabled selected hidden>Choose equipment type</option>
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
                                <input type="text" name="yearModel" required>
                            </div>
                            <div class="input-div" style="margin-top: 18px;">
                                <label>Branch <span>*</span></label>
                                <select name="AUBranch" required>
                                    <option value="" disabled selected hidden>-- Choose AU Branch --</option>
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

                        <!-- Col 2 -->
                        <div class="col2-input">
                            <div class="input-div">
                                <label>Serial Number <span>*</span></label>
                                <input type="text" name="serialNumber">
                            </div>
                            <div class="input-div">
                                <label>Manufacturer <span>*</span></label>
                                <input type="text" name="manufacturer">
                            </div>
                            <div class="input-div">
                                <label>Description <span>*</span></label>
                                <textarea name="description" required></textarea>
                            </div>
                            
                            <div class="input-div">
                                <label>Department <span>*</span></label>
                                <select name="AUDepartment" required>
                                    <option value="" disabled selected hidden>-- Select Department --</option>

                                    <optgroup label="Colleges / Schools">
                                        <option value="College of Arts and Sciences">College of Arts and Sciences</option>
                                        <option value="College of Criminal Justice Education">College of Criminal Justice Education</option>
                                        <option value="Institute of Accountancy">Institute of Accountancy</option>
                                        <option value="School of Computer Science / Information Technology">School of Computer Science / Information Technology</option>
                                        <option value="School of Business and Administration">School of Business and Administration</option>
                                        <option value="School of Education">School of Education</option>
                                        <option value="Allied Medical Programs">Allied Medical Programs</option>
                                        <option value="School of Hospitality and Tourism Management">School of Hospitality and Tourism Management</option>
                                        <option value="Graduate School of Education">Graduate School of Education</option>
                                        <option value="Graduate School of Nursing">Graduate School of Nursing</option>
                                        <option value="Graduate School of Business">Graduate School of Business</option>
                                        <option value="College of Nursing">College of Nursing</option>
                                        <option value="College of Medical Laboratory Science">College of Medical Laboratory Science</option>
                                        <option value="School of Midwifery">School of Midwifery</option>
                                        <option value="College of Information Technology">College of Information Technology</option>
                                        <option value="School of Business Technology">School of Business Technology</option>
                                    </optgroup>

                                    <optgroup label="Basic Education">
                                        <option value="Pre-School">Pre-School</option>
                                        <option value="Kindergarten">Kindergarten</option>
                                        <option value="Elementary">Elementary</option>
                                        <option value="Junior High School">Junior High School</option>
                                        <option value="Senior High School">Senior High School</option>
                                    </optgroup>

                                    <optgroup label="Administrative Offices">
                                        <option value="Office of the President">Office of the President</option>
                                        <option value="Office of the Vice President for Academic Affairs">Office of the Vice President for Academic Affairs</option>
                                        <option value="Office of the Vice President for Administration">Office of the Vice President for Administration</option>
                                        <option value="Registrar’s Office">Registrar’s Office</option>
                                        <option value="Accounting Office">Accounting Office</option>
                                        <option value="Finance Office">Finance Office</option>
                                        <option value="Human Resource Office">Human Resource Office</option>
                                        <option value="Information Technology Office">Information Technology Office</option>
                                        <option value="Library">Library</option>
                                        <option value="Guidance Office">Guidance Office</option>
                                        <option value="Admissions Office">Admissions Office</option>
                                        <option value="Scholarship Office">Scholarship Office</option>
                                        <option value="Research Office">Research Office</option>
                                        <option value="Community Extension Office">Community Extension Office</option>
                                        <option value="Security Office">Security Office</option>
                                        <option value="Maintenance Office">Maintenance Office</option>
                                        <option value="Procurement Office">Procurement Office</option>
                                        <option value="Property Office">Property Office</option>
                                        <option value="Quality Assurance Office">Quality Assurance Office</option>
                                        <option value="Alumni Office">Alumni Office</option>
                                        <option value="Public Affairs Office">Public Affairs Office</option>
                                        <option value="Clinic / Health Services">Clinic / Health Services</option>
                                    </optgroup>
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