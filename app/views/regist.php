<?php
$meta_title = "Registration form"; // page title

include_once 'app/views/warning_message.php';

if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    $login = $_POST['login'];
    $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

    $sth = $pdo->prepare('SELECT * FROM `users` WHERE login = :login');
    $sth->bindParam(':login', $login, PDO::PARAM_STR);
    $sth->execute();
    $user = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($user)) {
        $_SESSION['warning'] = '<div class="alert alert-warning row col-sm-4 mx-auto" 
                                role="alert">User with this login is already registered. Try signIn!</div>';
        header('Location: /register');
    } else {
        $sth = $pdo->prepare('INSERT INTO users (login, pass) VALUES(:login, :pass)');

        $sth->bindParam(':login', $login, PDO::PARAM_STR);
        $sth->bindParam(':pass', $pass, PDO::PARAM_STR);
        $sth->execute();

        $_SESSION['warning'] = '<div class="alert alert-success row col-sm-4 mx-auto" 
                                role="alert">You have been successfully registered!</div>';
        header('Location: /login');
    }
}
?>

<!--Registration form-->
<div class="row col-sm-4 mx-auto">
    <div class="col">
        <h2>Registration</h2>
        <form action="" method="post">
            <input type="text" class="form-control" name="login"
                   placeholder="Enter login" value="<?= $login ?>" required><br>
            <input type="password" class="form-control" name="pass" placeholder="Enter password" required><br>
            <button class="btn btn-success" name="signup" type="submit">SignUp</button>
        </form>
    </div>
</div>


