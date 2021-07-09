<?php
$this->layout('layout', ['title' => $username . "'s profile", 'loggedIn' => $loggedIn, 'admin' => $admin]);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Данные пользователя</h1>
            <table class="table">
                <thead>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Дата регистрации</th>
                    <th>Статус</th>
                </thead>

                <tbody>
                    <tr>
                        <td><?= $this->e($id) ?></td>
                        <td><?= $this->e($username) ?></td>
                        <td><?= $this->e($register_date) ?></td>
                        <td><?= $this->e($profile_status) ?></td>
                    </tr>
                </tbody>
            </table>


        </div>
    </div>
</div>