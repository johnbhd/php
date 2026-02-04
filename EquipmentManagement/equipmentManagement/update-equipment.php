<?php
require_once("../config/config.php");
include("../auth/session-checker.php");

$values = [];
$errors = [];

$assetNumber = $_GET["asset_number"];

$sql = "SELECT * FROM tblequipments WHERE asset_number = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $assetNumber);

if (mysqli_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $equipment = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Error fetch equipment data!'
    ];
}

if (isset($_POST['btnupdate'])) {

    $values = [
        'serialNumber'  => trim($_POST['serialNumber'] ?? ''),
        'equipmentType' => trim($_POST['equipmentType'] ?? ''),
        'manufacturer'  => trim($_POST['manufacturer'] ?? ''),
        'yearModel'     => trim($_POST['yearModel'] ?? ''),
        'description'   => trim($_POST['description'] ?? ''),
        'AUBranch'      => trim($_POST['AUBranch'] ?? ''),
        'AUDepartment'  => trim($_POST['AUDepartment'] ?? ''),
        'status'        => $_POST['rbstatus'] ?? ''
    ];

    if (empty($values['serialNumber'])) {
        $errors['serialNumber'] = "Serial Number is required.";
    }
    
    if (empty($values['manufacturer'])) {
        $errors['manufacturer'] = "Manufacturer is required.";
    }
    if (empty($values['yearModel'])) {
        $errors['yearModel'] = "Year Model is required.";
    } elseif (!is_numeric($values['yearModel'])) {
        $errors['yearModel'] = "Must be numeric";
    } elseif (!preg_match('/^\d{4}$/', $values['yearModel'])) {
        $errors['yearModel'] = "Year Model must be exactly 4 digits.";
    }
    

    $checkSql = "SELECT * FROM tblequipments WHERE serial_number = ? AND asset_number != ?";
    $stmtCheck = mysqli_prepare($link, $checkSql);
    mysqli_stmt_bind_param($stmtCheck, "ss", $values['serialNumber'], $assetNumber);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        $errors['serialNumber'] = "Serial Number already exists.";
    }

    if (empty($errors)) {

        $sql = "UPDATE tblequipments 
                SET serial_number=?, type=?, manufacturer=?, year_model=?, description=?, branch=?, department=?, status=? 
                WHERE asset_number=?";

        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssisssss",
            $values['serialNumber'],
            $values['equipmentType'],
            $values['manufacturer'],
            $values['yearModel'],
            $values['description'],
            $values['AUBranch'],
            $values['AUDepartment'],
            $values['status'],
            $assetNumber
        );

        if (mysqli_execute($stmt)) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Successful updated this equipment #' . $assetNumber . "!"
            ];
            header("location: ./equipment-management.php");
            exit();
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error updating this equipment #' . $assetNumber . "!"
            ];
        }
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
.input-div input[type = "text"], select, textarea {
    padding: 10px 10px;
    outline: none;
    border: 1px solid #CBD5E1;
    border-radius: 5px;
    font-size: 1rem;
}
.input-div input[type = "text"]:focus, select:focus, textarea:focus {
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
.update-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #1E293B;
}

.update-title .asset-number {
    font-size: 1.5rem;
    font-weight: 400;
    color: #3B82F6; 
    margin-left: 8px;
}

.rb {
    margin-top: 20px;
}
.rb-div {
    display: flex;
    gap: 20px;
    padding: 10px 0;
}
.rb-input {
    display: flex;
    gap: 10px;
}
.rb-input input {
    outline: none;
}
.rb-input label {
    font-size: 1.1rem;
}

@media screen and (max-width:  768px) {
    .col-div {
        display: flex;
        flex-direction: column;
    }   
}

.cancel-btn:hover {
    background-color: #111827;
    color: white;
}
.input-div input.error,
.input-div select.error {
    border-color: red;
}

.input-div input.success,
.input-div select.success {
    border-color: green;
}

