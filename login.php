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

        <label for="email">E-mail</label>
        <input type="text" name="email">

        <label for="password">Password</label>
        <input type="text" name="password">

        <input type="submit" value="login" name="login">
        <a href="register.php">Registrar</a>

    </form>
</body>

</html>

<?php 

//salvar nome do user em cookies ou em session

        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])){
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

            if(!empty($email) && !empty($password)){

                $findUserByEmail = "SELECT * from users WHERE email = '$email'";
                $userFound = mysqli_query($conn, $findUserByEmail);

                if($userFound){
                    $row = mysqli_fetch_assoc($userFound);
                    $userPassword = $row["password"];
                    $isPasswordCorrect = password_verify($password, $userPassword);

                    if($isPasswordCorrect){
                        $_SESSION["email"] = $row["email"];
                        $_SESSION["id"] = $row["id"];
                        $_SESSION["name"] = $row["name"];
                        echo "user logged";
                        header("Location: main.php");
                    }else{
                        echo "wrong password";
                    }

                }

                mysqli_close($conn);

            }
        }

?>