<?php
$this->layout('layout', ['title' => 'Change Password', 'loggedIn' => $loggedIn, 'admin' => $admin]);
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Изменить пароль</h1>

            <?= $error->display() ?>
            <?= $warnings ?>

            <ul>
                <li><a href="/profile">Изменить профиль</a></li>
            </ul>
            <form action="/changepassword" class="form" method="POST">
                <div class="form-group">
                    <label for="current_password">Текущий пароль</label>
                    <input type="password" id="current_password" name="current_password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="new_password">Новый пароль</label>
                    <input type="password" id="new_password" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="new_password_again">Повторите новый пароль</label>
                    <input type="password" id="new_password_again" name="new_password_again" class="form-control">
                </div>

                <div class="form-group">
                    <button class="btn btn-warning">Изменить</button>
                </div>
            </form>


        </div>
    </div>
</div>