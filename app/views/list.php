<?php
$meta_title = "Your tasks"; // page title

// Select all tasks of the current list
$todo_list_id = $url[1];
$sth = $pdo->prepare('SELECT * FROM `todo_tasks` 
                               WHERE todo_list_id = :todo_list_id
                               ORDER BY task_position');
$sth->bindParam(':todo_list_id', $todo_list_id, PDO::PARAM_INT);
$sth->execute();
$user_tasks = $sth->fetchAll(PDO::FETCH_ASSOC);

// Adding new task
if (!empty($_POST['title'])) {
    $title = $_POST['title'];
    $last_task_position = array_pop($user_tasks);
    $new_task_position = $last_task_position['task_position'] + 1000;

    $sth = $pdo->prepare('INSERT INTO todo_tasks (title, todo_list_id, task_position) 
                                   VALUES(:title, :todo_list_id, :task_position)');
    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->bindParam(':todo_list_id', $todo_list_id, PDO::PARAM_INT);
    $sth->bindParam(':task_position', $new_task_position, PDO::PARAM_INT);
    $sth->execute();

    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

// Deleting of the task
if (!empty($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $sql = 'DELETE FROM `todo_tasks` WHERE `id` = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$id]);

    header('Location: ' . $url[1]);
}

// Marking "Completed" or "Check as done" of the task
if (!empty($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $sth = $pdo->prepare('SELECT is_done FROM `todo_tasks` WHERE id = :id');
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->execute();
    $is_done = $sth->fetchColumn();

    if ($is_done == 0) {
        $new_status = 1;
    } else {
        $new_status = 0;
    }

    $sth = $pdo->prepare('UPDATE todo_tasks SET is_done = :new_status
                                   WHERE `id` = :id');
    $pdo->prepare($sql);
    $sth->bindParam(':new_status', $new_status, PDO::PARAM_INT);
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->execute();

    header('Location: ' . $url[1]);
}

// Sorting of the tasks
if (!empty($_GET['up'])) {
    $id = $_GET['up'];

    foreach ($user_tasks as $key => $task) {
        if ($task['id'] == $id) {
            $current_array_position = $key;
        }
    }

    // Check field "task_position" of the previous task
    if (isset($user_tasks[$current_array_position - 1])) {
        $new_position_current_task = $user_tasks[$current_array_position - 1]['task_position'];
        $new_position_previous_task = $user_tasks[$current_array_position]['task_position'];
        $previous_task_id = $user_tasks[$current_array_position - 1]['id'];
    }

    if (isset($previous_task_id)) {
        $sth = $pdo->prepare('UPDATE todo_tasks
                SET task_position = CASE id
                               WHEN :previous_task_id THEN :new_position_previous_task
                               WHEN :id THEN :new_position_current_task
                    END
                WHERE id IN (:previous_task_id, :id)');
        $sth->bindParam(':previous_task_id', $previous_task_id, PDO::PARAM_INT);
        $sth->bindParam(':new_position_previous_task', $new_position_previous_task, PDO::PARAM_INT);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':new_position_current_task', $new_position_current_task, PDO::PARAM_INT);
        $sth->execute();

        header('Location: ' . $url[1]);
    }
}

if (!empty($_GET['down'])) {
    $id = $_GET['down'];

    foreach ($user_tasks as $key => $task) {
        if ($task['id'] == $id) {
            $current_array_position = $key;
        }
    }

    // Check field "task_position" of the next task
    if (isset($user_tasks[$current_array_position + 1])) {
        $new_position_current_task = $user_tasks[$current_array_position + 1]['task_position'];
        $new_position_next_task = $user_tasks[$current_array_position]['task_position'];
        $next_task_id = $user_tasks[$current_array_position + 1]['id'];
    }

    if (isset($next_task_id)) {
        $sth = $pdo->prepare('UPDATE todo_tasks
                SET task_position = CASE id
                               WHEN :next_task_id THEN :new_position_next_task
                               WHEN :id THEN :new_position_current_task
                    END
                WHERE id IN (:next_task_id, :id)');
        $sth->bindParam(':next_task_id', $next_task_id, PDO::PARAM_INT);
        $sth->bindParam(':new_position_next_task', $new_position_next_task, PDO::PARAM_INT);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':new_position_current_task', $new_position_current_task, PDO::PARAM_INT);
        $sth->execute();

        header('Location: ' . $url[1]);
    }
}

?>

<!--The form for adding new task to the current list-->
<form action="" method="post">
    <a href="/lists">Back to your lists</a>
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Write new task" name="title" required>
        <button type="submit" class="btn btn-outline-secondary">Add</button>
    </div>
</form>
    <!--End of the form for adding new task to the current list-->

    <!--    The table with tasks of the current list-->
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
    <!--    End of the table with tasks of the current list-->

