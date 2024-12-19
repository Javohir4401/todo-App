<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use App\Router;
use App\Todo;

$router = new Router();
$todo = new Todo();

$router->get('/',fn()=> require 'Controllers/homeController.php');

$router->get('/register', fn() => view('register'));

$router->post('/register', fn() => require 'Controllers/storeUserController.php');

$router->get('/login', fn() => view('login'));

$router->post('/login', fn() => require 'Controllers/logincontroller.php');

$router->get('/todos', fn()=> require 'Controllers/getTodosController.php');

$router->post('/todos', fn()=> require 'Controllers/storeTodoController.php');

$router->get('/todos/{id}/edit', fn($todoId) => require 'Controllers/editController.php');

$router->get('/todos/{id}/delete', fn($todoId)=> require 'Controllers/deleteController.php');

$router->put('/todos/{id}/update', fn($todoId) => require 'Controllers/updateController.php');

$router->get('/logout', fn() => require 'Controllers/logauthcontroller.php');

