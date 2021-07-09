<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $this->e($title) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php if ($this->e($title) === 'Login' or $this->e($title) === 'Registration') : ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Custom styles for this template -->
        <link href="css/style.css" rel="stylesheet">
    <?php else : ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <?php endif ?>
    <?php if ($this->e($title) != 'Login' and $this->e($title) != 'Registration') : ?>
        <?= $this->insert('sections/scripts') ?>
    <?php endif ?>
</head>

<body>
    <?php if ($this->e($title) != 'Login' and $this->e($title) != 'Registration') : ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/home">User Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">Главная</a>
                    </li>
                    <?php if ($admin) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/users">Управление пользователями</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if ($loggedIn) : ?>
                        <li class="nav-item">
                            <a href="/profile" class="nav-link">Профиль</a>
                        </li>
                        <li class="nav-item">
                            <a href="/logout" class="nav-link">Выйти</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a href="/login" class="nav-link">Войти</a>
                        </li>
                        <li class="nav-item">
                            <a href="/register" class="nav-link">Регистрация</a>
                        </li>
                    <?php endif ?>
                </ul>

            </div>
        </nav>
    <?php endif ?>

    <?= $this->section('content') ?>

</body>

</html>