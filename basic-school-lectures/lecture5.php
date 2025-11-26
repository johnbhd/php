<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture 5: HTML FORM PROCESSING USING POST METHOD</title>
</head>
<body style="background-color: yellow;">
    <h1>
        <font color="red">Program 5</font>
    </h1>
    <h3>
        <center><?php echo "<font color='blue'> Description: This program show how to process input data using post method in php.</font>"?></center>
    </h3>
    <form method="POST">
        Input Number:
        <input type="text" name="txtinput1"><br>
        Input Number:
        <input type="text" name="txtinput2"><br>
        <input type="submit" name="btnadd" value="Add">
        <input type="submit" name="btnsubtract" value="Subtract">
        <input type="submit" name="btnmultiply" value="Multiply">
        <input type="submit" name="btndivide" value="Divide">
        
        <input type="submit" name="btnall" value="Compute All">
    </form>
    <br>
</body>
</html>
<?php
function validateInput1() {
    $errorsinput1 = 0;

    if (empty(($_POST['txtinput1']))) {
        echo '<p style = "color: red;">First number is  empty</p>';
        $errorsinput1 ++;
    } else if (!is_numeric(($_POST['txtinput1']))) {
        echo '<p style = "color: red;">First number is not a number</p>';
        $errorsinput1 ++;
    } else {
        $errorsinput1 = 0;
    }
    return $errorsinput1;
}
function validateInput2() {
   $errorsinput2 = 0;
   if (empty(($_POST['txtinput2']))) {
        echo '<p style = "color: red;">Second number is  empty</p>';
        $errorsinput2 ++;
    } else if (!is_numeric(($_POST['txtinput2']))) {
        echo '<p style = "color: red;">Second number is not a number</p>';
        $errorsinput2 ++;
    } else {
        $errorsinput2 = 0;
    }
    return $errorsinput2;
}
if (isset($_POST['btnadd'])) {
    $errors = 0;
    $errors += validateInput1();
    $errors += validateInput2();
    

    if ($errors == 0) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];
        $result = $num1 + $num2;
    
        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Sum: " .$result;
    }
}



if (isset($_POST['btnsubtract'])) {
    $errors = 0;
    $errors += validateInput1();
    $errors += validateInput2();
    
    if ($errors == 0) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $result = $num1 - $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Difference: " .$result;
    }
}
if (isset($_POST['btnmultiply'])) {
    $errors = 0;
    $errors += validateInput1();
    $errors += validateInput2();
    

    if ($errors == 0) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $result = $num1 * $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Product: " .$result;
    }
}
if (isset($_POST['btndivide'])) {
        $errors = 0;
        $errors += validateInput1();
        $errors += validateInput2();
        

        if ($errors == 0) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $result = $num1 / $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Quotient: " .$result;
    }
}

if (isset($_POST['btnall'])) {
    $errors = 0;
    $errors += validateInput1();
    $errors += validateInput2();
    

    if ($errors == 0) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $sum = $num1 + $num2;
        $diff = $num1 - $num2;
        $prod = $num1 * $num2;
        $quo = $num1 / $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br><br>Compute All <br>Sum: " .$sum."<br>Difference: ".$diff."<br>Product: :".$prod."<br>Quotient: ".$quo;
    }
}




?>
