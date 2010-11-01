<?php

/**
 * This example code is no production code, it is used for training purposes.
 * The code deliberately has architectural problems and potential security problems.
 *
 * Copyright (c) 2010 Sebastian Bergmann, Stefan Priebsch
 * http://thePHP.cc
 */


class UsersTableDataGateway
{
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function userExists($email)
    {
        $statement = $this->db->prepare('SELECT email FROM Users WHERE email=:email;');

        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    public function updateUser($code, $email)
    {
        $statement = $this->db->prepare('UPDATE Users SET code=:code WHERE email=:email;');

        $statement->bindValue(':code', $code, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);

        return $statement->execute();
    }
}
