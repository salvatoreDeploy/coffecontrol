<?php


require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use Source\Core\Session;
use CoffeeCode\Router\Router;

$session = new Session();
$route = new Router(url(), ":");

/**
 * WEB ROUTES
 */

$route->namespace("Source\App");
$route->get("/", "Web:home");
$route->get("/sobre", "Web:about");
$route->get("/termos", "Web:terms");

/**
 * ERROR ROUTES
 */

$route->namespace("Source\App")->group("/ooops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */

$route->dispatch();

/**
 * ERROR REDIRECT
 */

if($route->error()){
  $route->redirect("/ooops/{$route->error()}");
}

ob_end_flush();