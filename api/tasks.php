<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: ../index.php');
    exit();
}
require_once "../connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno!=0) {
    echo "Error: ".$polaczenie->connect_errno;
} else {
    $user_id = $_SESSION['id'];
    
    if(isset($_GET['task_id'])){
        $task_id = $_GET['task_id'];
        get($polaczenie,$user_id,$task_id);
    }
    else
    {
        get_all($polaczenie,$user_id);
    }

    $polaczenie->close();
}





function get_all($polaczenie,$user_id)
{
    $user_id = htmlentities($user_id, ENT_QUOTES, "UTF-8");
    if ($rezultat = @$polaczenie->query(
        sprintf("SELECT * FROM tasks WHERE user_id='%s'",
        mysqli_real_escape_string($polaczenie, $user_id),
    )))
    {
        $tasks = array();
        $ile_zadan =  $rezultat->num_rows;
        if ($ile_zadan >0) {
            for ($i = 0; $i < $ile_zadan; $i++) {
                $wiersz = $rezultat->fetch_assoc();
                $tasks[$i] = $wiersz;
            }
        }
    
        header('Content-Type: application/json');
        echo json_encode($tasks);
    }
}

function get($polaczenie,$user_id,$task_id)
{
    $user_id = htmlentities($user_id, ENT_QUOTES, "UTF-8");
    $task_id = htmlentities($task_id, ENT_QUOTES, "UTF-8");

    if ($rezultat = @$polaczenie->query(
        sprintf("SELECT * FROM tasks WHERE user_id='%s' AND id='%s' ",
        mysqli_real_escape_string($polaczenie,$user_id),
        mysqli_real_escape_string($polaczenie,$task_id),
    )))
    {
        $ile_zadan =  $rezultat->num_rows;
        if ($ile_zadan >0) {
            $task =  $rezultat->fetch_assoc();
            header('Content-Type: application/json');
            echo json_encode($task);
        }
        else
        {
             http_response_code(400);
             die('invalid task_id');
        }
    }
}

?>