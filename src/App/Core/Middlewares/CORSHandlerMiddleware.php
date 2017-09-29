<?php

namespace App\Core\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CORSHandlerMiddleware
{
    public function __invoke(
        Request $request,
        Response $response, $next)
    {
        //Determine if request is preflight or actual
/*
        if(!$request->hasHeader('Origin')) {
            return $response->withJson([
                'success' => false,
                'message' => '1000: Not a valid request'
            ], 400);
        }
*/
        //if the request is not preflight
        //continue processing the request and then append
        //the response with Access-Control-Allow-Origin header
        if(!$request->isOptions()) {
            $response = $next($request, $response);
            return $response->withHeader('Access-Control-Allow-Origin', '*');
        }

        if(!$request->hasHeader('Access-Control-Request-Method')) {
            return $next($request, $response);
        }

        //Start handle preflight request
        $accessControlMethod = $request->getHeader('Access-Control-Request-Method')[0];
        if(!$this->validateAccessControlMethod($accessControlMethod)) {
            return $response->withJson([
                'success' => false,
                'message' => '1000: Not a valid request'
            ], 400);
        }

        return $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type');
    }

    private function validateAccessControlMethod($method)
    {
        return in_array($method, ['GET', 'POST', 'PUT', 'DELETE', 'PATCH']);
    }
}
