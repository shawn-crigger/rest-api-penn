<?php


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

// ------------------------------------------------------------------------
// Application setup and middleware.
// ------------------------------------------------------------------------

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// ------------------------------------------------------------------------
// Routing setup.
// ------------------------------------------------------------------------

$app->get('/users', \App\Actions\UsersGetAction::class);
$app->post('/users', \App\Actions\UsersCreateAction::class);
$app->post('/users/{id}/earn', \App\Actions\UsersEarnAction::class);
$app->post('/users/{id}/redeem', \App\Actions\UsersRedeemAction::class);
$app->delete('/users/{id}', \App\Actions\UsersDeleteAction::class);

// ------------------------------------------------------------------------

$app->run();


