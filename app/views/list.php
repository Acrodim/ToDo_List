<?php
$meta_title = "Your tasks"; // название формы

$todo_list_id = $url[1];
$sth = $pdo->prepare('SELECT * FROM `todo_tasks` WHERE todo_list_id = :todo_list_id ORDER BY task_position');
$sth->bindParam(':todo_list_id', $todo_list_id, PDO::PARAM_INT);
$sth->execute();
$user_tasks = $sth->fetchAll(PDO::FETCH_ASSOC);

//print_r($todo_list_id);

// Adding new task
if (!empty($_POST['title'])) {
    $title = $_POST['title'];
    $last_task_position = array_pop($user_tasks);
    $new_task_position = $last_task_position['task_position'] + 100;

//    $sth = $pdo->prepare('INSERT INTO todo_tasks (title, todo_list_id) VALUES(:title, :todo_list_id)');
//    $sth->bindParam(':title', $title, PDO::PARAM_STR);
//    $sth->bindParam(':todo_list_id', $todo_list_id, PDO::PARAM_INT);
//    $sth->execute();

    $sth = $pdo->prepare('INSERT INTO todo_tasks (title, todo_list_id, task_position) VALUES(:title, :todo_list_id, :task_position)');
    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->bindParam(':todo_list_id', $todo_list_id, PDO::PARAM_INT);
    $sth->bindParam(':task_position', $new_task_position, PDO::PARAM_INT);
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
    <a href="/lists">Back to your lists</a>
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
        <th scope="col" style="width: 2%">Sort</th>
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
            <td>
                <a href="?up=<?= $task['id'] ?>">
                    <button type="button" class="btn-up" aria-label="Up">Up</button>
                </a>
                <a href="?down=<?= $task['id'] ?>">
                    <button type="button" class="btn-down" aria-label="Down">Down</button>
                </a>
            </td>
        </tr>
    <?php endforeach ?>

    </tbody>
</table>

<?php
foreach ($user_tasks as $task) {
    echo $task['title'] . ' - ' . $task['task_position'] . '<br>';
}
//echo '<pre>';
//print_r($user_tasks) ;
//    echo '</pre>';
?>

<?php

//if (!empty($_GET['up']) || !empty($_GET['down'])) {
//    $id = array_pop($_GET);
//echo $id . '<br>';
//    foreach ($user_tasks as $key => $task) {
//        if ($task['id'] == $id) {
//            $current_array_position = $key;
//        }
//    }
//    echo '$current_array_position - ' . $current_array_position . '<br>';
//
//    print_r($user_tasks[$current_array_position - 1]);
//
//    if (!empty($_GET['up'])/* && !empty($user_tasks[$current_array_position - 1])*/) {
////        $id = $_GET['up'];
//        $new_position = $user_tasks[$current_array_position - 1]['task_position'] - 1;
//        echo 55555;
//       }
//
//    if (!empty($_GET['down']) && isset($user_tasks[$current_array_position + 1])) {
////        $id = $_GET['down'];
//        $new_position = $user_tasks[$current_array_position + 1]['task_position'] + 1;
//    }
//    echo '<br>' . '$new_position - ' . $new_position;
//
////    if($new_position > 0) {
//        $sql = 'UPDATE todo_tasks SET task_position = ? WHERE id = ?';
//        $query = $pdo->prepare($sql);
//        $query->execute([$new_position, $id]);
////    }
//    header('Location: ' . $url[1]);
//}

if (!empty($_GET['up'])) {
    $id = $_GET['up'];

    foreach ($user_tasks as $key => $task) {
        if ($task['id'] == $id) {
            $current_array_position = $key;
        }
    }

    if (isset($user_tasks[$current_array_position - 1])) {
        $new_position = $user_tasks[$current_array_position - 1]['task_position'] - 10;
    }
}

if($new_position > 0) {
    $sql = 'UPDATE todo_tasks SET task_position = ? WHERE id = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$new_position, $id]);

    header('Location: ' . $url[1]);
}

if (!empty($_GET['down'])) {
    $id = $_GET['down'];

    foreach ($user_tasks as $key => $task) {
        if ($task['id'] == $id) {
            $current_array_position = $key;
        }
    }

    if (isset($user_tasks[$current_array_position+1])) {
        $new_position = $user_tasks[$current_array_position + 1]['task_position'] + 10;
    }
}

if($new_position > 0) {
    $sql = 'UPDATE todo_tasks SET task_position = ? WHERE id = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$new_position, $id]);

    header('Location: ' . $url[1]);
}