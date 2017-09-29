<?php

namespace App\Core;

use App\Core\Factories\LoggerFactory;
use App\Core\Exceptions\HttpException;

class ExceptionHandler
{
    public function __invoke($request, $response, $exception)
    {
        if($exception instanceof  HttpException) {
            return $response
                ->withStatus($exception->getStatusCode())
                ->withJson([
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'data' => null]);
        }

        //default handling
        $logger = LoggerFactory::create();
        $logger->error('Server Error', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        return $response
            ->withStatus(500)
            ->withJson([
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => null]);
    }
}
