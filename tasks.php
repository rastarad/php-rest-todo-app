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
    <title>Web Task App</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- Material Design Bootstrap -->
    <link href="https://z9t4u9f6.stackpathcdn.com/wp-content/themes/mdbootstrap4/css/compiled-4.19.0.min.css?ver=4.19.0"
        rel="stylesheet" />

</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <div class="card-header info-color white-text py-4">
                    <h5 class="text-right"><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></h5>
                    <h3 class="text-center">
                        <strong>Task List</strong>
                    </h3>
                </div>

                <div class="card-body px-lg-5 pt-0">
                    <div class="form-row justify-content-between">
                        <div class="col-10">
                            <div class="md-form">
                                <input type="text" name="task" class="form-control" id="new-task-name" />
                                <label for="new-task-name">New task</label>
                            </div>
                        </div>
                        <div class="align-self-center justify-content-end">
                            <button class="btn btn-primary" id="new-task-button"> Add task
                            </button>
                        </div>
                    </div>

                    <div id="todo-list">
                        <?php
                            foreach ($tasks as $task) {
                                $id = $task['id'];
                                $name = $task['name'];
                                $due_date = $task['due_date'];
                                $is_task_done = $task['status']==1;
                                $task_class_name = $is_task_done ? "task-done" : "task-todo";

                                echo '<div class="todo-item md-form input-group mb-3">';
                                    echo '<input type="hidden" name="id" id="task_'.$id.'_id_input" value="'.$id.'">';
                                    echo '<input type="hidden" name="due_date" id="task_'.$id.'_due_date_input" value="'.$due_date.'">';

                                    echo '<div class="input-group-prepend">';
                                        echo '<div class="input-group-text md-addon">';
                                            echo '<input class="form-check-input todo-item-status-input" type="checkbox" name="status" id="task_'.$id.'_status_input" '.($is_task_done ? "checked":'').' value="">';
                                            echo '<label class="form-check-label" for="task_'.$id.'_status_input">';
                                        echo '</div>';
                                    echo '</div>';                                  
                                    
                                    echo '<input type="text" name="name" class="todo-item-name-input form-control" id="task_'.$id.'_name_input"value="'.$name.'">';
                                    
                                    echo '<div class="input-group-append">';
                                        echo '<button class="btn btn-link todo-item-delete-button m-0 px-3"><i class="fas fa-times"></i></button>';
                                    echo '</div>';                                  
                                
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js">
    </script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript"
        src="https://z9t4u9f6.stackpathcdn.com/wp-content/themes/mdbootstrap4/js/bundles/4.19.0/compiled.0.min.js?ver=4.19.0">
    </script>

    <script src="tasks.js"></script>

    <script>
    function onDeleteButtonClick() {
        const button = $(this)
        const taskData = getTaskData(button);

        function afterDelete() {
            button.parents(".todo-item").remove();
        }

        deleteTask(taskData.id, afterDelete);
    }

    $(".todo-item-status-input").click(function() {
        changeStatus(this);
    });
    $(".todo-item-name-input").change(function() {
        changeStatus(this);
    });

    $(".todo-item-delete-button").click(onDeleteButtonClick);

    $("#new-task-button").click(function() {
        const input = $("#new-task-name")[0]
        const name = input.value;

        function afterAdd(data) {
            const id = data.id;
            const name = data.name;
            const due_date = "2021-06-13 21:45:24"; // Date.now();
            $("#new-task-name").val('');

            let taskDiv = $('<div class="todo-item md-form input-group mb-3"></div>');

            let idInput = $('<input type="hidden" name="id" id="task_' + id + '_id_input" value="' + id +
                '">');
            let dueDateInput = $('<input type="hidden" name="due_date" id="task_' + id +
                '_due_date_input" value="' + due_date + '">');

            let checkboxDiv = $('<div class="input-group-prepend"></div>');
            let statusDiv = $('<div class="input-group-text md-addon"></div>');
            let statusInput = $(
                '<input class="form-check-input todo-item-status-input" type="checkbox" name="status" id="task_' +
                id + '_status_input" value="">');
            statusInput.change(function() {
                changeStatus(this);
            });
            let statusLabel = $('<label class="form-check-label" for="task_' + id + '_status_input">');

            statusDiv.append(statusInput);
            statusDiv.append(statusLabel);
            checkboxDiv.append(statusDiv);

            let nameInput = $(
                '<input type="text" name="name" class="todo-item-name-input form-control" id="task_' +
                id +
                '_name_input"value="' + name + '">');
            nameInput.change(function() {
                changeStatus(this);
            });

            let deleteButtonDiv = $('<div class="input-group-append"></div>');
            let deleteButton = $(
                '<button class="btn btn-link todo-item-delete-button m-0 px-3"><i class="fas fa-times"></i></button>'
            );
            deleteButton.click(onDeleteButtonClick);
            deleteButtonDiv.append(deleteButton);

            taskDiv.append(idInput);
            taskDiv.append(dueDateInput);
            taskDiv.append(checkboxDiv);
            taskDiv.append(nameInput);
            taskDiv.append(deleteButtonDiv);

            $("#todo-list").append(taskDiv);
        }

        addTask(name, afterAdd);
    });
    </script>

</body>

</html>