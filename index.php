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
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Task menedżer</title>
</head>

<body>

    Tylko martwi ujrzeli koniec programowania - Platfon

    <form action="zaloguj.php" method="post">

        Email: <br /> <input type="email" name="login" value="<?php echo $login ?>" /> <br />
        Hasło: <br /> <input type="password" name="haslo" /> <br /> <br />
        <input type="submit" value="Zaloguj się" />
        [<a href="register.php"> Zarejestruj się! </a>]</p>

    </form>

    <?php
    if(isset($_SESSION['blad'])) echo $_SESSION['blad'];
    ?>

</body>

</html>