<?php

$sth = $pdo->prepare('SELECT * FROM `todo_lists` WHERE user_id = :user_id ORDER BY created_at DESC');
$sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$sth->execute();
$user_lists = $sth->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST['title'])) {
    $title = $_POST['title'];

    $sth = $pdo->prepare('INSERT INTO todo_lists (title, user_id) VALUES(:title, :user_id)');

    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sth->execute();

    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

if (!empty($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $sql = 'DELETE FROM `todo_lists` WHERE `id` = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$id]);

    header('Location: /lists');
}
?>

<div class="col-sm-6">
    <h2>Create new list</h2>
    <form action="" method="post">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="title" placeholder="Enter name for new list">
            <button class="btn btn-outline-secondary" type="submit">Add</button>
        </div>
    </form>
</div>

     <div>
         <h2>Your lists, <?= $user_login ?></h2>
         <table class="table table-bordered">
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
     </div>
