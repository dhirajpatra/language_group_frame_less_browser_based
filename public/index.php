<?php
declare(strict_types=1);

use Curl\Curl;
use DI\ContainerBuilder;
use LanguageApp\Language;
use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Narrowspark\HttpEmitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;
use Relay\Relay;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use function DI\create;
use function FastRoute\simpleDispatcher;
use function DI\get;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// DI container 
$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);

// pussing DI to our main class
$containerBuilder->addDefinitions([
    Language::class => create(Language::class) -> constructor( get('Curl'), get('Response') ),
    'Curl' => function() {
        return new Curl;
    },
    'Response' => function() {
        return new Response();
    },
]);

$container = $containerBuilder->build();

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/language/{countryNameFirst}', Language::class);
    $r->get('/language/{countryNameFirst}/{countryNameSecond}', Language::class);
});

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
return $emitter->emit($response);
