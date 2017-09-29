<?php

$app->post('/todo/tasks', ['\App\Modules\Todo\TodoController', 'newTask']);
$app->get('/todo/tasks', ['\App\Modules\Todo\TodoController', 'listTask']);
$app->delete('/todo/tasks/{id}', ['\App\Modules\Todo\TodoController', 'deleteTask']);
$app->put('/todo/tasks/status/{id}', ['\App\Modules\Todo\TodoController', 'statusTask']);
$app->put('/todo/tasks/{id}', ['\App\Modules\Todo\TodoController', 'editTask']);
