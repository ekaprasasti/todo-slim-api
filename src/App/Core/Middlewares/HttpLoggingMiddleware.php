<?php

namespace App\Core\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Core\Factories\LoggerFactory;

class HttpLoggingMiddleware
{
    public function __invoke(
        Request $request,
        Response $response, $next)
    {
        $uri = $request->getUri();
        $logger = LoggerFactory::create();
        $logger->info('Request', [
            'scheme' => $uri->getScheme(),
            'method' => $request->getOriginalMethod(),
            'path' => $uri->getPath(),
            'ip' => $request->getAttribute('ip_address'),
        ]);

        $response = $next($request, $response);
        $body = json_decode((string)$response->getBody());

        $logger->info('Response', [
            'scheme' => $uri->getScheme(),
            'method' => $request->getOriginalMethod(),
            'path' => $uri->getPath(),
            'ip' => $request->getAttribute('ip_address'),
            'status_code' => $response->getStatusCode(),
            'message' => $body->message
        ]);

        return $response;
    }
}
