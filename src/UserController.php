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

    public function __construct(UsersTableDataGateway $gateway, Mailer $mailer)
    {
        $this->gateway = $gateway;
        $this->mailer = $mailer;
    }
    
    public function resetPasswordAction()
    {
        if (empty($_POST['email'])) {
            return new ErrorView('resetPassword', 'No email specified');
        }
    
        $record = $this->gateway->findUser($_POST['email']);

        if ($record === FALSE) {
            return new ErrorView('resetPassword', 'No user with email ' . $_POST['email']);
        }

        $code = CryptHelper::getConfirmationCode();

        $this->gateway->updateUser($code, $_POST['email']);
        
        $this->mailer->sendMail($_POST['email'], 'Password Reset', 'Confirmation code: ' . $code);

        return new View('passwordResetRequested');
    }
}
