<?php

/**
 * This example code is no production code, it is used for training purposes.
 * The code deliberately has architectural problems and potential security problems.
 *
 * Copyright (c) 2010 Sebastian Bergmann, Stefan Priebsch
 * http://thePHP.cc
 */

class UserControllerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->db = new PDO('sqlite::memory:');
        $this->db->exec(file_get_contents(__DIR__ . '/schema.sql'));
        $this->db->exec("INSERT INTO Users (username, email) VALUES ('Stefan Priebsch', 'stefan@priebsch.de');");

        Configuration::init(array('DSN' => 'sqlite::memory:'));

        $this->mailer = $this->getMock('Mailer');
        
        $this->controller = new UserController;
    }

    protected function tearDown()
    {
        unset($this->db);
        unset($this->controller);
        Configuration::init(array());
        $_POST = array();
    }

    public function testDisplaysErrorViewWhenNoEmailAddressGiven()
    {
        $_POST['email'] = '';
        $view = $this->controller->resetPasswordAction($this->db, $this->mailer);
        $this->assertType('ErrorView', $view);
    }

    public function testDisplaysViewWhenEmailAddressGiven()
    {
        $this->mailer->expects($this->once())
                     ->method('sendMail');

        $_POST['email'] = 'stefan@priebsch.de';
        $view = $this->controller->resetPasswordAction($this->db, $this->mailer);
        $this->assertType('View', $view);
    }
}
