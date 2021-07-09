<?php
$this->layout('layout', ['title' => 'Profile', 'admin' => $admin, 'loggedIn' => $loggedIn]);
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Профиль пользователя - <?= $this->e($username) ?></h1>

            <?= $error->display() ?>

            <ul>
                <li><a href="/changepassword">Изменить пароль</a></li>
            </ul>
            <form action="/profile" class="form" method="POST">
                <div class="form-group">
                    <label for="username">Имя</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= $this->e($username) ?>">
                </div>

                <div class="form-group">
                    <label for="status">Статус</label>
                    <input type="text" id="profile_status" name="profile_status" class="form-control" value="<?= $this->e($profile_status) ?>">
                </div>

                <div class="form-group">
                    <button class="btn btn-warning">Обновить</button>
                </div>
            </form>


        </div>
    </div>
</div>