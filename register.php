<?php
include("db.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        form {
            display: flex;
            flex-direction: column;
        }
    </style>
    <title>Document</title>
</head>

<body>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

        <label for="name">Name</label>
        <input type="text" name="name">

        <label for="email">E-mail</label>
        <input type="text" name="email">

        <label for="password">Password</label>
        <input type="text" name="password">

        <input type="submit" value="register" name="register">
        <a href="login.php">Fazer Login</a>

    </form>
</body>

</html>

<?php

//nao cadastrar 2 users c mesmo email

    if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        $findUserByEmail = "SELECT * from users WHERE email = '$email'";
        $userFound = mysqli_query($conn, $findUserByEmail);

        if (mysqli_num_rows($userFound) > 0) {
            echo "this email is already registered.";
/* 
            $row = mysqli_fetch_assoc($userFound);
            echo $row["id"] . "<br>"; */

            //for multiple rows:

/*                 while($row = mysqli_fetch_assoc($userFound)){
                    echo $row["name"] . "<br>";
                } */

        } else {

            if (!empty($name) && !empty($email) && !empty($password)) {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword');";

                try {
                    mysqli_query($conn, $sql);
                    $_SESSION["username"] = $name;
                    echo "user registered";
                    header("Location: login.php");
                } catch (mysqli_sql_exception) {
                    echo "couldnt register";
                }

                mysqli_close($conn);
            }
        }
    }

?>