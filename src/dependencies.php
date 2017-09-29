<?php

use Psr\Log\LoggerInterface;
use App\Core\Factories\LoggerFactory;

return [
    LoggerInterface::class => DI\factory([LoggerFactory::class, 'create'])
];
