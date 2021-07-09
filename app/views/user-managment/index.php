<?php
$this->layout('layout', ['title' => 'Users', 'loggedIn' => $loggedIn, 'admin' => $admin]);
?>

<div class="container">
    <div class="col-md-12">
        <h1>Пользователи</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Действия</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user) : ?>
                    <?php if ($user['id'] == $auth->getUserId()) {
                        continue;
                    } ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['username'] ?></a></td>
                        <td><?= $user['email'] ?></td>
                        <td>
                            <a href="users/changepermission/<?= $user['id'] ?>" class="<?php echo ($auth->admin()->doesUserHaveRole($user['id'], \Delight\Auth\Role::ADMIN)) ? "btn btn-danger" : "btn btn-success"; ?>">
                                <?php
                                echo ($auth->admin()->doesUserHaveRole($user['id'], \Delight\Auth\Role::ADMIN)) ? "Разжаловать" : "Назначить администратором";
                                ?>
                            </a>
                            <a href="user-profile/<?= $user['id'] ?>" class="btn btn-info">Посмотреть</a>
                            <a href="users/edit/<?= $user['id'] ?>" class="btn btn-warning">Редактировать</a>
                            <a href="users/delete/<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>