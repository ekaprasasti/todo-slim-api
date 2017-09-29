<?php

namespace App\Modules\Auth;

use Slim\Container;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Modules\Auth\Model\User;
use App\Modules\Auth\Model\UserQuery;
use App\Modules\Auth\Model\ResetToken;
use App\Modules\Auth\Model\ResetTokenQuery;
use App\Modules\Auth\Services\UserRegistrationService;
use App\Modules\Auth\Exceptions\EmailAlreadyRegisteredException;
use App\Core\Exceptions\HttpException;
use App\Core\Services\Mail\SMTPService;
use App\Core\Validator;
use Ramsey\Uuid\Uuid;

class AuthController
{
    function __construct(
        User $user,
        UserQuery $userQuery,
        SMTPService $smtp,
        UserRegistrationService $userRegService)
    {
        $this->user = $user;
        $this->userQuery = $userQuery;
        $this->smtp = $smtp;
        $this->userRegService = $userRegService;
    }

    public function register(Request $request, Response $response)
    {
        $ruleset = [
            'email' => 'required | email',
            'password' => 'required',
            'confirmation_password' => 'required'
        ];

        $validator = new Validator($request, $ruleset);
        $validator->validate();

        $params = $request->getParsedBody();
        $email = $params['email'];
        $password = $params['password'];
        $this->userRegService->register($email, $password);

        $responseData = [
            'success' => true,
            'message' => 'user registration success',
            'data' => null
        ];

        return $response->withJson($responseData, 200);
    }

    public function forgotPassword(Request $request, Response $response)
    {
        $ruleset = ['email' => 'required | email'];
        $validator = new Validator($request, $ruleset);
        $validator->validate();

        $params = $request->getParsedBody();
        $token = strtoupper(substr(uniqid(), -6));
        $user = UserQuery::create()->findOneByEmail($params['email']);
        if(!$user) {
            return $response->withJson(['success' => true], 200);
        }

        $resetToken = ResetTokenQuery::create()->findOneByEmail($params['email']);
        if($resetToken) {
            $resetToken->delete();
        }

        $resetToken = new ResetToken();
        $resetToken->setEmail($params['email']);
        $resetToken->setToken($token);

        $expiredAt = new \DateTime();
        $expiredAt->add(new \DateInterval('PT1H'));
        $resetToken->setExpiredAt($expiredAt->format('Y-m-d H:i:s'));
        $resetToken->save();

        $renderer = new PhpRenderer(__DIR__ . '/resources/mail/');
        $template = $renderer->render($response, 'reset_password.php', [
            'token' => $token,
            'email' => $params['email']
        ]);

        $this->smtp->addAddress($params['email']);
        $this->smtp->Body = (string)$template->getBody();
        $this->smtp->isHTML(true);
        if(!$this->smtp->send()) {
            throw new \Exception($this->smtp->ErrorInfo);
        }

        return $response->withJson(['success' => true], 200);
    }

    public function resetPassword(Request $request, Response $response)
    {
        $ruleset = [
            'reset_token' => 'required',
            'password' => 'required',
            'confirmation_password' => 'required'
        ];

        $validator = new Validator($request, $ruleset);
        $validator->validate();

        $params = $request->getParsedBody();
        $resetToken = ResetTokenQuery::create()->findOneByToken($params['reset_token']);
        if(!$resetToken) {
            throw new HttpException(400, 'invalid reset token');
        }

        $currentDate = new \DateTime();
        if($currentDate > $resetToken->getExpiredAt()) {
            throw new HttpException(400, 'reset token is expired');
        }

        $user = UserQuery::create()->findOneByEmail($resetToken->getEmail());

        $hashedPassword = password_hash($params['password'], PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);

        $user->setUpdatedAt($currentDate->format('Y-m-d H:i:s'));

        if($user->save()) {
            $resetToken->delete();
        }

        return $response->withJson([
            'success' => true,
            'message' => 'your password has been changed',
            'data' => null], 200);
    }
}
