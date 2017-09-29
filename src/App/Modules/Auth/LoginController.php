<?php

namespace App\Modules\Auth;
use Slim\Container;
use App\Core\Validator;
use App\Core\Exceptions\HttpException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface as Logger;
use App\Core\Services\Messaging\FirebaseCloudMessaging;
use App\Modules\Auth\Model\User as User;
use App\Modules\Auth\Model\UserQuery as UserQuery;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class LoginController
{
    function __construct( UserQuery $userQuery, User $user, FirebaseCloudMessaging $messaging)
    {
        $this->userQuery = $userQuery;
        $this->user = $user;
        $this->messaging = $messaging;
    }

    public function login(Request $request, Response $response)
    {
        $ruleset = [
            'email' => 'required | email',
            'password' => 'required'
        ];

        $validator = new Validator($request, $ruleset);
        $validator->validate();

        $params = $request->getParsedBody();
        if (!$user = $this->validateUser($params)) {
            throw new HttpException(401, 'invalid user credentials');
        }

        $lastLogin = new \DateTime();
        $user->setLastLogin($lastLogin->format('Y-m-d H:i:s'));
        $user->save();

        $token = $this->getToken($request, $user);
        $responseData = [
            "success" => true,
            "message" => "you are successfully logged in",
            "data" => [
                "id" => $user->getUuid(),
                "name" => $user->getEmail(),
                "photo" => $request->getUri()->getBaseUrl() . '/users/' . $user->getUuid() . '/avatar/',
                "email" => $user->getEmail(),
                "access_token" => (string) $token,
            ]
        ];

        return $response->withJson($responseData, 200);
    }

    private function validateUser($params)
    {
        $email = $params['email'];
        $user = $this->userQuery
                    ->create()
                    ->filterByEmail($email);

        if ($user->count() < 1) {
            return false;
        }

        $password = password_verify($params['password'], $user->findOne()->getPassword());
        if (!$password) {
            return false;
        }
        $user = $user->findOne();

        return $user;
    }

    private function getToken($request, $user)
    {
        $signer = new Sha256();
        $keychain = new Keychain();
        $token = (new Builder())->setIssuer($request->getUri()) // Configures the issuer (iss claim)
                        ->setAudience($request->getUri()) // Configures the audience (aud claim)
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                        ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                        ->set('uuid', $user->getUuid()) // Configures a new claim, called "uid"
                        ->sign($signer, $keychain->getPrivateKey('file://' . __DIR__ . '/../../../key.pem'))
                        ->getToken(); // Retrieves the generated token

        return $token;
    }
}
