<?php

/**
 * This example code is no production code, it is used for training purposes.
 * The code deliberately has architectural problems and potential security problems.
 *
 * Copyright (c) 2010 Sebastian Bergmann, Stefan Priebsch
 * http://thePHP.cc
 */

class UserController
{
    protected $gateway;
    protected $mailer;

    public function __construct(UsersTableDataGateway $gateway, Mailer $mailer, CryptHelper $cryptHelper)
    {
        $this->gateway = $gateway;
        $this->mailer = $mailer;
        $this->cryptHelper = $cryptHelper;
    }
    
    public function resetPasswordAction()
    {
        if (empty($_POST['email'])) {
            return new ErrorView('resetPassword', 'No email specified');
        }
    
        if (!$this->gateway->userExists($_POST['email'])) {
            return new ErrorView('resetPassword', 'No user with email ' . $_POST['email']);
        }

        $code = $this->cryptHelper->getConfirmationCode();

        $this->gateway->updateUser($code, $_POST['email']);
        
        $this->mailer->sendMail($_POST['email'], 'Password Reset', 'Confirmation code: ' . $code);

        return new View('passwordResetRequested');
    }
}
