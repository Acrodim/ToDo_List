<?php
$meta_title = "Your lists"; // page title

include_once 'app/views/warning_message.php';

$sth = $pdo->prepare('SELECT * FROM `todo_lists` WHERE user_id = :user_id ORDER BY created_at DESC');
$sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$sth->execute();
$user_lists = $sth->fetchAll(PDO::FETCH_ASSOC);

// adding a new list
if (!empty($_POST['title'])) {
    $title = $_POST['title'];

    $sth = $pdo->prepare('INSERT INTO todo_lists (title, user_id) VALUES(:title, :user_id)');

    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sth->execute();

    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

// deleting the list
if (!empty($_GET['del_id'])) {
    $id = $_GET['del_id'];

    $sth = $pdo->prepare('SELECT id FROM `todo_tasks` WHERE todo_list_id = :todo_list_id');
    $sth->bindParam(':todo_list_id', $id, PDO::PARAM_INT);
    $sth->execute();
    $list_id = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($list_id)) {
        $_SESSION['warning'] = "<div class=\"alert alert-warning row col-sm-7 mx-auto\" role=\"alert\">
                                    Before deleting of this <?php print_r($user_lists) ?>ToDo List you should delete tasks it contains!
                                </div>";
        header('Location: /lists');
    } else {
        $sql = 'DELETE FROM `todo_lists` WHERE `id` = ?';
        $query = $pdo->prepare($sql);
        $query->execute([$id]);

        header('Location: /lists');
    }
}
?>

<!-- adding a new list-->
<form action="" method="post">
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="title" placeholder="Enter title to create new list" required>
        <button class="btn btn-outline-secondary" type="submit">Add</button>
    </div>
</form>

<!-- The table of the user's lists-->
<table class="table table-bordered table-hover list">
    <thead>
    <tr>
        <th scope="col" style="width: 45%">List name</th>
        <th scope="col" style="width: 10%">Date</th>
        <th scope="col" style="width: 5%">Delete</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($user_lists as $list): ?>
        <tr>
            <td><a href="list/<?= $list['id'] ?>"><?= $list['title'] ?></a></td>
            <td><?= $list['created_at'] ?></td>
            <td>
                <a href="?del_id=<?= $list['id'] ?>">
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </a>
            </td>
        </tr>
    <?php endforeach ?>

    </tbody>
</table>

