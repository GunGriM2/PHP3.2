<?php
$this->layout('layout', ['title' => 'Homepage', 'loggedIn' => $loggedIn, 'admin' => $admin]);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron">
                <h1 class="display-4">Привет, мир!</h1>
                <p class="lead">Это дипломный проект по разработке на PHP. На этой странице список наших пользователей.</p>
                <hr class="my-4">
                <p>Чтобы стать частью нашего проекта вы можете пройти регистрацию.</p>
                <a class="btn btn-primary btn-lg" href="/register" role="button">Зарегистрироваться</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h1>Пользователи</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><a href="/user-profile/<?= $user['id'] ?>"><?= $user['username'] ?></a></td>
                            <td><?= $user['email'] ?></td>
                            <td><?php echo date('d/m/Y', $user['registered']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>