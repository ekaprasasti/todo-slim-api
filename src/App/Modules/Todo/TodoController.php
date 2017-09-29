<?php

namespace App\Modules\Todo;

use Slim\Container;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Modules\Todo\Model\Todo;
use App\Modules\Todo\Model\TodoQuery;
use App\Modules\Todo\Services\TodoService;
use App\Core\Exceptions\HttpException;
use App\Core\Validator;

class TodoController
{
	function __construct(
		Todo $todo,
		TodoQuery $todoQuery)
	{
		$this->todo = $todo;
		$this->todoQuery = $todoQuery;
	}
	
	public function newTask(Request $request, Response $response)
	{
		$ruleset = [
			'task' => 'required',
			'description' => 'required',
			'date' => 'required'
		];	

		$validator = new Validator($request, $ruleset);
		$validator->validate();

		$params = $request->getParsedBody();
		$task = $params['task'];
		$description = $params['description'];
		$date = $params['date'];		

		$createdAt = new \DateTime();

        $this->todo->setTask($task);
        $this->todo->setDescription($description);
        $this->todo->setDate($date);
        $this->todo->setStatus('undone');
        $this->todo->setCreatedAt($createdAt->format('Y-m-d H:i:s'));

        $this->todo->save();


		$responseData = [
			'success' => true,
			'message' => 'new task success',
			'data' => null
		];
		
		return $response->withJson($responseData, 200);
	}	

	public function listTask(Request $request, Response $response)
	{
		$taskDetails = TodoQuery::create()->lastUpdatedFirst()->find();
		
		$dataLoop = array();
		foreach($taskDetails as $taskDetail){
			$dataLoop[] = [
				'id' => $taskDetail->getId(),
				'task' => $taskDetail->getTask(),
				'description' => $taskDetail->getDescription(),
				'status' => $taskDetail->getStatus(),
				'date' => $taskDetail->getDate()
			];
		}	

		$responseData = [
            'success' => true,
            'message' => 'success get tasks',
            'data' => $dataLoop
        ];

        return $response->withJson($responseData, 200);
	}

	public function deleteTask(Request $request, Response $response, $id)
	{
		TodoQuery::create()->filterById($id)->delete();

		$responseData = [
			'success' => true,
			'message' => 'delete by id '.$id.'.',
			'data' => null
		];

		return $response->withJson($responseData, 200);
	}

	public function statusTask(Request $request, Response $response, $id)
	{
		$ruleset = [
			'status' => 'required'
		];

		$validator = new Validator($request, $ruleset);
        $validator->validate();

        $params = $request->getParsedBody();
		$status = $params['status'];

		$task = TodoQuery::create()->findOneById($id);
		$task->setStatus($status);
		$task->save();

		$responseData = [
			'success' => true,
            'message' => 'status change to '.$status.'.',
            'data' => null
        ];

        return $response->withJson($responseData, 200);
	}

	public function editTask(Request $request, Response $response, $id)
	{
		$ruleset = [
            'task' => 'required',
            'description' => 'required',
            'date' => 'required'
        ];

        $validator = new Validator($request, $ruleset);
        $validator->validate();

        $params = $request->getParsedBody();
        $task = $params['task'];
        $description = $params['description'];
        $date = $params['date'];

		$taskEdit = TodoQuery::create()->findOneById($id);
        $taskEdit->setTask($task);
		$taskEdit->setDescription($description);
		$taskEdit->setDate($date);
        $taskEdit->save();	

        $responseData = [
            'success' => true,
            'message' => 'edit task success',
            'data' => null
        ];

        return $response->withJson($responseData, 200);	
	}
}
