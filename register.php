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
                     header('Location: index.php');
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
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Task_menedżer - załóż darmowe konto</title>
    <!-- <script src="https://www.google.com/recaptcha/api.js"></script> -->

    <style>
    .error {
        color: red;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    </style>

    [<a href="zaloguj.php"> Przejdź do logowania </a>]</p>
</head>

<body>

    <form method="post" action="">

        <?php
        if(isset($_SESSION['err_email']))
            {
                echo '<div class="error">'.$_SESSION['err_email'].'</div>';
                unset($_SESSION['err_email']);
            }
            ?>
        <label for="email">Email:</label>
        <input type="email" name="email" minlength="5" maxlength="50" value="<?php echo $email ?>" />

        <?php
        if(isset($_SESSION['err_password']))
            {
                echo '<div class="error">'.$_SESSION['err_password'].'</div>';
                unset($_SESSION['err_password']);
            }
            ?>
        <label for="password_1">Hasło:</label>
        <input type="password" name="password_1" minlength="8" value="<?php echo $password_1 ?>" />

        <?php
        if(isset($_SESSION['err_password_2']))
            {
                echo '<div class="error">'.$_SESSION['err_password_2'].'</div>';
                unset($_SESSION['err_password_2']);
            }
            ?>
        <label for="password_2">Powtórz hasło:</label>
        <input type="password" name="password_2" minlength="8" value="<?php echo $password_2 ?>" />

        <!-- <label>
            <input type="checkbox" name="regulamin" /> Akceptuję regulamin
        </label> -->



        <!-- <script>
        function onSubmit(token) {
            document.getElementById("demo-form").submit();
        }
        </script> -->

        <button data-action='submit'>Zarejestruj się</button>

    </form>
    <!-- TODO: class="g-recaptcha" data-sitekey="6LdoE6QZAAAAAMTH8ytxSDs8l0jzFgy0X-P_1wbP" data-callback='onSubmit' -->

</body>

</html>