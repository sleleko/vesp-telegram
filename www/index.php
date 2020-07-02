<?php

declare(strict_types=1);

use App\Controllers\Web\Users;
use Vesp\Helpers\Env;
use Slim\Routing\RouteCollectorProxy;

define('BASE_DIR', dirname(__DIR__) . '/');
require_once BASE_DIR . 'core/vendor/autoload.php';
Env::loadFile(BASE_DIR . 'core/.env');

$app = DI\Bridge\Slim\Bridge::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(App\Middlewares\Auth::class);
$app->add(new RKA\Middleware\IpAddress());

$app->group(
    '/api',
    function (RouteCollectorProxy $group) {
        $group->group(
            '/admin',
            function (RouteCollectorProxy $group) {
                $group->any('/users', [App\Controllers\Admin\Users::class, 'process']);
                $group->any('/user-roles', [App\Controllers\Admin\UserRoles::class, 'process']);
            }
        );

        $group->any('/security/login', [App\Controllers\Security\Login::class, 'process']);
        $group->any('/security/logout', [App\Controllers\Security\Logout::class, 'process']);
        $group->any('/user/profile', [App\Controllers\User\Profile::class, 'process']);
    }
);
$app->any('/web/users[/{id:\d+}]', [Users::class, 'process']);

try {
    $app->run();
} catch (Throwable $e) {
    http_response_code($e->getCode());
    echo json_encode($e->getMessage());
}