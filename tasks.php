<?php


session_start();

if(!isset($_SESSION['zalogowany']))
{
    header('Location: index.php');
    exit();   
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


                    $tasks[0] = 'testowe_zadanie_1';
                    $tasks[1] = 'testowe_zadanie_2';
                    $tasks[2] = 'testowe_zadanie_3';
                    $tasks[3] = 'testowe_zadanie_4';
                        foreach ($tasks as $task) {
                            $name = $task;
               
                            echo '<li class="collection-item">';
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