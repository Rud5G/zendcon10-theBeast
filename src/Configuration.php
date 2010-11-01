<?php

/**
 * This example code is no production code, it is used for training purposes.
 * The code deliberately has architectural problems and potential security problems.
 *
 * Copyright (c) 2010 Sebastian Bergmann, Stefan Priebsch
 * http://thePHP.cc
 */

class Configuration
{
    protected static $values = array();
        
    public static function init(array $values)
    {
        self::$values = $values;
    }

    public static function get($key)
    {
        if (!isset(self::$values[$key])) {
            throw new Exception('No such key');
        }
        return self::$values[$key];
    }
}
