<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);


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
$route->group("/blog");
$route->get("/", "Web:blog");
$route->get("/p/{page}", "Web:blog");
$route->get("/{uri}", "Web:blogPost");
$route->post("/buscar", "Web:blogSearch");
$route->get("/buscar", "Web:blogSearch");
$route->post("/buscar/categoria", "Web:blogCategory");
$route->get("/buscar/categoria", "Web:blogCategory");

/**
 * Authenticate Routes
 */
$route->group(null);
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");

$route->get("/cadastrar", "Web:register");
$route->post("/cadastrar", "Web:register");

$route->get("/recuperar", "Web:forget");
$route->post("/recuperar", "Web:forget");

$route->get("/recuperar/{code}", "Web:reset");
$route->post("/recuperar/resetar", "Web:reset");


/**
 * Options Routes
 */

$route->get("/confirma", "Web:confirm");
$route->get("/obrigado/{email}", "Web:success");

/* 
* APP Route
*/

$route->group("/app");
$route->get("/", "App:home");
$route->get("/sair", "App:logout");


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