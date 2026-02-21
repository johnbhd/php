<?php
require_once("../config/config.php");
include("../auth/session-checker.php");


$valid = false;
$values = [];
$errors = [];

if (isset($_POST['btnadd'])) {
    $values = [
        'assetNumber'   => trim($_POST['assetNumber'] ?? ''),
        'serialNumber'  => trim($_POST['serialNumber'] ?? ''),
        'equipmentType' => trim($_POST['equipmentType'] ?? ''),
        'manufacturer'  => trim($_POST['manufacturer'] ?? ''),
        'yearModel'     => trim($_POST['yearModel'] ?? ''),
        'AUBranch'      => trim($_POST['AUBranch'] ?? ''),
        'AUDepartment'  => trim($_POST['AUDepartment'] ?? '')
    ];

    if (empty($values['assetNumber'])) {
        $errors['assetNumber'] = "Asset Number is required.";
    }
    if (empty($values['serialNumber'])) {
        $errors['serialNumber'] = "Serial Number is required.";
    }
    if (empty($values['equipmentType'])) {
        $errors['equipmentType'] = "Equipment Type is required.";
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
    if (empty($values['AUBranch'])) {
        $errors['AUBranch'] = "Branch is required.";
    }
    if (empty($values['AUDepartment'])) {
        $errors['AUDepartment'] = "Department is required.";
    }

    $checkSql = "SELECT * FROM tblequipments WHERE asset_number = ? OR serial_number = ?";
    $stmtCheck = mysqli_prepare($link, $checkSql);
    mysqli_stmt_bind_param($stmtCheck, "ss", $values['assetNumber'], $values['serialNumber']);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        while ($row = mysqli_fetch_assoc($resultCheck)) {
            if ($row['asset_number'] === $values['assetNumber']) {
                $errors['assetNumber'] = "Asset Number already exists.";
            }
            if ($row['serial_number'] === $values['serialNumber']) {
                $errors['serialNumber'] = "Serial Number already exists.";
            }
        }
    }

    if (empty($errors)) {

        $description = $_POST['description'];
        $status = "WORKING";
        $createdBy = $_SESSION['username'];
        
        $sql = "INSERT INTO tblequipments (asset_number, serial_number, type, manufacturer, year_model, description, branch, department, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
    
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ssssisssss", $values['assetNumber'], $values['serialNumber'], $values['equipmentType'], $values['manufacturer'], $values['yearModel'], $description, $values['AUBranch'], $values['AUDepartment'], $status, $createdBy);
    
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
                                <label class="labelInput">
                                    Asset Number 
                                    <span class="<?= isset($errors['assetNumber']) ? '' : (!empty($values['assetNumber']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['assetNumber'] ?? '' ?></p>
                                </label>

                                <input type="text" name="assetNumber" value="<?= htmlspecialchars($values['assetNumber'] ?? '') ?>"  class="<?php echo isset($errors['assetNumber']) ? 'error' : (!empty($values['assetNumber']) ? 'success' : ''); ?>">
                            </div>
                            <div class="input-div">
                                <label class="labelInput">
                                    Equipment Type
                                    <span class="<?= isset($errors['equipmentType']) ? '' : (!empty($values['equipmentType']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['equipmentType'] ?? '' ?></p>
                                </label>
                                <select name="equipmentType" class="<?= isset($errors['equipmentType']) ? 'error' : (!empty($values['equipmentType']) ? 'success' : ''); ?>">
                                    <option value="" disabled <?= empty($values['equipmentType']) ? 'selected' : '' ?>>Choose equipment type</option>
                                    <option value="Monitor" <?= ($values['equipmentType'] ?? '') === 'Monitor' ? 'selected' : '' ?>>Monitor</option>
                                    <option value="CPU" <?= ($values['equipmentType'] ?? '') === 'CPU' ? 'selected' : '' ?>>CPU</option>
                                    <option value="Keyboard" <?= ($values['equipmentType'] ?? '') === 'Keyboard' ? 'selected' : '' ?>>Keyboard</option>
                                    <option value="Mouse" <?= ($values['equipmentType'] ?? '') === 'Mouse' ? 'selected' : '' ?>>Mouse</option>
                                    <option value="AVR" <?= ($values['equipmentType'] ?? '') === 'AVR' ? 'selected' : '' ?>>AVR</option>
                                    <option value="MAC" <?= ($values['equipmentType'] ?? '') === 'MAC' ? 'selected' : '' ?>>MAC</option>
                                    <option value="Printer" <?= ($values['equipmentType'] ?? '') === 'Printer' ? 'selected' : '' ?>>Printer</option>
                                    <option value="Projector" <?= ($values['equipmentType'] ?? '') === 'Projector' ? 'selected' : '' ?>>Projector</option>
                                </select>
                            </div>
                            <div class="input-div">
                                <label class="labelInput">
                                    Year Model 
                                    <span class="<?= isset($errors['yearModel']) ? '' : (!empty($values['yearModel']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['yearModel'] ?? '' ?></p>
                                </label>                                
                                <input type="text" name="yearModel" value="<?= htmlspecialchars($values['yearModel'] ?? '') ?>"  class="<?php echo isset($errors['yearModel']) ? 'error' : (!empty($values['yearModel']) ? 'success' : ''); ?>">
                            </div>
                            <div class="input-div" style="margin-top: 18px;">
                                <label class="labelInput">
                                    Branch
                                    <span class="<?= isset($errors['AUBranch']) ? '' : (!empty($values['AUBranch']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['AUBranch'] ?? '' ?></p>
                                </label>
                                <select name="AUBranch" class="<?= isset($errors['AUBranch']) ? 'error' : (!empty($values['AUBranch']) ? 'success' : ''); ?>">
                                    <option value="" disabled <?= empty($values['AUBranch']) ? 'selected' : '' ?>>-- Choose AU Branch --</option>
                                    <option value="Juan Sumulong Campus (AU Legarda/Main)" <?= ($values['AUBranch'] ?? '') === 'Juan Sumulong Campus (AU Legarda/Main)' ? 'selected' : '' ?>>Juan Sumulong Campus (AU Legarda/Main)</option>
                                    <option value="Jose Abad Santos Campus (AU Pasay)" <?= ($values['AUBranch'] ?? '') === 'Jose Abad Santos Campus (AU Pasay)' ? 'selected' : '' ?>>Jose Abad Santos Campus (AU Pasay)</option>
                                    <option value="Arellano School of Law" <?= ($values['AUBranch'] ?? '') === 'Arellano School of Law' ? 'selected' : '' ?>>Arellano School of Law</option>
                                    <option value="Andres Bonifacio Campus (AU Pasig)" <?= ($values['AUBranch'] ?? '') === 'Andres Bonifacio Campus (AU Pasig)' ? 'selected' : '' ?>>Andres Bonifacio Campus (AU Pasig)</option>
                                    <option value="Jose Rizal Campus (AU Malabon)" <?= ($values['AUBranch'] ?? '') === 'Jose Rizal Campus (AU Malabon)' ? 'selected' : '' ?>>Jose Rizal Campus (AU Malabon)</option>
                                    <option value="Plaridel Campus (AU Mandaluyong)" <?= ($values['AUBranch'] ?? '') === 'Plaridel Campus (AU Mandaluyong)' ? 'selected' : '' ?>>Plaridel Campus (AU Mandaluyong)</option>
                                    <option value="Apolinario Mabini Campus (AU Pasay)" <?= ($values['AUBranch'] ?? '') === 'Apolinario Mabini Campus (AU Pasay)' ? 'selected' : '' ?>>Apolinario Mabini Campus (AU Pasay)</option>
                                    <option value="Elisa Esguerra Campus (AU Malabon)" <?= ($values['AUBranch'] ?? '') === 'Elisa Esguerra Campus (AU Malabon)' ? 'selected' : '' ?>>Elisa Esguerra Campus (AU Malabon)</option>
                                </select>

                            </div>
                        </div>

                        <!-- Col 2 -->
                        <div class="col2-input">
                            <div class="input-div">
                                 <label class="labelInput">
                                    Serial Number 
                                    <span class="<?= isset($errors['serialNumber']) ? '' : (!empty($values['serialNumber']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['serialNumber'] ?? '' ?></p>
                                </label>
                                <input type="text" name="serialNumber" value="<?= htmlspecialchars($values['serialNumber'] ?? '') ?>"  class="<?php echo isset($errors['serialNumber']) ? 'error' : (!empty($values['serialNumber']) ? 'success' : ''); ?>">
                            </div>
                            <div class="input-div">
                                 <label class="labelInput">
                                    Manufacturer 
                                    <span class="<?= isset($errors['manufacturer']) ? '' : (!empty($values['manufacturer']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['manufacturer'] ?? '' ?></p>
                                </label>
                                <input type="text" name="manufacturer" value="<?= htmlspecialchars($values['manufacturer'] ?? '') ?>" class="<?php echo isset($errors['manufacturer']) ? 'error' : (!empty($values['manufacturer']) ? 'success' : ''); ?>">
                            </div>
                            <div class="input-div">
                                <label>Description <span id="optional">(optional)</span></label>
                                <textarea name="description" ></textarea>
                            </div>
                            
                            <div class="input-div">
                                 <label class="labelInput">
                                    Department    
                                    <span class="<?= isset($errors['AUDepartment']) ? '' : (!empty($values['AUDepartment']) ? 'valid' : ''); ?>">*</span>
                                    <p class="p-error"><?= $errors['AUDepartment'] ?? '' ?></p>
                                </label>                                
                                <select name="AUDepartment" class="<?= isset($errors['AUDepartment']) ? 'error' : (!empty($values['AUDepartment']) ? 'success' : ''); ?>">
                                    <option value="" disabled <?= empty($values['AUDepartment']) ? 'selected' : '' ?>>-- Select Department --</option>

                                    <optgroup label="Colleges / Schools">
                                        <option value="College of Arts and Sciences" <?= ($values['AUDepartment'] ?? '') === 'College of Arts and Sciences' ? 'selected' : '' ?>>College of Arts and Sciences</option>
                                        <option value="College of Criminal Justice Education" <?= ($values['AUDepartment'] ?? '') === 'College of Criminal Justice Education' ? 'selected' : '' ?>>College of Criminal Justice Education</option>
                                        <option value="Institute of Accountancy" <?= ($values['AUDepartment'] ?? '') === 'Institute of Accountancy' ? 'selected' : '' ?>>Institute of Accountancy</option>
                                        <option value="School of Computer Science / Information Technology" <?= ($values['AUDepartment'] ?? '') === 'School of Computer Science / Information Technology' ? 'selected' : '' ?>>School of Computer Science / Information Technology</option>
                                        <option value="School of Business and Administration" <?= ($values['AUDepartment'] ?? '') === 'School of Business and Administration' ? 'selected' : '' ?>>School of Business and Administration</option>
                                        <option value="School of Education" <?= ($values['AUDepartment'] ?? '') === 'School of Education' ? 'selected' : '' ?>>School of Education</option>
                                        <option value="Allied Medical Programs" <?= ($values['AUDepartment'] ?? '') === 'Allied Medical Programs' ? 'selected' : '' ?>>Allied Medical Programs</option>
                                        <option value="School of Hospitality and Tourism Management" <?= ($values['AUDepartment'] ?? '') === 'School of Hospitality and Tourism Management' ? 'selected' : '' ?>>School of Hospitality and Tourism Management</option>
                                        <option value="Graduate School of Education" <?= ($values['AUDepartment'] ?? '') === 'Graduate School of Education' ? 'selected' : '' ?>>Graduate School of Education</option>
                                        <option value="Graduate School of Nursing" <?= ($values['AUDepartment'] ?? '') === 'Graduate School of Nursing' ? 'selected' : '' ?>>Graduate School of Nursing</option>
                                        <option value="Graduate School of Business" <?= ($values['AUDepartment'] ?? '') === 'Graduate School of Business' ? 'selected' : '' ?>>Graduate School of Business</option>
                                        <option value="College of Nursing" <?= ($values['AUDepartment'] ?? '') === 'College of Nursing' ? 'selected' : '' ?>>College of Nursing</option>
                                        <option value="College of Medical Laboratory Science" <?= ($values['AUDepartment'] ?? '') === 'College of Medical Laboratory Science' ? 'selected' : '' ?>>College of Medical Laboratory Science</option>
                                        <option value="School of Midwifery" <?= ($values['AUDepartment'] ?? '') === 'School of Midwifery' ? 'selected' : '' ?>>School of Midwifery</option>
                                        <option value="College of Information Technology" <?= ($values['AUDepartment'] ?? '') === 'College of Information Technology' ? 'selected' : '' ?>>College of Information Technology</option>
                                        <option value="School of Business Technology" <?= ($values['AUDepartment'] ?? '') === 'School of Business Technology' ? 'selected' : '' ?>>School of Business Technology</option>
                                    </optgroup>

                                    <optgroup label="Basic Education">
                                        <option value="Pre-School" <?= ($values['AUDepartment'] ?? '') === 'Pre-School' ? 'selected' : '' ?>>Pre-School</option>
                                        <option value="Kindergarten" <?= ($values['AUDepartment'] ?? '') === 'Kindergarten' ? 'selected' : '' ?>>Kindergarten</option>
                                        <option value="Elementary" <?= ($values['AUDepartment'] ?? '') === 'Elementary' ? 'selected' : '' ?>>Elementary</option>
                                        <option value="Junior High School" <?= ($values['AUDepartment'] ?? '') === 'Junior High School' ? 'selected' : '' ?>>Junior High School</option>
                                        <option value="Senior High School" <?= ($values['AUDepartment'] ?? '') === 'Senior High School' ? 'selected' : '' ?>>Senior High School</option>
                                    </optgroup>

                                    <optgroup label="Administrative Offices">
                                        <option value="Office of the President" <?= ($values['AUDepartment'] ?? '') === 'Office of the President' ? 'selected' : '' ?>>Office of the President</option>
                                        <option value="Office of the Vice President for Academic Affairs" <?= ($values['AUDepartment'] ?? '') === 'Office of the Vice President for Academic Affairs' ? 'selected' : '' ?>>Office of the Vice President for Academic Affairs</option>
                                        <option value="Office of the Vice President for Administration" <?= ($values['AUDepartment'] ?? '') === 'Office of the Vice President for Administration' ? 'selected' : '' ?>>Office of the Vice President for Administration</option>
                                        <option value="Registrar’s Office" <?= ($values['AUDepartment'] ?? '') === 'Registrar’s Office' ? 'selected' : '' ?>>Registrar’s Office</option>
                                        <option value="Accounting Office" <?= ($values['AUDepartment'] ?? '') === 'Accounting Office' ? 'selected' : '' ?>>Accounting Office</option>
                                        <option value="Finance Office" <?= ($values['AUDepartment'] ?? '') === 'Finance Office' ? 'selected' : '' ?>>Finance Office</option>
                                        <option value="Human Resource Office" <?= ($values['AUDepartment'] ?? '') === 'Human Resource Office' ? 'selected' : '' ?>>Human Resource Office</option>
                                        <option value="Information Technology Office" <?= ($values['AUDepartment'] ?? '') === 'Information Technology Office' ? 'selected' : '' ?>>Information Technology Office</option>
                                        <option value="Library" <?= ($values['AUDepartment'] ?? '') === 'Library' ? 'selected' : '' ?>>Library</option>
                                        <option value="Guidance Office" <?= ($values['AUDepartment'] ?? '') === 'Guidance Office' ? 'selected' : '' ?>>Guidance Office</option>
                                        <option value="Admissions Office" <?= ($values['AUDepartment'] ?? '') === 'Admissions Office' ? 'selected' : '' ?>>Admissions Office</option>
                                        <option value="Scholarship Office" <?= ($values['AUDepartment'] ?? '') === 'Scholarship Office' ? 'selected' : '' ?>>Scholarship Office</option>
                                        <option value="Research Office" <?= ($values['AUDepartment'] ?? '') === 'Research Office' ? 'selected' : '' ?>>Research Office</option>
                                        <option value="Community Extension Office" <?= ($values['AUDepartment'] ?? '') === 'Community Extension Office' ? 'selected' : '' ?>>Community Extension Office</option>
                                        <option value="Security Office" <?= ($values['AUDepartment'] ?? '') === 'Security Office' ? 'selected' : '' ?>>Security Office</option>
                                        <option value="Maintenance Office" <?= ($values['AUDepartment'] ?? '') === 'Maintenance Office' ? 'selected' : '' ?>>Maintenance Office</option>
                                        <option value="Procurement Office" <?= ($values['AUDepartment'] ?? '') === 'Procurement Office' ? 'selected' : '' ?>>Procurement Office</option>
                                        <option value="Property Office" <?= ($values['AUDepartment'] ?? '') === 'Property Office' ? 'selected' : '' ?>>Property Office</option>
                                        <option value="Quality Assurance Office" <?= ($values['AUDepartment'] ?? '') === 'Quality Assurance Office' ? 'selected' : '' ?>>Quality Assurance Office</option>
                                        <option value="Alumni Office" <?= ($values['AUDepartment'] ?? '') === 'Alumni Office' ? 'selected' : '' ?>>Alumni Office</option>
                                        <option value="Public Affairs Office" <?= ($values['AUDepartment'] ?? '') === 'Public Affairs Office' ? 'selected' : '' ?>>Public Affairs Office</option>
                                        <option value="Clinic / Health Services" <?= ($values['AUDepartment'] ?? '') === 'Clinic / Health Services' ? 'selected' : '' ?>>Clinic / Health Services</option>
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
<script>
const formFields = document.querySelectorAll('form input, form select');

formFields.forEach(field => {
    const span = field.closest('.input-div').querySelector('span');
    const errorP = field.closest('.input-div').querySelector('.p-error'); 

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