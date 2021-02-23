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

    echo '<pre>';
    print_r($_SESSION['user']);
    echo '</pre>';

    if (!empty($user)) {
        if ($user[0]['login'] == $login && $user[0]['pass'] == $pass) {
            $_SESSION['user']['id'] = $user[0]['id'];
            $_SESSION['user']['login'] = $user[0]['login'];
            header('Location: /lists');
        } else {
            $_SESSION['warning'] = '<div class="alert alert-warning" role="alert">Login or password is not exists!</div>';
            header('Location: /login');
        }
    } else {
        $_SESSION['warning'] = '<div class="alert alert-warning" role="alert">Login is not exists!</div>';
        header('Location: /login');
    }
}

?>

<div class="row">
    <div class="col">
        <!-- Форма авторизации -->
        <h2>Authorization</h2>
        <form action="/login" method="post">
            <input type="text" class="form-control" name="login" id="login" placeholder="Enter login"><br>
            <input type="password" class="form-control" name="pass" placeholder="Enter password"><br>
            <button class="btn btn-success" name="signup" type="submit">SignIn</button>
        </form>
    </div>
</div>

