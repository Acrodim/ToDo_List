<?php
$title = "Registration form"; // название формы

if (!empty($_SESSION['warning'])) {
    echo $_SESSION['warning'];
    unset($_SESSION['warning']);
}


if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    $login = $_POST['login'];
    $pass = $_POST['pass'];

    $sth = $pdo->prepare('SELECT * FROM `users` WHERE login = :login');
    $sth->bindParam(':login', $login, PDO::PARAM_STR);
    $sth->execute();
    $user = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($user)) {
        $_SESSION['warning'] = '<div class="alert alert-warning" role="alert">User with this login is already registered. Try signIn!</div>';
        header('Location: /register');
    } else {
        $sth = $pdo->prepare('INSERT INTO users (login, pass) VALUES(:login, :pass)');

        $sth->bindParam(':login', $login, PDO::PARAM_STR);
        $sth->bindParam(':pass', $pass, PDO::PARAM_STR);
        $sth->execute();

        $_SESSION['warning'] = '<div class="alert alert-success" role="alert">You have been successfully registered!</div>';
        header('Location: /login');
    }
}
?>


<div class="row">
    <div class="col">
        <!-- Форма регистрации -->
        <h2>Registration</h2>
        <form action="/register" method="post">
            <input type="text" class="form-control" name="login" placeholder="Enter login" required><br>
            <input type="password" class="form-control" name="pass" placeholder="Enter password" required><br>
            <button class="btn btn-success" name="signup" type="submit">SignUp</button>
        </form>
    </div>
</div>


