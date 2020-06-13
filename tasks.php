<?php


session_start();

if(!isset($_SESSION['zalogowany']))
{
    header('Location: index.php');
    exit();   
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno!=0)
{
echo "Error: ".$polaczenie->connect_errno;
}
else
{
$user_id = $_SESSION['id'];

$user_id = htmlentities($user_id, ENT_QUOTES, "UTF-8");

if($rezultat = @$polaczenie->query(
    sprintf("SELECT * FROM tasks WHERE user_id='%s'",
    mysqli_real_escape_string($polaczenie,$user_id),
    )))
{
    $tasks = array();
    $ile_zadan =  $rezultat->num_rows;
    if($ile_zadan >0)
    {
        $_SESSION['zalogowany']= true;

        for($i = 0; $i < $ile_zadan; $i++ ){
            $wiersz = $rezultat->fetch_assoc();
            $tasks[$i] = $wiersz;
        }
    }
}

$polaczenie->close();
}






?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous" />
    <title>Web Task App</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div id="main" class="card">
                    <div class="card-content">
                        <span class="card-title">Task List</span>
                        <div class="row">
                            <form id="task-form">
                                <div class="input-field col s12">
                                    <input type="text" name="task" id="task" />
                                    <label for="task">New task</label>
                                </div>
                                <input type="submit" value="Add Task" class="btn" />
                            </form>
                        </div>
                    </div>
                    <div class="card-action">
                        <h5 id="task-title">Task</h5>
                        <div class="input-field col s12">
                            <input type="text" name="filter" id="filter" />
                            <label for="task">Filter task</label>
                        </div>


                        <ul class="collection">
                            <?php

                        foreach ($tasks as $task) {
                            $name = $task['name'];
                            $is_task_done = $task['status']==1;
                            $task_class_name = $is_task_done?"task-done":"task-todo";
                            echo "<li class='collection-item $task_class_name'>";
                                echo $is_task_done?'<i class="fa fa-check-square-o"></i>':'<i class="fa fa-square-o"></i>';
                                echo $name;
                                echo '<a class = "delete-item secondary-content">';
                                echo '<i class="fa fa-remove"></i>';
                                echo '</a>';
                            echo '</li>';
                        }
                ?>



                        </ul>
                        <a href="#" class="clear-tasks btn black">Clear all tasks</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>

</html>