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
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" /> -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous" /> -->
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
                            <div class="input-field col s12">
                                <input type="text" name="task" id="new-task-name" />
                                <label for="task">New task</label>
                            </div>
                            <button class="btn" id="new-task-button">
                                Add task
                            </button>
                        </div>
                    </div>
                    <div class="card-action">
                        <h5 id="task-title">Task</h5>
                        <div class="input-field col s12">
                            <input type="text" name="filter" id="filter" />
                            <label for="task">Filter task</label>
                        </div>


                        <div id="todo-list">
                            <?php
                            foreach ($tasks as $task) {
                                $id = $task['id'];
                                $name = $task['name'];
                                $due_date = $task['due_date'];
                                $is_task_done = $task['status']==1;
                                $task_class_name = $is_task_done ? "task-done" : "task-todo";

                                echo '<div class="todo-item">';
                                    echo '<input type="hidden" name="id" id="task_'.$id.'_id_input" value="'.$id.'">';
                                    echo '<input type="checkbox" class="form-check-input todo-item-status-input" name="status" id="task_'.$id.'_status_input" '.($is_task_done ? "checked":'').'>';
                                    echo '<input type="text" name="name" class="todo-item-name-input" id="task_'.$id.'_name_input"value="'.$name.'">';
                                    echo '<input type="hidden" name="due_date" id="task_'.$id.'_due_date_input" value="'.$due_date.'">';
                                    echo '<button class = "delete-item secondary-content todo-item-delete-button">';
                                        echo '<i class="fa fa-remove"></i>X';
                                    echo '</button>';
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <a href="#" class="clear-tasks btn black">Clear all tasks</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="tasks.js"></script>
    
    <script>
    function onDeleteButtonClick() {
        const button = $(this)
        const taskData = getTaskData(button);
        function afterDelete(){
            button.parent(".todo-item").remove();
        }

        deleteTask(taskData.id, afterDelete);        
    }

    $(".todo-item-status-input").click(function() {changeStatus(this);});
    $(".todo-item-name-input").change(function() {changeStatus(this);});

    $(".todo-item-delete-button").click(onDeleteButtonClick);

    $("#new-task-button").click(function() {
        const input = $("#new-task-name")[0]
        const name = input.value;
        
        function afterAdd(data){
            const id = data.id; 
            const name = data.name;
            const due_date = "2021-06-13 21:45:24"; // Date.now();
            $("#new-task-name").val('');

            let taskDiv = $("<div class=\"todo-item\"></div>");
            
            let idInput = $('<input type="hidden" name="id" id="task_'+id+'_id_input" value="'+id+'">');
            let statusInput =$('<input type="checkbox" class="form-check-input todo-item-status-input" name="status" id="task_'+id+'_status_input" >');
            statusInput.click(function() {changeStatus(this);});

            let nameInput = $('<input type="text" name="name" class="todo-item-name-input" id="task_'+id+'_name_input"value="'+name+'">');
            nameInput.change(function() {changeStatus(this);});

            let dueDateInput = $('<input type="hidden" name="due_date" id="task_'+id+'_due_date_input" value="'+due_date+'">');
            let deleteButton = $('<button class = "delete-item secondary-content todo-item-delete-button"><i class="fa fa-remove"></i>X</button>');
            deleteButton.click(onDeleteButtonClick);

            taskDiv.append(idInput);
            taskDiv.append(statusInput);
            taskDiv.append(nameInput);
            taskDiv.append(dueDateInput);
            taskDiv.append(deleteButton);

            $("#todo-list").append(taskDiv);
        }
        
        addTask(name, afterAdd);
    });
    </script>

</body>

</html>