<?php

namespace App\Core\Services\Mail;

use DI\Container;

class SMTPService extends \PHPMailer
{
    protected $recipients = [];
    protected $subject = "";
    protected $body = "";

    function __construct(Container $container)
    {
        parent::__construct();

        $this->isSMTP();
        $this->Host = $container->get('settings.mail.host');
        $this->Port = $container->get('settings.mail.port');
        $this->SMTPAuth = $container->get('settings.mail.enableAuth');;
        $this->Username = $container->get('settings.mail.username');
        $this->Password = $container->get('settings.mail.password');
        $this->From = $container->get('settings.mail.sender');
    }
}