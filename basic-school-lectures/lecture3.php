
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture 3: HTML FORM PROCESSING USING POST METHOD</title>
</head>
<body style="background-color: yellow;">
    <h1>
        <font color="red">Program 3</font>
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
    if (isset($_POST['btnadd'])) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $result = $num1 + $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Sum: " .$result;
    }
    if (isset($_POST['btnsubtract'])) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $result = $num1 - $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Difference: " .$result;
    }
    if (isset($_POST['btnmultiply'])) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $result = $num1 * $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Product: " .$result;
    }
    if (isset($_POST['btndivideresult'])) {
        $num1 = $_POST['txtinput1'];
        $num2 = $_POST['txtinput2'];

        $result = $num1 / $num2;

        echo "First number: " .$num1."<br>Second number: " .$num2."<br>Quotient: " .$result;
    }

?>
