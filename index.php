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

/**
 * Services Routes
 */

$route->get("/termos", "Web:terms");

/**
 * BLOG ROUTES
 */

$route->get("/blog", "Web:blog");
$route->get("/blog/page/{page}", "Web:blog");
$route->get("/blog/{postName}", "Web:blogPost");

/**
 * Authenticate Routes
 */

$route->get("/entrar", "Web:login");
$route->get("/recuperar", "Web:forget");
$route->get("/cadastrar", "Web:register");


/**
 * Options Routes
 */

$route->get("/confirma", "Web:confirm");
$route->get("/obrigado", "Web:success");

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