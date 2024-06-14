<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
->withMiddleware(function (Middleware $middleware) {
    //
})
->withExceptions(function (Exceptions $exceptions) {
    //
})->create();

$app->register(Laravel\Lumen\Routing\RouterServiceProvider::class);
$app->register(Illuminate\View\ViewServiceProvider::class);
$app->withFacades();
$app->withEloquent();

// Add view path and alias
$app->alias('view', \Illuminate\View\Factory::class);

$app->configure('view');