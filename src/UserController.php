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
    public function resetPasswordAction($db, $mailer)
    {
        if (empty($_POST['email'])) {
            return new ErrorView('resetPassword', 'No email specified');
        }
    
        $gateway = new UsersTableDataGateway();
        $record = $gateway->findUser($db, $_POST['email']);

        if ($record === FALSE) {
            return new ErrorView('resetPassword', 'No user with email ' . $_POST['email']);
        }

        $code = CryptHelper::getConfirmationCode();

        $statement = $db->prepare('UPDATE Users SET code=:code WHERE email=:email;');

        $statement->bindValue(':code', $code, PDO::PARAM_STR);

        $statement->bindValue(':email', $_POST['email'], PDO::PARAM_STR);

        $statement->execute();
        
        $mailer->sendMail($_POST['email'], 'Password Reset', 'Confirmation code: ' . $code);

        return new View('passwordResetRequested');
    }
}

class UsersTableDataGateway
{
    public function findUser($db, $email)
    {
        $statement = $db->prepare('SELECT * FROM Users WHERE email=:email;');

        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
