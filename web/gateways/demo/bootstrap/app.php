<?php
if(!defined('__GATEWAY_ROOT_DIR__')){
    define('__GATEWAY_ROOT_DIR__', __DIR__ . '/../../');
}

require_once __GATEWAY_ROOT_DIR__.'/../vendor/autoload.php';

if(!defined('ENV_LOADED')){
    try {
        $envKeyFile = __GATEWAY_ROOT_DIR__ . '/../.envkey';
        $envFileSuf = is_file($envKeyFile) ? trim(file_get_contents($envKeyFile)) : '';
        $envFile = '.env';
        if (!empty($envFileSuf)) {
            $envCusFile = sprintf("%s.%s", $envFile, $envFileSuf);
            $envCusFilePath = __GATEWAY_ROOT_DIR__ . '/../' . $envCusFile;
            $envFile = is_file($envCusFilePath) ? $envCusFile : $envFile;
        }
        (new Dotenv\Dotenv(__GATEWAY_ROOT_DIR__.'/../', $envFile))->load();
        define('ENV_LOADED', 1);
    } catch (Dotenv\Exception\InvalidPathException $e) {
        //
    }
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__GATEWAY_ROOT_DIR__.'/../')
);

$app->withFacades();
if(!class_exists('App')) {
    class_alias(\Illuminate\Support\Facades\App::class, 'App');
}

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Custom configure
|--------------------------------------------------------------------------
*/
$app->configure('database');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Gateway\Course\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Gateway\Course\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

$app->configure('amqp');
$app->register(Bschmitt\Amqp\AmqpServiceProvider::class);

if(env('CODELOG.FILE_FMT')) {
    $app->configureMonologUsing(function(Monolog\Logger $monolog) use ($app) {
        $flag = defined('HOME_FLAG') ? HOME_FLAG :0;
        $logFile = env('LOG_ROOT') . '/' . sprintf(env('CODELOG.FILE_FMT'), $flag);
        $handler = new \Monolog\Handler\RotatingFileHandler($logFile);
        $handler->setFilenameFormat('{filename}.{date}', 'Ymd');
        return $monolog->pushHandler($handler);
    });
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->routeMiddleware([
]);

$app->group(
    [
        'prefix' => 'demo/api',
        'middleware' => [
            'demo', 
        ],
        'namespace' => 'Gateway\Demo\Http\Controllers'
    ], function ($app) {
        require __DIR__.'/../routes/api.php';
});

return $app;
