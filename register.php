<?php

    session_start();
    
    //tworzy puste zmienne
    $email = "";
    $password_1 = "";
    $password_2 = "";

    if(isset($_POST['email']))
    {
        // zwraca poprawną walidację
        $wszystko_OK=true;

        if(!empty($_POST['email'])){
           $email = $_POST['email'];
        }
        if(!empty($_POST['password_1'])){
            $password_1 = $_POST['password_1'];
         }
         if(!empty($_POST['password_2'])){
            $password_2 = $_POST['password_2'];
         }

        //Sprawdzanie poprawności danych
        if(strlen($email)<5 || (strlen($email)>20))
        {
            $wszystko_OK=false;
            $_SESSION['err_email']="Email musi posiadać od 5 do 50 znaków!";
        }
        if(strlen($password_1)<7)
        {
            $wszystko_OK=false;
            $_SESSION['err_password']="Hasło musi posiadać powyżej 7 znaków";
        }
        if($password_1!=$password_2)
        {
            $wszystko_OK=false;
            $_SESSION['err_password_2']="Hasła muszą być takie same";
        }

        require_once "connect.php";
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        
        if ($polaczenie->connect_errno!=0)
        {
            echo "Error: ".$polaczenie->connect_errno;
            http_response_code(500);
            die('Failed to connect to database');
        }

        if($rezultat = $polaczenie->query(
            sprintf("SELECT * FROM users WHERE email='%s'",
            mysqli_real_escape_string($polaczenie,$email),
        )))
        {
            if($rezultat->num_rows > 0){
                $wszystko_OK=false;
                $_SESSION['err_email'] = "Uzytkownik o podanym mailu istnieje";
            }
        }
    
        //jeśl walidacja się powiodła łączę się z bazą
        if($wszystko_OK==true)
        {
            $password_hash = password_hash($password_1, PASSWORD_ARGON2ID);

            //pozwala używać encji
            $email = htmlentities($email, ENT_QUOTES, "UTF-8");
            $password_hash = htmlentities($password_hash, ENT_QUOTES, "UTF-8");
        
            // dodaje nowy email i hasło do bazy
            if($rezultat = @$polaczenie->query(
                sprintf("INSERT INTO `users`(`email`, `password_hash`, `is_admin`) VALUES ('%s','%s',0)",
                mysqli_real_escape_string($polaczenie,$email),
                mysqli_real_escape_string($polaczenie,$password_hash))))
            {
                if($rezultat === TRUE)
                {
                    unset($_SESSION['blad']);
                     header('Location: login.php');
                     $polaczenie->close();
                    exit();
                }else {
                    $_SESSION['blad'] = '<span style="color:red">Nieudana rejestracja. Spróbuj ponownie</span>';
                    $polaczenie->close(); 
                }
            }
        }  
    }
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Task_menedżer - załóż darmowe konto</title>
    <!-- <script src="https://www.google.com/recaptcha/api.js"></script> -->

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

    <!-- Material form register -->
    <div class="card">
        <h5 class="card-header info-color white-text text-center py-4">
            <strong>Sign up</strong>
        </h5>

        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">
            <!-- Form -->
            <form method="post" class="text-center" style="color: #757575;" action="">

                <!-- E-mail -->
                <?php
                    if(isset($_SESSION['err_email']))
                    {
                        echo '<div class="error">'.$_SESSION['err_email'].'</div>';
                        unset($_SESSION['err_email']);
                    }
                ?>
                <div class="md-form">
                    <input type="email" id="emailinput" name="email" class="form-control" minlength="5" maxlength="50"
                        value="<?php echo $email ?>" />
                    <label for="email">E-mail</label>
                </div>

                <!-- Password 1-->
                <div class="md-form">
                    <input type="password" id="passwordinput_1" name="password_1" class="form-control"
                        aria-describedby="materialRegisterFormPasswordHelpBlock" minlength="8"
                        value="<?php echo $password_1 ?>" />
                    <label for="password_1">Password</label>
                    <?php
                    if(isset($_SESSION['err_password']))
                    {
                        echo '<div class="invalid-feedback">'.$_SESSION['err_password'].'</div>';
                        unset($_SESSION['err_password']);
                    }
                    ?>
                </div>

                <!-- Password 2-->
                <div class="md-form">
                    <input type="password" id="passwordinput_2" name="password_2" class="form-control"
                        aria-describedby="materialRegisterFormPasswordHelpBlock" minlength="8"
                        value="<?php echo $password_2 ?>" />
                    <label for="password_2">Confirm password</label>
                    <small id="materialRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                        At least 8 characters
                    </small>
                    <?php
                    if(isset($_SESSION['err_password_2']))
                    {
                        echo '<div class="invalid-feedback">'.$_SESSION['err_password_2'].'</div>';
                        unset($_SESSION['err_password_2']);
                    }
                    ?>
                </div>

                <!-- Sign up button -->
                <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">
                    Sign up
                </button>

                <!-- Login -->
                <p>
                    Already a member
                    <a href="login.php">Sign in</a>
                </p>

            </form>
            <!-- Form -->
        </div>
    </div>
    <!-- Material form register -->

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