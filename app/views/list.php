<?php
$todo_list_id = $url[1];
$sth = $pdo->prepare('SELECT * FROM `todo_tasks` WHERE todo_list_id = :todo_list_id ORDER BY created_at DESC');
$sth->bindParam(':todo_list_id', $todo_list_id, PDO::PARAM_INT);
$sth->execute();
$user_tasks = $sth->fetchAll(PDO::FETCH_ASSOC);

//print_r($todo_list_id);

if (!empty($_POST['title'])) {
    $title = $_POST['title'];

    $sth = $pdo->prepare('INSERT INTO todo_tasks (title, todo_list_id) VALUES(:title, :todo_list_id)');
    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->bindParam(':todo_list_id', $todo_list_id, PDO::PARAM_INT);
    $sth->execute();

    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

if (!empty($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $sql = 'DELETE FROM `todo_tasks` WHERE `id` = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$id]);

    header('Location: ' . $url[1]);
}

if (!empty($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $sth = $pdo->prepare('SELECT is_done FROM `todo_tasks` WHERE id = :id');
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->execute();
    $is_done = $sth->fetchColumn();

    if ($is_done == 0) {
        $sql = 'UPDATE todo_tasks SET is_done = 1 WHERE `id` = ?';
        $query = $pdo->prepare($sql);
        $query->execute([$id]);
    } else {
        $sql = 'UPDATE todo_tasks SET is_done = 0 WHERE `id` = ?';
        $query = $pdo->prepare($sql);
        $query->execute([$id]);
    }

    header('Location: ' . $url[1]);
}

?>

<form action="" method="post">
    <h1 class="text-center">ToDoList <span><?= '' ?></span></h1>
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Write new task" name="title" required>
        <button type="submit" class="btn btn-outline-secondary">Add</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col" style="width: 45%">Title</th>
        <th scope="col" style="width: 10%">Date</th>
        <th scope="col" style="width: 10%">Done</th>
        <th scope="col" style="width: 5%">Delete</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($user_tasks as $task): ?>
        <tr<?= $task['is_done'] == 1 ? ' class="table-success"' : '' ?>>
            <td><?= $task['title'] ?></td>
            <td><?= $task['created_at'] ?></td>
            <?php if ($task['is_done'] == 0): ?>
                <td>
                    <a href="?edit_id=<?= $task['id'] ?>">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">Check as done
                        </button>
                    </a>
                </td>
            <?php else: ?>
                <td>
                    <a href="?edit_id=<?= $task['id'] ?>">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">Completed
                        </button>
                    </a>
                </td>
            <? endif ?>

            <td>
                <a href="?del_id=<?= $task['id'] ?>">
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </a>
            </td>
        </tr>
    <?php endforeach ?>

    </tbody>
</table>
