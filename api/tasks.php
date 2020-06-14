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
    
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $task = array(
            "id" => $_PUT['id'],
            "name" => $_PUT['name'],
            "status" => $_PUT['status'],
            "due_date" => $_PUT['due_date']
        );
        update($polaczenie,$user_id,$task);
    }

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    }

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

function update($polaczenie,$user_id,$task)
{
    $user_id = htmlentities($user_id, ENT_QUOTES, "UTF-8");
    $task_id = htmlentities($task['id'], ENT_QUOTES, "UTF-8");
    $name = htmlentities($task['name'], ENT_QUOTES, "UTF-8");
    $status = htmlentities($task['status'], ENT_QUOTES, "UTF-8");
    $due_date = htmlentities($task['due_date'], ENT_QUOTES, "UTF-8");


    if ($rezultat = @$polaczenie->query(
        sprintf("UPDATE tasks 
                    SET name='%s', status='%s', due_date='%s'
                  WHERE user_id='%s' AND id='%s'",
            mysqli_real_escape_string($polaczenie,$name),
            mysqli_real_escape_string($polaczenie,$status),
            mysqli_real_escape_string($polaczenie,$due_date),
            mysqli_real_escape_string($polaczenie,$user_id),
            mysqli_real_escape_string($polaczenie,$task_id)
    )))
    {
        if($rezultat === TRUE)
        {
            get($polaczenie,$user_id,$task['id']);
        }
        else
        {
            http_response_code(400);
            die('error');
        }
    }
}
?>