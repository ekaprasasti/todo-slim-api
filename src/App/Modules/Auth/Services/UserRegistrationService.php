<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Model\User;
use App\Modules\Auth\Model\UserQuery;
use App\Modules\Auth\Exceptions\EmailAlreadyRegisteredException;
use Ramsey\Uuid\Uuid;

class UserRegistrationService
{
    function __construct(User $user, UserQuery $query)
    {
        $this->user = $user;
        $this->query = $query;
    }

    public function register($email, $password)
    {
        if($this->isEmailAlreadyRegistered($email)) {
            throw new EmailAlreadyRegisteredException;
        }

        $uuid = Uuid::uuid4()->toString();
        $createdAt = new \DateTime();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->user->setUuid($uuid);
        $this->user->setEmail($email);
        $this->user->setPassword($hashedPassword);
        $this->user->setCreatedAt($createdAt->format('Y-m-d H:i:s'));
        $this->user->save();

        return $this->user;
    }

    public function isEmailAlreadyRegistered($email)
    {
        if($this->query->findOneByEmail($email) === null) {
            return false;
        }

        return true;
    }
}
