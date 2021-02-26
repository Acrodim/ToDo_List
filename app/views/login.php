<?php
$meta_title = "Authorization form"; // page title

include_once 'app/views/warning_message.php';

if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    $login = $_POST['login'];
    $pass = $_POST['pass'];

    $sth = $pdo->prepare('SELECT * FROM `users` WHERE login = :login');
    $sth->bindParam(':login', $login, PDO::PARAM_STR);
    $sth->execute();
    $user = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($user)) {
        if ($user[0]['login'] == $login && password_verify($pass, $user[0]['pass'])) {
            $_SESSION['user']['id'] = $user[0]['id'];
            $_SESSION['user']['login'] = $user[0]['login'];
            header('Location: /lists');
        } else {
            $_SESSION['warning'] = '<div class="alert alert-warning row col-sm-4 mx-auto" 
                                    role="alert">Login or password is not exists!</div>';
            $_SESSION['login'] = $login;
            header('Location: /login');
        }
    } else {
        $_SESSION['warning'] = '<div class="alert alert-warning row col-sm-4 mx-auto" 
                                role="alert">Login is not exists!</div>';
        $_SESSION['login'] = $login;

        header('Location: /login');
    }
}
?>

<!-- Authorization form -->
<div class="row col-sm-4 mx-auto">
    <div class="col">
        <h2>Authorization</h2>
        <form action="" method="post">
            <input type="text" class="form-control" name="login" id="login"
                   placeholder="Enter login" value="<?= $_SESSION['login'] ?>" required><br>
            <input type="password" class="form-control" name="pass" placeholder="Enter password" required><br>
            <button class="btn btn-success" name="signup" type="submit">SignIn</button>
        </form>
    </div>
</div>

