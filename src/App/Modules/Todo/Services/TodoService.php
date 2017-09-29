<?php

namespace App\Modules\Todo\Services;

use App\Modules\Todo\Model\Todo;
use App\Modules\Todo\Model\TodoQuery;

class TodoService
{
	function __construct(Todo $todo, TodoQuery $query)
    {
        $this->todo = $todo;
        $this->query = $query;
    }

	public function newTask($task, $description, $date)
	{
		$createdAt = new \DateTime();

		$this->todo->setTask($task);
		$this->todo->setDescription($description);
		$this->todo->setDate($date);
		$this->todo->setStatus('undone');
		$this->todo->setCreatedAt($createdAt->format('Y-m-d H:i:s'));		

		$this->todo->save();

		return $this->todo;
	}
}
