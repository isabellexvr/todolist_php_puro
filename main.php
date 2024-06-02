<?php
    include("db.php");
    session_start();
    $userId = $_SESSION["id"];
    $userName = $_SESSION["name"];
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
    <header>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <input type="submit" value="logout" name="logout">
        </form>
        <hr>
    </header>

    <h1>Nova Tarefa:</h1>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

        <label for="name">Título</label>
        <input type="text" name="name">

        <label for="description">Descrição</label>
        <input type="text" name="description">

        <label for="datetime">Será completada até:</label>
        <input type="datetime-local" id="datetime" name="datetime">

        <input type="submit" value="Nova Tarefa" name="new_task">

    </form>

    <?php
        
        //echo $userId;
        $userTasks = "SELECT * FROM tasks WHERE userId = '$userId'";
        try{
            $result = mysqli_query($conn, $userTasks);

            if (mysqli_num_rows($result) > 0) {
                echo "
                    <h1>Tarefas do usuário:</h1>
                        <ul>
                    ";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                            <li>
                                <h2>Título: {$row["name"]}</h2>
                                <h3>Data de início: {$row["startsAt"]}</h3>
                                <h3>Completar até: {$row["endsAt"]}</h3>
                                <p>Descrição: {$row["description"]}</p>
                                
                                <form action='main.php' method='post'>
                                    <input type='hidden' name='taskId' value='{$row["id"]}'>
                                    <input type='submit' value='Deletar' name='delete'>
                                </form>
                            </li>
                        ";
                }


                echo "</ul>";
            } else {
                echo "Você ainda não possui tarefas";
            }
        }catch(mysqli_sql_exception){
            "não foi possível trazer as tarefas do banco de dados.";
        }

    ?>

</body>

</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
        $_SESSION = array();
        session_destroy();
        header("Location: login.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_task"])) {
        $date = filter_input(INPUT_POST, "datetime", FILTER_SANITIZE_SPECIAL_CHARS);

        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
        $datetime = str_replace('T', ' ', $date) . ':00'; //conversao pro formato do banco de dados

        if (!empty($name) && !empty($description) && !empty($datetime)) {
            $sql = "INSERT INTO tasks (name, description, endsAt, userId) VALUES ('$name', '$description', '$datetime', '$userId');";

            try {
                mysqli_query($conn, $sql);
                echo "<script type='text/javascript'>alert('Tarefa criada com sucesso.');</script>";
                header("Location: main.php");
            } catch (mysqli_sql_exception) {
                echo "<script type='text/javascript'>alert('Não foi possível criar a tarefa.');</script>";
            }
        }


        //echo $datetime;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $taskId = $_POST['taskId'];

        $deleteTask = "DELETE FROM tasks WHERE id = $taskId";

        try{
            mysqli_query($conn, $deleteTask);
            echo "<script type='text/javascript'>alert('Tarefa deletada com sucesso.');</script>";
            header("Location: main.php");

        }catch(mysqli_sql_exception){
            echo "<script type='text/javascript'>alert('Não foi possível deletar a tarefa.');</script>";
        }
    
    }



?>