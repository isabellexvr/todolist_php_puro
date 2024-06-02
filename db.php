<?php 
    $dbServer = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "todolist";
    $conn = "";

    try{
        $conn = mysqli_connect($dbServer, $dbUser, $dbPass, $dbName); //bool

        echo "connected";
    }catch(mysqli_sql_exception){
        echo "could not connect";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>to do list</title>
</head>
<body>

    <form>

    
    </form>
    
</body>
</html>