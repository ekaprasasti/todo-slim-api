<?php

namespace App\Modules\Auth;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Modules\Auth\Model\User;
use App\Modules\Auth\Model\UserQuery;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use RuntimeException;


class LogoutController
{
    function __construct(User $user, UserQuery $userQuery)
    {
        $this->user = $user;
        $this->userQuery = $userQuery;
    }

    public function logout(Request $request, Response $response)
    {
        $authorization = $request->getHeaderLine('Authorization');
        if(!$authorization) {
            return $response->withJson([
                'success' => false,
                'message' => 'no "Authorization" header on request',
                'data' => null], 400);
        }

        $authToken = $request->getHeaderLine('Authorization');
        $jwt = (new Parser())->parse((string) $authToken);
        $signer = new SHA256();
        $keychain = new KeyChain();
        $isValid = $jwt->verify($signer, $keychain->getPublicKey('file://' . __DIR__ . '/../../../key.pub'));

        if(!$isValid) {
            return $response->withJson([
                'success' => false,
                'message' => 'invalid access token',
                'data' => null], 401);
        }

        $responseData = [
            "success" => true,
            "message" => "you are successfully logged out",
            "data" => null
        ];

        return $response->withJson($responseData, 200);
    }
}
