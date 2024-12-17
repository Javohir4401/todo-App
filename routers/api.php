<?php

use App\Router;
use App\Todo;

$router = new Router();
$todo = new Todo();

$router->get('/api/todos', function () use ($todo) {
    apiResponse($todo->getAllTodos(2));
});

$router->get('/api/todos/{id}', function ($todoId) use ($todo) {
    apiResponse($todo->getTodo($todoId));
});

$router->put('/api/update/{id}', function ($todoId) use ($todo) {
    $todo->update($todoId, $_POST['title'], $_POST['status'], $_POST['dueDate']);
    apiResponse([
        'status' => 'OKEY',
        'message' => 'UPDATE FUNCTION SUCCESSFULLY'
        ]);
});

$router->get('/api/delete/{id}', function ($todoId) use ($todo) {
    $todo->destroy($todoId);
    header('Content-Type: application/json');
    $response = array('status' => 'OKEY', 'message' => 'DELETE FUNCTION SUCCESSFULLY');
    echo json_encode($response);
});
