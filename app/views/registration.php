<?php
$this->layout('layout', ['title' => 'Registration', 'loggedIn' => $loggedIn, 'admin' => $admin]);
?>


<body class="text-center">
    <form class="form-signin" action="/register" method="POST">
        <img class="mb-4" src="images/apple-touch-icon.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>


        <?= $errors->display() ?>

        <div class="form-group">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $email ?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Ваше имя" value="<?= $username ?>">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
        </div>

        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password_again" placeholder="Повторите пароль">
        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" name="rules"> Согласен со всеми правилами
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Зарегистрироваться</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2017-2021</p>
    </form>
</body>



</html>