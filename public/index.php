<?php
// Start a Session

use Valitron\Validator;

if (!session_id()) @session_start();

require "../vendor/autoload.php";

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
    PDO::class => function () {
        $driver = 'mysql';
        $host = 'mysql';
        $database_name = 'level3';
        $username = 'root';
        $password = 'secret';

        return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
    },

    League\Plates\Engine::class => function () {
        return new League\Plates\Engine('../app/views');
    },

    Delight\Auth\Auth::class => function ($container) {
        return new \Delight\Auth\Auth($container->get('PDO'), null, null, null, 0.1);
    },

    Aura\SqlQuery\QueryFactory::class => function () {
        return new Aura\SqlQuery\QueryFactory('mysql');
    }
]);
$container = $containerBuilder->build();

// $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
//     ->setUsername('07568447bbf596')
//     ->setPassword('6e2a9ea439c43e');


// $mailer = new Swift_Mailer($transport);

// $message = (new Swift_Message('Серьезная маза'))
//     ->setFrom(['john@doe.com' => 'Maksim Grin'])
//     ->setTo('receiver@domain.org')
//     ->setBody('Сбегай кабанчиком за клубничкой, циферки мои знаешь, на созвоне...');

// // Send the message
// $result = $mailer->send($message);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/home', ['App\controllers\HomeController', 'index']);
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'index']);


    $r->addRoute('GET', '/register', ['App\controllers\HomeController', 'register']);
    $r->addRoute('POST', '/register', ['App\controllers\HomeController', 'register']);

    $r->addRoute('GET', '/login', ['App\controllers\HomeController', 'login']);
    $r->addRoute('POST', '/login', ['App\controllers\HomeController', 'login']);

    $r->addRoute('GET', '/logout', ['App\controllers\HomeController', 'logout']);

    $r->addRoute('GET', '/profile', ['App\controllers\HomeController', 'profile']);
    $r->addRoute('POST', '/profile', ['App\controllers\HomeController', 'profile']);

    $r->addRoute('GET', '/changepassword', ['App\controllers\HomeController', 'changepassword']);
    $r->addRoute('POST', '/changepassword', ['App\controllers\HomeController', 'changepassword']);

    $r->addRoute('GET', '/user-profile/{id:\d+}', ['App\controllers\HomeController', 'userProfile']);
    $r->addRoute('POST', '/user-profile/{id:\d+}', ['App\controllers\HomeController', 'userProfile']);

    $r->addRoute('GET', '/users', ['App\controllers\HomeController', 'users']);
    $r->addRoute('POST', '/users', ['App\controllers\HomeController', 'users']);

    $r->addRoute('GET', '/users/changepermission/{id:\d+}', ['App\controllers\HomeController', 'changeUserPermission']);
    $r->addRoute('POST', '/users/changepermission/{id:\d+}', ['App\controllers\HomeController', 'changeUserPermission']);

    $r->addRoute('GET', '/users/delete/{id:\d+}', ['App\controllers\HomeController', 'userDelete']);
    $r->addRoute('POST', '/users/delete/{id:\d+}', ['App\controllers\HomeController', 'userDelete']);

    $r->addRoute('GET', '/users/edit/{id:\d+}', ['App\controllers\HomeController', 'userEdit']);
    $r->addRoute('POST', '/users/edit/{id:\d+}', ['App\controllers\HomeController', 'userEdit']);

    // $r->addRoute('GET', '/verification', ['App\controllers\HomeController', 'email_verification']);
    // $r->addRoute('GET', '/paginate[/{page:\d+}]', ['App\controllers\HomeController', 'paginate']);
    // // The /{title} suffix is optional
    // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo 404;
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo 405;
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $container->call($handler, [$vars]);

        // ... call $handler with $vars
        // $controller = new $handler[0];
        // call_user_func([$controller, $handler[1]], $vars);
        // d($controller);
        // call_user_func($handler[0], $handler[1], $vars);

        break;
}
