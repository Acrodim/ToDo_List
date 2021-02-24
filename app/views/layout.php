<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="app/public/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
            crossorigin="anonymous"></script>
    <title><?= $meta_title ?></title>
</head>
<body>

<nav>
    <div class="container">
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/">Home</a>
            </li>
            <?php if (!empty($user_id)): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">LogOut</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/register">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login">LogIn</a>
                </li>
            <?php endif ?>

        </ul>
    </div>
</nav>

<div class="container">

    <?= $content ?>

</div>
</body>
</html>