.input-div label span {
    color: red;
    font-weight: bold;
}
.input-div label span.valid {
    color: green;
    visibility: hidden;
}
#optional {
    font-size: 1rem;
    color: black;
    font-weight: normal;
}
.labelInput {
    display: flex;
    gap: 5px;
    align-items: center;
}
.p-error {
    color:red; 
    font-size:0.9rem; 
    margin:0;

}
</style>
<body>
    
    <?php include("../includes/nav-sidebar.php");?>
    <div class="container">
            <h4 class="update-title">
                Update Equipment <span class="asset-number">#<?php echo $assetNumber ?></span>
            </h4>

            <div class="wrapper">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?asset_number=' . $assetNumber;?>" method="POST">
                    <div class="col-div">
                        <div class="col1-input">
                            <div class="input-div">
                                <label class="labelInput">
                                    Serial Number 
                                    <span class="<?= isset($errors['serialNumber']) ? '' : (!empty($values['serialNumber']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['serialNumber'] ?? '' ?></p>
                                </label>
                                <input type="text" name="serialNumber"
                                    value="<?= htmlspecialchars($values['serialNumber'] ?? $equipment['serial_number']) ?>"
                                    class="<?= isset($errors['serialNumber']) ? 'error' : (!empty($values['serialNumber']) ? 'success' : '') ?>">
                            </div>

                            <div class="input-div">
                               <label class="labelInput">
                                    Manufacturer <span class="<?= isset($errors['manufacturer']) ? '' : (!empty($values['manufacturer']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['manufacturer'] ?? '' ?></p>
                                </label>
                                <input type="text" name="manufacturer"
                                    value="<?= htmlspecialchars($values['manufacturer'] ?? $equipment['manufacturer']) ?>"
                                    class="<?= isset($errors['manufacturer']) ? 'error' : (!empty($values['manufacturer']) ? 'success' : '') ?>">  
                            </div>
                            
                            <div class="input-div">
                                <label>Description <span></span></label>
                                <textarea name="description"  ><?php echo $equipment["description"];?></textarea>
                            </div>
                     
                            <div class="input-div">
                                <label>Department <span></span></label>
                                <select name="AUDepartment" >
                                    <option value="<?php echo $equipment["department"];?>" selected hidden><?php echo $equipment["department"];?></option>
    
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
                        <!-- Col 2 -->
                        <div class="col2-input">
                     
                         
                            <div class="input-div">
                                <label>Equipment Type <span></span></label>
                                <select id="" name="equipmentType" >
                                    <option value="<?php echo $equipment["type"];?>" selected hidden><?php echo $equipment["type"];?></option>
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
                            <label class="labelInput">
                                Year Model 
                                <span class="<?= isset($errors['yearModel']) ? '' : (!empty($values['yearModel']) ? 'valid' : ''); ?>">*</span>
                                <p class="p-error"><?= $errors['yearModel'] ?? '' ?></p>
                            </label>
                            <input type="text" name="yearModel"
                                value="<?= htmlspecialchars($values['yearModel'] ?? $equipment['year_model']) ?>"
                                class="<?= isset($errors['yearModel']) ? 'error' : (!empty($values['yearModel']) ? 'success' : '') ?>">
                        </div>

                            <div class="input-div" >
                                <label>Branch <span></span></label>
                                <select name="AUBranch" >
                                    <option value="<?php echo $equipment["branch"];?>" selected hidden><?php echo $equipment["branch"];?></option>
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
                            <div class="input-div rb">
                                <label>Equipment Status <span></span></label>
                                <div class="rb-div">
                                    <div class="rb-input">
                                        <input type="radio" name="rbstatus" value="WORKING" <?php
                                            if ($equipment['status'] === "WORKING") echo "checked";
                                        ?>>    
                                        <label>Working</label>
                                    </div>
                                    <div class="rb-input">
                                        <input type="radio" name="rbstatus" value="ON-REPAIR" <?php
                                            if ($equipment['status']== "ON-REPAIR") echo "checked";
                                        ?>>                                   
                                        <label>On Repair</label>
                                    </div>
                                    <div class="rb-input">
                                        <input type="radio" name="rbstatus" value="RETIRED" <?php
                                            if ($equipment['status'] === "RETIRED") echo "checked";
                                        ?>>
                                        <label>Retired</label>
                                    </div>

                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="btn-div">
                        <button type="submit" name="btnupdate" class="add-btn">
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
<script>
const formFields = document.querySelectorAll('form input[type="text"], form select');

formFields.forEach(field => {
    const span = field.closest('.input-div')?.querySelector('span');
    const errorP = field.closest('.input-div')?.querySelector('.p-error'); 

    function validateField() {
        const value = field.value.trim();
        let isValid = true;

        if (field.name === 'yearModel') {
            if (!/^\d{4}$/.test(value)) {
                isValid = false;
                if (errorP) errorP.textContent = "Must be exactly 4 digits"; 
            } else {
                if (errorP) errorP.textContent = "";
            }
        } else {
            if (value === '') {
                isValid = false;
                if (errorP) errorP.textContent = "This field is required";
            } else {
                if (errorP) errorP.textContent = "";
            }
        }

        if (isValid) {
            field.classList.add('success');     
            field.classList.remove('error');     
            if (span) span.classList.add('valid');
        } else {
            field.classList.remove('success');
            field.classList.add('error');   
            if (span) span.classList.remove('valid'); 
        }
    }

    field.addEventListener('input', validateField);
    field.addEventListener('change', validateField);
});
</script>

</html>