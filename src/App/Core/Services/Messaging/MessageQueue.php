<?php

namespace App\Core\Services\Messaging;

use App\Core\Model\MessageQueue as Queue;
use App\Core\Model\MessageQueueQuery as Query;

class MessageQueue
{
    function __construct(Queue $queue, Query $query)
    {
        $this->queue = $queue;
        $this->query = $query;
    }

    public function push($destination, $message)
    {
        $timestamp = new \DateTime();
        $this->queue->setDestination($destination);
        $this->queue->setMessage($message);
        $this->queue->setTimestamp($timestamp->format('Y-m-d H:i:s'));
        $this->queue->save();
    }

    public function pop()
    {

    }

    public function peek()
    {
        return $this->query->create()->findOne();
    }
}
