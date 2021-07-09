<?php
$this->layout('layout', ['title' => 'Login', 'loggedIn' => $loggedIn, 'admin' => $admin]);
?>

<body class="text-center">
    <form class="form-signin" action="/login" method="POST">
        <img class="mb-4" src="images/apple-touch-icon.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Авторизация</h1>

        <?= $errors->display() ?>

        <div class="form-group">
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?= $email ?>">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" id="password" placeholder="Пароль">
        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" name="remember"> Запомнить меня
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2017-2021</p>
    </form>
</body>

</html>