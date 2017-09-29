<?php

$app->post('/auth/register', ['\App\Modules\Auth\AuthController', 'register']);
$app->post('/auth/forgot_password', ['\App\Modules\Auth\AuthController', 'forgotPassword']);
$app->post('/auth/reset_password', ['\App\Modules\Auth\AuthController', 'resetPassword']);

$app->post('/auth/login', ['\App\Modules\Auth\LoginController', 'login']);
$app->post('/auth/logout', ['\App\Modules\Auth\LogoutController', 'logout']);
$app->post('/auth/social_login', ['\App\Modules\Auth\SocialLoginController', 'login']);