<?php

/**
 * This example code is no production code, it is used for training purposes.
 * The code deliberately has architectural problems and potential security problems.
 *
 * Copyright (c) 2010 Sebastian Bergmann, Stefan Priebsch
 * http://thePHP.cc
 */

/**
 * @covers UserController
 */
class UserControllerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->db = new PDO('sqlite::memory:');
        $this->db->exec(file_get_contents(__DIR__ . '/schema.sql'));
        $this->db->exec("INSERT INTO Users (username, email) VALUES ('Stefan Priebsch', 'stefan@priebsch.de');");

        Configuration::init(array('DSN' => 'sqlite::memory:'));


        $this->cryptHelper = $this->getMock('CryptHelper');

        $this->mailer = $this->getMock('Mailer');

        $this->gateway = $this->getMockBuilder('UsersTableDataGateway')
                              ->disableOriginalConstructor()
                              ->getMock();
        
        $this->controller = new UserController($this->gateway, $this->mailer, $this->cryptHelper);
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
        $this->mailer->expects($this->never())
                     ->method('sendMail');

        $_POST['email'] = '';
        $view = $this->controller->resetPasswordAction();
        $this->assertType('ErrorView', $view);
    }

    public function testDisplaysErrorWhenEmailIsUnknown()
    {
        $this->mailer->expects($this->never())
                     ->method('sendMail');

        $this->gateway->expects($this->once())
                      ->method('userExists')
                      ->with('stefan@priebsch.de')
                      ->will($this->returnValue(FALSE));

        $_POST['email'] = 'stefan@priebsch.de';
        $view = $this->controller->resetPasswordAction();
        $this->assertType('ErrorView', $view);
    }

    public function testDisplaysViewWhenEmailAddressGiven()
    {
        $this->mailer->expects($this->once())
                     ->method('sendMail')
                     ->with('stefan@priebsch.de');

        $this->gateway->expects($this->once())
                      ->method('userExists')
                      ->with('stefan@priebsch.de')
                      ->will($this->returnValue(TRUE));

        $this->gateway->expects($this->once())
                      ->method('updateUser')
                      ->with('123', 'stefan@priebsch.de');

        $this->cryptHelper->expects($this->once())
                          ->method('getConfirmationCode')
                          ->will($this->returnValue('123'));

        $_POST['email'] = 'stefan@priebsch.de';
        $view = $this->controller->resetPasswordAction();
        $this->assertType('View', $view);
    }
}
