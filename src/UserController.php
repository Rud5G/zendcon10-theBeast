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
        if (!isset($_POST['email'])) {
            return new ErrorView('resetPassword', 'No email specified');
        }
    
//        $db = new PDO(Configuration::get('DSN'));
        $statement = $db->prepare('SELECT * FROM Users WHERE email=:email;');

        $statement->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $statement->execute();
        $record = $statement->fetch(PDO::FETCH_ASSOC);

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

class Mailer
{
    public function sendMail($email, $subject, $body)
    {
        mail($email, $subject, $body);
    }
}
