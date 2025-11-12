
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture 4: HTML FORM PROCESSING USING GET METHOD</title>
    <link rel="shortcut icon" href="Jollibee.jpg" type="image/x-icon">
</head>
<body style="background-image: url('Jollibee.jpg');">
    <h1>
        <font color="white" size="30">Program 4</font>
    </h1>
    <h3 style="font-size: 80px;">
        <center><?php echo "<font color='red'> Description: This program show how to process input data using post method in php.</font>"?></center>
    </h3>
    <font color="white" size="20">
        <form method="GET" >
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

    </font>
    <br>
</body>
</html>
<font color="white" size="20">
    <?php
        if (isset($_GET['btnadd'])) {
            $num1 = $_GET['txtinput1'];
            $num2 = $_GET['txtinput2'];

            $result = $num1. $num2;

            echo "First number: " .$num1."<br>Second number: " .$num2."<br>Sum: " .$result;
        }
        if (isset($_GET['btnsubtract'])) {
            $num1 = $_GET['txtinput1'];
            $num2 = $_GET['txtinput2'];

            $result = $num1 - $num2;

            echo "First number: " .$num1."<br>Second number: " .$num2."<br>Difference: " .$result;
        }
        if (isset($_GET['btnmultiply'])) {
            $num1 = $_GET['txtinput1'];
            $num2 = $_GET['txtinput2'];

            $result = $num1 * $num2;

            echo "First number: " .$num1."<br>Second number: " .$num2."<br>Product: " .$result;
        }
        if (isset($_GET['btndivideresult'])) {
            $num1 = $_GET['txtinput1'];
            $num2 = $_GET['txtinput2'];

            $result = $num1 / $num2;

            echo "First number: " .$num1."<br>Second number: " .$num2."<br>Quotient: " .$result;
        }

    ?>

</font>
