<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture 2: Variable Declaration</title>
</head>
<body style="background-color: yellow;">
    <h1>
        <font color="red">Program 2</font>
    </h1>
    <h3>
        <center><?php echo "<font color='blue'> Description: This program show on how to declare variable in php.</font>"?></center>
    </h3>
    <font color="blue">
        <?php
            $num = 10;
            $pi = 3.1416;
            $letter = 'a';
            $msg = "Message";
            $result = true;

            echo "Variable num contains ". $num;
            echo "<br>Variable num pi contains ". $pi;
            echo "<br>Variable letter contains ". $letter ."<br>Variable message contains ". $msg . "<br>Variable result contains ". $result;
            
        ?>
    </font>
    
</body>
</html>