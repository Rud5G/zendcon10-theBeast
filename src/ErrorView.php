<?php

/**
 * This example code is no production code, it is used for training purposes.
 * The code deliberately has architectural problems and potential security problems.
 *
 * Copyright (c) 2010 Sebastian Bergmann, Stefan Priebsch
 * http://thePHP.cc
 */

class ErrorView extends View
{
    protected $errorMessage;

    public function __construct($viewScript, $errorMessage)
    {
        $this->errorMessage = $errorMessage;
        parent::__construct($viewScript);
    }
}
