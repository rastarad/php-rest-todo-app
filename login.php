<?php

    session_start();

    if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
    {
        header('Location:tasks.php');
        exit();   
    }
    $login = "";
    if(!empty($_SESSION['login'])){
    $login = $_SESSION['login'];
    }
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Task menager</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/css/mdb.min.css" rel="stylesheet" />

</head>

<body>
    <!-- Material form login -->
    <div class="card">
        <h5 class="card-header info-color white-text text-center py-4">
            <strong>Sign in</strong>
        </h5>

        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">
            <!-- Form -->
            <form method="post" class="text-center" style="color: #757575;" action="zaloguj.php">

                <!-- Email -->
                <div class="md-form">
                    <input type="email" id="emailinput" name="login" class="form-control"
                        value="<?php echo $login ?>" />
                    <label for="materialLoginFormEmail">E-mail</label>
                </div>

                <!-- Password -->
                <div class="md-form">
                    <input type="password" id="passwordinput" name="haslo" class="form-control" />
                    <label for="materialLoginFormPassword">Password</label>
                </div>

                <!-- Sign in button -->
                <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">
                    Sign in
                </button>

                <!-- Register -->
                <p>
                    Not a member?
                    <a href="register.php">Register</a>
                </p>
            </form>
            <!-- Form -->
        </div>
    </div>

    <!-- Material form login -->

    <?php
    if(isset($_SESSION['blad'])) echo $_SESSION['blad'];
    ?>

    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js">
    </script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/js/mdb.min.js">
    </script>

</body>

</html>